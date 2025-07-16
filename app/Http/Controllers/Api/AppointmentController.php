<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Services\AppointmentService;
use App\Models\Appointment;
use App\Models\AppointmentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends BaseController
{
    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    /**
     * Get appointment settings
     */
    public function getSettings(Request $request)
    {
        try {
            $settings = [
                'instant_booking_enabled' => AppointmentSetting::getInstantBookingEnabled(),
                'scheduled_booking_enabled' => AppointmentSetting::getScheduledBookingEnabled(),
                'payment_timing' => AppointmentSetting::getPaymentTiming(),
                'max_wait_time' => AppointmentSetting::getMaxWaitTime(),
                'available_durations' => AppointmentSetting::getAvailableDurations(),
                'service_pricing' => AppointmentSetting::getServicePricing(),
                'discount_settings' => AppointmentSetting::getDiscountSettings(),
                'broadcast_settings' => AppointmentSetting::getBroadcastSettings(),
                'cancellation_settings' => AppointmentSetting::getCancellationSettings(),
                'rating_settings' => AppointmentSetting::getRatingSettings()
            ];

            return $this->successResponse($settings, 'Appointment settings retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error retrieving settings: ' . $e->getMessage());
        }
    }

    /**
     * Create instant appointment
     */
    public function createInstant(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'service_type' => 'required|in:chat,call,video_call',
                'duration_minutes' => 'nullable|integer|in:10,15,20',
                'is_broadcast' => 'nullable|boolean',
                'user_notes' => 'nullable|string|max:1000'
            ]);

            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors());
            }

            $user = $request->user();
            if (!$user) {
                return $this->unauthorizedResponse('User not authenticated');
            }

            $data = array_merge($request->all(), ['user_id' => $user->id]);
            $result = $this->appointmentService->createInstantAppointment($data);

            if ($result['success']) {
                return $this->successResponse($result['data'], $result['message']);
            } else {
                return $this->errorResponse($result['message'], 400, $result['data'] ?? null);
            }
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error creating instant appointment: ' . $e->getMessage());
        }
    }

    /**
     * Create scheduled appointment
     */
    public function createScheduled(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'astrologer_id' => 'required|exists:astrologers,id',
                'service_type' => 'required|in:chat,call,video_call',
                'scheduled_at' => 'required|date|after:now',
                'duration_minutes' => 'nullable|integer|in:10,15,20',
                'user_notes' => 'nullable|string|max:1000'
            ]);

            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors());
            }

            $user = $request->user();
            if (!$user) {
                return $this->unauthorizedResponse('User not authenticated');
            }

            $data = array_merge($request->all(), ['user_id' => $user->id]);
            $result = $this->appointmentService->createScheduledAppointment($data);

            if ($result['success']) {
                return $this->successResponse($result['data'], $result['message']);
            } else {
                return $this->errorResponse($result['message'], 400, $result['data'] ?? null);
            }
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error creating scheduled appointment: ' . $e->getMessage());
        }
    }

    /**
     * Accept appointment (for astrologers)
     */
    public function accept(Request $request, $appointmentId)
    {
        try {
            $user = $request->user();
            if (!$user || !$user->astrologer) {
                return $this->unauthorizedResponse('Astrologer not authenticated');
            }

            $result = $this->appointmentService->acceptAppointment($appointmentId, $user->astrologer->id);

            if ($result['success']) {
                return $this->successResponse($result['data'], $result['message']);
            } else {
                return $this->errorResponse($result['message'], 400, $result['data'] ?? null);
            }
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error accepting appointment: ' . $e->getMessage());
        }
    }

    /**
     * Start appointment session
     */
    public function start(Request $request, $appointmentId)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return $this->unauthorizedResponse('User not authenticated');
            }

            // Check if user is authorized to start this appointment
            $appointment = Appointment::find($appointmentId);
            if (!$appointment) {
                return $this->notFoundResponse('Appointment not found');
            }

            if ($appointment->user_id !== $user->id &&
                (!$user->astrologer || $appointment->astrologer_id !== $user->astrologer->id)) {
                return $this->forbiddenResponse('Not authorized to start this appointment');
            }

            $result = $this->appointmentService->startAppointment($appointmentId);

            if ($result['success']) {
                return $this->successResponse($result['data'], $result['message']);
            } else {
                return $this->errorResponse($result['message'], 400, $result['data'] ?? null);
            }
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error starting appointment: ' . $e->getMessage());
        }
    }

    /**
     * Complete appointment session
     */
    public function complete(Request $request, $appointmentId)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return $this->unauthorizedResponse('User not authenticated');
            }

            // Check if user is authorized to complete this appointment
            $appointment = Appointment::find($appointmentId);
            if (!$appointment) {
                return $this->notFoundResponse('Appointment not found');
            }

            if ($appointment->user_id !== $user->id &&
                (!$user->astrologer || $appointment->astrologer_id !== $user->astrologer->id)) {
                return $this->forbiddenResponse('Not authorized to complete this appointment');
            }

            $result = $this->appointmentService->completeAppointment($appointmentId);

            if ($result['success']) {
                return $this->successResponse($result['data'], $result['message']);
            } else {
                return $this->errorResponse($result['message'], 400, $result['data'] ?? null);
            }
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error completing appointment: ' . $e->getMessage());
        }
    }

    /**
     * Cancel appointment
     */
    public function cancel(Request $request, $appointmentId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'reason' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors());
            }

            $user = $request->user();
            if (!$user) {
                return $this->unauthorizedResponse('User not authenticated');
            }

            // Check if user is authorized to cancel this appointment
            $appointment = Appointment::find($appointmentId);
            if (!$appointment) {
                return $this->notFoundResponse('Appointment not found');
            }

            if ($appointment->user_id !== $user->id &&
                (!$user->astrologer || $appointment->astrologer_id !== $user->astrologer->id)) {
                return $this->forbiddenResponse('Not authorized to cancel this appointment');
            }

            $cancelledBy = $user->astrologer ? 'astrologer' : 'user';
            $result = $this->appointmentService->cancelAppointment(
                $appointmentId,
                $request->reason,
                $cancelledBy
            );

            if ($result['success']) {
                return $this->successResponse($result['data'], $result['message']);
            } else {
                return $this->errorResponse($result['message'], 400, $result['data'] ?? null);
            }
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error cancelling appointment: ' . $e->getMessage());
        }
    }

    /**
     * Get user's appointments
     */
    public function getUserAppointments(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return $this->unauthorizedResponse('User not authenticated');
            }

            $perPage = $request->get('per_page', 15);
            $status = $request->get('status');
            $bookingType = $request->get('booking_type');
            $serviceType = $request->get('service_type');

            $query = $user->appointments()->with(['astrologer.user', 'astrologer.skills.category']);

            if ($status) {
                $query->where('status', $status);
            }

            if ($bookingType) {
                $query->where('booking_type', $bookingType);
            }

            if ($serviceType) {
                $query->where('service_type', $serviceType);
            }

            $appointments = $query->orderBy('created_at', 'desc')->paginate($perPage);

            $formattedAppointments = $appointments->getCollection()->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'service_type' => $appointment->service_type,
                    'booking_type' => $appointment->booking_type,
                    'status' => $appointment->status,
                    'duration_minutes' => $appointment->getDurationInMinutes(),
                    'scheduled_at' => $appointment->scheduled_at ? $appointment->scheduled_at->toISOString() : null,
                    'requested_at' => $appointment->requested_at ? $appointment->requested_at->toISOString() : null,
                    'accepted_at' => $appointment->accepted_at ? $appointment->accepted_at->toISOString() : null,
                    'started_at' => $appointment->started_at ? $appointment->started_at->toISOString() : null,
                    'ended_at' => $appointment->ended_at ? $appointment->ended_at->toISOString() : null,
                    'base_amount' => (float) $appointment->base_amount,
                    'final_amount' => (float) $appointment->final_amount,
                    'amount_paid' => (float) $appointment->amount_paid,
                    'payment_status' => $appointment->payment_status,
                    'user_notes' => $appointment->user_notes,
                    'astrologer_notes' => $appointment->astrologer_notes,
                    'rating' => $appointment->rating,
                    'review' => $appointment->review,
                    'astrologer' => $appointment->astrologer ? [
                        'id' => $appointment->astrologer->id,
                        'name' => $appointment->astrologer->user->name,
                        'specialization' => $appointment->astrologer->skills->first() ?
                            $appointment->astrologer->skills->first()->category->name : 'General',
                        'experience' => $appointment->astrologer->experience_years,
                        'rating' => $appointment->astrologer->total_rating,
                        'profile_image' => $appointment->astrologer->user->profile_image,
                    ] : null,
                    'remaining_time' => $appointment->getRemainingTime(),
                    'session_duration' => $appointment->getSessionDuration()
                ];
            });

            $data = [
                'appointments' => $formattedAppointments,
                'pagination' => [
                    'current_page' => $appointments->currentPage(),
                    'last_page' => $appointments->lastPage(),
                    'per_page' => $appointments->perPage(),
                    'total' => $appointments->total()
                ]
            ];

            return $this->successResponse($data, 'User appointments retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error retrieving appointments: ' . $e->getMessage());
        }
    }

    /**
     * Get astrologer's appointments
     */
    public function getAstrologerAppointments(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user || !$user->astrologer) {
                return $this->unauthorizedResponse('Astrologer not authenticated');
            }

            $perPage = $request->get('per_page', 15);
            $status = $request->get('status');
            $bookingType = $request->get('booking_type');
            $serviceType = $request->get('service_type');

            $query = $user->astrologer->appointments()->with(['user']);

            if ($status) {
                $query->where('status', $status);
            }

            if ($bookingType) {
                $query->where('booking_type', $bookingType);
            }

            if ($serviceType) {
                $query->where('service_type', $serviceType);
            }

            $appointments = $query->orderBy('created_at', 'desc')->paginate($perPage);

            $formattedAppointments = $appointments->getCollection()->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'service_type' => $appointment->service_type,
                    'booking_type' => $appointment->booking_type,
                    'status' => $appointment->status,
                    'duration_minutes' => $appointment->getDurationInMinutes(),
                    'scheduled_at' => $appointment->scheduled_at ? $appointment->scheduled_at->toISOString() : null,
                    'requested_at' => $appointment->requested_at ? $appointment->requested_at->toISOString() : null,
                    'accepted_at' => $appointment->accepted_at ? $appointment->accepted_at->toISOString() : null,
                    'started_at' => $appointment->started_at ? $appointment->started_at->toISOString() : null,
                    'ended_at' => $appointment->ended_at ? $appointment->ended_at->toISOString() : null,
                    'base_amount' => (float) $appointment->base_amount,
                    'final_amount' => (float) $appointment->final_amount,
                    'amount_paid' => (float) $appointment->amount_paid,
                    'payment_status' => $appointment->payment_status,
                    'user_notes' => $appointment->user_notes,
                    'astrologer_notes' => $appointment->astrologer_notes,
                    'rating' => $appointment->rating,
                    'review' => $appointment->review,
                    'user' => [
                        'id' => $appointment->user->id,
                        'name' => $appointment->user->name,
                        'phone' => $appointment->user->phone,
                        'profile_image' => $appointment->user->profile_image,
                    ],
                    'remaining_time' => $appointment->getRemainingTime(),
                    'session_duration' => $appointment->getSessionDuration()
                ];
            });

            $data = [
                'appointments' => $formattedAppointments,
                'pagination' => [
                    'current_page' => $appointments->currentPage(),
                    'last_page' => $appointments->lastPage(),
                    'per_page' => $appointments->perPage(),
                    'total' => $appointments->total()
                ]
            ];

            return $this->successResponse($data, 'Astrologer appointments retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error retrieving appointments: ' . $e->getMessage());
        }
    }

    /**
     * Get appointment details
     */
    public function show(Request $request, $appointmentId)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return $this->unauthorizedResponse('User not authenticated');
            }

            $appointment = Appointment::with(['user', 'astrologer.user', 'astrologer.skills.category'])
                ->find($appointmentId);

            if (!$appointment) {
                return $this->notFoundResponse('Appointment not found');
            }

            // Check authorization
            if ($appointment->user_id !== $user->id &&
                (!$user->astrologer || $appointment->astrologer_id !== $user->astrologer->id)) {
                return $this->forbiddenResponse('Not authorized to view this appointment');
            }

            $data = [
                'id' => $appointment->id,
                'service_type' => $appointment->service_type,
                'booking_type' => $appointment->booking_type,
                'status' => $appointment->status,
                'duration_minutes' => $appointment->getDurationInMinutes(),
                'scheduled_at' => $appointment->scheduled_at ? $appointment->scheduled_at->toISOString() : null,
                'requested_at' => $appointment->requested_at ? $appointment->requested_at->toISOString() : null,
                'accepted_at' => $appointment->accepted_at ? $appointment->accepted_at->toISOString() : null,
                'started_at' => $appointment->started_at ? $appointment->started_at->toISOString() : null,
                'ended_at' => $appointment->ended_at ? $appointment->ended_at->toISOString() : null,
                'base_amount' => (float) $appointment->base_amount,
                'final_amount' => (float) $appointment->final_amount,
                'amount_paid' => (float) $appointment->amount_paid,
                'payment_status' => $appointment->payment_status,
                'user_notes' => $appointment->user_notes,
                'astrologer_notes' => $appointment->astrologer_notes,
                'rating' => $appointment->rating,
                'review' => $appointment->review,
                'session_id' => $appointment->session_id,
                'session_meta' => $appointment->session_meta,
                'user' => [
                    'id' => $appointment->user->id,
                    'name' => $appointment->user->name,
                    'phone' => $appointment->user->phone,
                    'profile_image' => $appointment->user->profile_image,
                ],
                'astrologer' => $appointment->astrologer ? [
                    'id' => $appointment->astrologer->id,
                    'name' => $appointment->astrologer->user->name,
                    'specialization' => $appointment->astrologer->skills->first() ?
                        $appointment->astrologer->skills->first()->category->name : 'General',
                    'experience' => $appointment->astrologer->experience_years,
                    'rating' => $appointment->astrologer->total_rating,
                    'profile_image' => $appointment->astrologer->user->profile_image,
                ] : null,
                'remaining_time' => $appointment->getRemainingTime(),
                'session_duration' => $appointment->getSessionDuration()
            ];

            return $this->successResponse($data, 'Appointment details retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error retrieving appointment details: ' . $e->getMessage());
        }
    }

    /**
     * Rate and review appointment
     */
    public function rate(Request $request, $appointmentId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'rating' => 'required|integer|min:1|max:5',
                'review' => 'nullable|string|max:1000'
            ]);

            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors());
            }

            $user = $request->user();
            if (!$user) {
                return $this->unauthorizedResponse('User not authenticated');
            }

            $appointment = Appointment::find($appointmentId);
            if (!$appointment) {
                return $this->notFoundResponse('Appointment not found');
            }

            if ($appointment->user_id !== $user->id) {
                return $this->forbiddenResponse('Not authorized to rate this appointment');
            }

            if ($appointment->status !== 'completed') {
                return $this->errorResponse('Can only rate completed appointments');
            }

            if ($appointment->rating) {
                return $this->errorResponse('Appointment already rated');
            }

            $appointment->update([
                'rating' => $request->rating,
                'review' => $request->review
            ]);

            return $this->successResponse([
                'appointment_id' => $appointment->id,
                'rating' => $appointment->rating,
                'review' => $appointment->review
            ], 'Appointment rated successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error rating appointment: ' . $e->getMessage());
        }
    }
}
