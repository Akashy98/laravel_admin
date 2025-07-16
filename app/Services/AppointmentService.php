<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\AppointmentSetting;
use App\Models\User;
use App\Models\Astrologer;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AppointmentService
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Create instant appointment
     */
    public function createInstantAppointment(array $data): array
    {
        try {
            return DB::transaction(function () use ($data) {
                $user = User::find($data['user_id']);
                $serviceType = $data['service_type'];
                $duration = $data['duration_minutes'] ?? 15;
                $isBroadcast = $data['is_broadcast'] ?? false;
                $userNotes = $data['user_notes'] ?? null;

                // Calculate pricing
                $pricing = $this->calculatePricing($serviceType, $duration, $user);

                // Check if user has sufficient balance
                if ($pricing['final_amount'] > 0) {
                    $wallet = $user->wallet;
                    if (!$wallet || $wallet->balance < $pricing['final_amount']) {
                        return [
                            'success' => false,
                            'message' => 'Insufficient wallet balance',
                            'data' => [
                                'required_amount' => $pricing['final_amount'],
                                'available_balance' => $wallet ? $wallet->balance : 0
                            ]
                        ];
                    }
                }

                // Create appointment
                $appointment = Appointment::create([
                    'user_id' => $user->id,
                    'service_type' => $serviceType,
                    'booking_type' => 'instant',
                    'duration_minutes' => $duration,
                    'base_amount' => $pricing['base_amount'],
                    'final_amount' => $pricing['final_amount'],
                    'payment_timing' => AppointmentSetting::getPaymentTiming(),
                    'is_broadcast' => $isBroadcast,
                    'max_wait_time' => AppointmentSetting::getMaxWaitTime(),
                    'user_notes' => $userNotes,
                    'status' => 'pending',
                    'payment_status' => 'pending'
                ]);

                // Handle payment if required on request
                if ($appointment->isPaymentOnRequest() && $pricing['final_amount'] > 0) {
                    $paymentResult = $this->processPayment($appointment, $pricing['final_amount']);
                    if (!$paymentResult['success']) {
                        $appointment->delete();
                        return $paymentResult;
                    }
                }

                // Find available astrologers
                $availableAstrologers = $this->findAvailableAstrologers($serviceType, $isBroadcast);

                if (empty($availableAstrologers)) {
                    $appointment->update(['status' => 'no_astrologer']);
                    return [
                        'success' => false,
                        'message' => 'No astrologers available at the moment',
                        'data' => [
                            'appointment_id' => $appointment->id,
                            'status' => 'no_astrologer'
                        ]
                    ];
                }

                            // If not broadcast, assign to first available astrologer
            if (!$isBroadcast && !empty($availableAstrologers)) {
                $astrologerData = $availableAstrologers[0];
                $astrologerId = $astrologerData['id'];

                // If this is a fake astrologer, we need to find a real one to assign
                if ($astrologerData['is_fake']) {
                    $realAstrologer = $this->findRealAstrologer($serviceType);
                    if ($realAstrologer) {
                        $appointment->assignAstrologer($realAstrologer->id);
                        $appointment->update(['original_astrologer_id' => $astrologerId]);
                    } else {
                        $appointment->update(['status' => 'no_astrologer']);
                    }
                } else {
                    $appointment->assignAstrologer($astrologerId);
                }
            }

                return [
                    'success' => true,
                    'message' => 'Instant appointment created successfully',
                    'data' => [
                        'appointment_id' => $appointment->id,
                        'status' => $appointment->status,
                        'estimated_wait_time' => $this->getEstimatedWaitTime($availableAstrologers),
                        'available_astrologers_count' => count($availableAstrologers),
                        'pricing' => $pricing
                    ]
                ];
            });
        } catch (\Exception $e) {
            Log::error('Error creating instant appointment: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error creating appointment: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Create scheduled appointment
     */
    public function createScheduledAppointment(array $data): array
    {
        try {
            return DB::transaction(function () use ($data) {
                $user = User::find($data['user_id']);
                $astrologerId = $data['astrologer_id'];
                $serviceType = $data['service_type'];
                $scheduledAt = Carbon::parse($data['scheduled_at']);
                $duration = $data['duration_minutes'] ?? 15;
                $userNotes = $data['user_notes'] ?? null;

                // Validate astrologer availability
                $astrologer = Astrologer::find($astrologerId);
                if (!$astrologer || !$this->isAstrologerAvailable($astrologer, $scheduledAt, $duration)) {
                    return [
                        'success' => false,
                        'message' => 'Astrologer is not available at the selected time'
                    ];
                }

                // Calculate pricing
                $pricing = $this->calculatePricing($serviceType, $duration, $user);

                // Check if user has sufficient balance
                if ($pricing['final_amount'] > 0) {
                    $wallet = $user->wallet;
                    if (!$wallet || $wallet->balance < $pricing['final_amount']) {
                        return [
                            'success' => false,
                            'message' => 'Insufficient wallet balance',
                            'data' => [
                                'required_amount' => $pricing['final_amount'],
                                'available_balance' => $wallet ? $wallet->balance : 0
                            ]
                        ];
                    }
                }

                // Create appointment
                $appointment = Appointment::create([
                    'user_id' => $user->id,
                    'astrologer_id' => $astrologerId,
                    'service_type' => $serviceType,
                    'booking_type' => 'scheduled',
                    'scheduled_at' => $scheduledAt,
                    'duration_minutes' => $duration,
                    'base_amount' => $pricing['base_amount'],
                    'final_amount' => $pricing['final_amount'],
                    'payment_timing' => AppointmentSetting::getPaymentTiming(),
                    'user_notes' => $userNotes,
                    'status' => 'pending',
                    'payment_status' => 'pending'
                ]);

                // Handle payment if required on request
                if ($appointment->isPaymentOnRequest() && $pricing['final_amount'] > 0) {
                    $paymentResult = $this->processPayment($appointment, $pricing['final_amount']);
                    if (!$paymentResult['success']) {
                        $appointment->delete();
                        return $paymentResult;
                    }
                }

                return [
                    'success' => true,
                    'message' => 'Scheduled appointment created successfully',
                    'data' => [
                        'appointment_id' => $appointment->id,
                        'scheduled_at' => $scheduledAt->toISOString(),
                        'pricing' => $pricing
                    ]
                ];
            });
        } catch (\Exception $e) {
            Log::error('Error creating scheduled appointment: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error creating appointment: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Accept appointment by astrologer
     */
    public function acceptAppointment(int $appointmentId, int $astrologerId): array
    {
        try {
            return DB::transaction(function () use ($appointmentId, $astrologerId) {
                $appointment = Appointment::find($appointmentId);

                if (!$appointment) {
                    return [
                        'success' => false,
                        'message' => 'Appointment not found'
                    ];
                }

                if (!$appointment->canBeAccepted()) {
                    return [
                        'success' => false,
                        'message' => 'Appointment cannot be accepted'
                    ];
                }

                // Check if astrologer is available
                if (!$this->isAstrologerAvailableForAppointment($astrologerId, $appointment)) {
                    return [
                        'success' => false,
                        'message' => 'Astrologer is not available for this appointment'
                    ];
                }

                // Assign astrologer and accept
                $appointment->assignAstrologer($astrologerId);
                $appointment->markAsAccepted();

                // Handle payment if required on accept
                if ($appointment->isPaymentOnAccept() && $appointment->getAmountToPay() > 0) {
                    $paymentResult = $this->processPayment($appointment, $appointment->getAmountToPay());
                    if (!$paymentResult['success']) {
                        $appointment->markAsCancelled('Payment failed', 'system');
                        return $paymentResult;
                    }
                }

                return [
                    'success' => true,
                    'message' => 'Appointment accepted successfully',
                    'data' => [
                        'appointment_id' => $appointment->id,
                        'status' => $appointment->status,
                        'payment_status' => $appointment->payment_status
                    ]
                ];
            });
        } catch (\Exception $e) {
            Log::error('Error accepting appointment: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error accepting appointment: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Start appointment session
     */
    public function startAppointment(int $appointmentId): array
    {
        try {
            $appointment = Appointment::find($appointmentId);

            if (!$appointment) {
                return [
                    'success' => false,
                    'message' => 'Appointment not found'
                ];
            }

            if (!$appointment->canBeStarted()) {
                return [
                    'success' => false,
                    'message' => 'Appointment cannot be started'
                ];
            }

            $appointment->markAsInProgress();

            return [
                'success' => true,
                'message' => 'Appointment session started',
                'data' => [
                    'appointment_id' => $appointment->id,
                    'session_id' => $appointment->session_id,
                    'duration_minutes' => $appointment->getDurationInMinutes(),
                    'started_at' => $appointment->started_at->toISOString()
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Error starting appointment: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error starting appointment: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Complete appointment session
     */
    public function completeAppointment(int $appointmentId): array
    {
        try {
            $appointment = Appointment::find($appointmentId);

            if (!$appointment) {
                return [
                    'success' => false,
                    'message' => 'Appointment not found'
                ];
            }

            if (!$appointment->canBeCompleted()) {
                return [
                    'success' => false,
                    'message' => 'Appointment cannot be completed'
                ];
            }

            $appointment->markAsCompleted();

            return [
                'success' => true,
                'message' => 'Appointment completed successfully',
                'data' => [
                    'appointment_id' => $appointment->id,
                    'session_duration' => $appointment->getSessionDuration(),
                    'ended_at' => $appointment->ended_at->toISOString()
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Error completing appointment: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error completing appointment: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cancel appointment
     */
    public function cancelAppointment(int $appointmentId, string $reason = null, string $cancelledBy = 'user'): array
    {
        try {
            return DB::transaction(function () use ($appointmentId, $reason, $cancelledBy) {
                $appointment = Appointment::find($appointmentId);

                if (!$appointment) {
                    return [
                        'success' => false,
                        'message' => 'Appointment not found'
                    ];
                }

                if (!$appointment->canBeCancelled()) {
                    return [
                        'success' => false,
                        'message' => 'Appointment cannot be cancelled'
                    ];
                }

                // Handle refund if applicable
                $refundAmount = $this->calculateRefundAmount($appointment);

                if ($refundAmount > 0) {
                    $refundResult = $this->processRefund($appointment, $refundAmount);
                    if (!$refundResult['success']) {
                        return $refundResult;
                    }
                }

                $appointment->markAsCancelled($reason, $cancelledBy);

                return [
                    'success' => true,
                    'message' => 'Appointment cancelled successfully',
                    'data' => [
                        'appointment_id' => $appointment->id,
                        'refund_amount' => $refundAmount,
                        'cancellation_reason' => $reason
                    ]
                ];
            });
        } catch (\Exception $e) {
            Log::error('Error cancelling appointment: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error cancelling appointment: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Calculate pricing for appointment
     */
    protected function calculatePricing(string $serviceType, int $duration, User $user): array
    {
        $pricing = AppointmentSetting::getServicePricing();
        $servicePricing = $pricing[$serviceType] ?? $pricing['chat'];

        $baseAmount = $servicePricing['base_price'] + ($servicePricing['per_minute'] * $duration);
        $finalAmount = $baseAmount;

        // Apply discounts
        $discountSettings = AppointmentSetting::getDiscountSettings();
        if ($discountSettings['enabled']) {
            // First time discount
            $userAppointmentsCount = $user->appointments()->count();
            if ($userAppointmentsCount === 0 && $discountSettings['first_time_discount'] > 0) {
                $discount = ($baseAmount * $discountSettings['first_time_discount']) / 100;
                $finalAmount -= $discount;
            }

            // Bulk discount
            if ($discountSettings['bulk_discount']['enabled'] &&
                $userAppointmentsCount >= $discountSettings['bulk_discount']['threshold']) {
                $discount = ($baseAmount * $discountSettings['bulk_discount']['discount']) / 100;
                $finalAmount -= $discount;
            }
        }

        return [
            'base_amount' => $baseAmount,
            'final_amount' => max(0, $finalAmount),
            'discount_amount' => $baseAmount - $finalAmount,
            'discount_percentage' => $baseAmount > 0 ? (($baseAmount - $finalAmount) / $baseAmount) * 100 : 0
        ];
    }

    /**
     * Find available astrologers
     */
    protected function findAvailableAstrologers(string $serviceType, bool $isBroadcast = false): array
    {
        $query = Astrologer::where('status', 'approved')
            ->where('is_online', true);

        // Filter by service type
        $query->whereHas('services', function ($q) use ($serviceType) {
            $q->where('name', $serviceType);
        });

        // If not broadcast, get only available astrologers
        if (!$isBroadcast) {
            $query->whereDoesntHave('appointments', function ($q) {
                $q->whereIn('status', ['pending', 'accepted', 'in_progress']);
            });
        }

        // Get both fake and real astrologers
        $astrologers = $query->orderBy('total_rating', 'desc')
            ->limit($isBroadcast ? AppointmentSetting::getBroadcastSettings()['max_astrologers'] : 1)
            ->get();

        $result = [];
        foreach ($astrologers as $astrologer) {
            $astrologerData = $astrologer->toArray();

            // Add fake astrologer flag
            $astrologerData['is_fake'] = (bool) $astrologer->is_fake;

            $result[] = $astrologerData;
        }

        return $result;
    }

    /**
     * Check if astrologer is available
     */
    protected function isAstrologerAvailable(Astrologer $astrologer, Carbon $scheduledAt, int $duration): bool
    {
        // Check if astrologer is online and approved
        if ($astrologer->status !== 'approved' || !$astrologer->is_online) {
            return false;
        }

        // Check for conflicting appointments
        $endTime = $scheduledAt->copy()->addMinutes($duration);

        $conflictingAppointment = $astrologer->appointments()
            ->whereIn('status', ['pending', 'accepted', 'in_progress'])
            ->where(function ($query) use ($scheduledAt, $endTime) {
                $query->whereBetween('scheduled_at', [$scheduledAt, $endTime])
                    ->orWhereBetween(DB::raw('DATE_ADD(scheduled_at, INTERVAL duration_minutes MINUTE)'), [$scheduledAt, $endTime]);
            })
            ->exists();

        return !$conflictingAppointment;
    }

    /**
     * Check if astrologer is available for appointment
     */
    protected function isAstrologerAvailableForAppointment(int $astrologerId, Appointment $appointment): bool
    {
        $astrologer = Astrologer::find($astrologerId);

        if (!$astrologer || $astrologer->status !== 'approved' || !$astrologer->is_online) {
            return false;
        }

        if ($appointment->isScheduled()) {
            return $this->isAstrologerAvailable($astrologer, $appointment->scheduled_at, $appointment->getDurationInMinutes());
        }

        // For instant appointments, check if astrologer has no active appointments
        return !$astrologer->appointments()
            ->whereIn('status', ['pending', 'accepted', 'in_progress'])
            ->exists();
    }

    /**
     * Process payment
     */
    protected function processPayment(Appointment $appointment, float $amount): array
    {
        try {
            $user = $appointment->user;
            $wallet = $user->wallet;

            if (!$wallet || $wallet->balance < $amount) {
                return [
                    'success' => false,
                    'message' => 'Insufficient wallet balance'
                ];
            }

            // Deduct from wallet
            $wallet->balance -= $amount;
            $wallet->save();

            // Create wallet transaction
            $wallet->transactions()->create([
                'amount' => $amount,
                'type' => 'debit',
                'description' => "Payment for appointment #{$appointment->id}",
                'meta' => json_encode([
                    'appointment_id' => $appointment->id,
                    'service_type' => $appointment->service_type,
                    'booking_type' => $appointment->booking_type,
                    'status' => 'completed',
                    'completed_at' => now()->toISOString()
                ])
            ]);

            // Update appointment payment
            $appointment->addPaymentAmount($amount);
            $appointment->updatePaymentStatus('paid');

            return [
                'success' => true,
                'message' => 'Payment processed successfully'
            ];
        } catch (\Exception $e) {
            Log::error('Error processing payment: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error processing payment: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Calculate refund amount
     */
    protected function calculateRefundAmount(Appointment $appointment): float
    {
        if ($appointment->amount_paid <= 0) {
            return 0;
        }

        $cancellationSettings = AppointmentSetting::getCancellationSettings();
        $hoursUntilAppointment = now()->diffInHours($appointment->scheduled_at ?? $appointment->requested_at, false);

        if ($hoursUntilAppointment >= $cancellationSettings['free_cancellation_hours']) {
            return $appointment->amount_paid; // Full refund
        } elseif ($hoursUntilAppointment >= $cancellationSettings['partial_refund_hours']) {
            return $appointment->amount_paid * 0.5; // 50% refund
        } else {
            return 0; // No refund
        }
    }

    /**
     * Process refund
     */
    protected function processRefund(Appointment $appointment, float $amount): array
    {
        try {
            $user = $appointment->user;
            $wallet = $user->wallet;

            if (!$wallet) {
                return [
                    'success' => false,
                    'message' => 'User wallet not found'
                ];
            }

            // Add to wallet
            $wallet->balance += $amount;
            $wallet->save();

            // Create wallet transaction
            $wallet->transactions()->create([
                'amount' => $amount,
                'type' => 'credit',
                'description' => "Refund for cancelled appointment #{$appointment->id}",
                'meta' => json_encode([
                    'appointment_id' => $appointment->id,
                    'refund_type' => 'cancellation',
                    'status' => 'completed',
                    'completed_at' => now()->toISOString()
                ])
            ]);

            // Update appointment payment
            $appointment->updatePaymentStatus('refunded');

            return [
                'success' => true,
                'message' => 'Refund processed successfully'
            ];
        } catch (\Exception $e) {
            Log::error('Error processing refund: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error processing refund: ' . $e->getMessage()
            ];
        }
    }

        /**
     * Find real astrologer for fake astrologer assignment
     */
    protected function findRealAstrologer(string $serviceType): ?Astrologer
    {
        return Astrologer::where('status', 'approved')
            ->where('is_online', true)
            ->where('is_fake', false)
            ->whereHas('services', function ($q) use ($serviceType) {
                $q->where('name', $serviceType);
            })
            ->whereDoesntHave('appointments', function ($q) {
                $q->whereIn('status', ['pending', 'accepted', 'in_progress']);
            })
            ->orderBy('total_rating', 'desc')
            ->first();
    }

    /**
     * Get estimated wait time
     */
    protected function getEstimatedWaitTime(array $availableAstrologers): int
    {
        if (empty($availableAstrologers)) {
            return AppointmentSetting::getMaxWaitTime();
        }

        // Simple calculation based on available astrologers
        $baseWaitTime = 60; // 1 minute base
        $waitTimePerAstrologer = 30; // 30 seconds per additional astrologer

        return $baseWaitTime + (count($availableAstrologers) * $waitTimePerAstrologer);
    }
}
