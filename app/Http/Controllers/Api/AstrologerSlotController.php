<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\Astrologer;
use App\Models\Appointment;
use App\Models\AppointmentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AstrologerSlotController extends BaseController
{
    /**
     * Get available slots for an astrologer
     */
    public function getAvailableSlots(Request $request, $astrologerId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'date' => 'required|date|after_or_equal:today',
                'service_type' => 'nullable|in:chat,call,video_call',
                'duration_minutes' => 'nullable|integer|in:10,15,20'
            ]);

            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors());
            }

            $astrologer = Astrologer::with(['user', 'skills.category'])->find($astrologerId);

            if (!$astrologer) {
                return $this->notFoundResponse('Astrologer not found');
            }

            if ($astrologer->status !== 'approved') {
                return $this->errorResponse('Astrologer is not available for bookings');
            }

            $date = Carbon::parse($request->date);
            $serviceType = $request->service_type;
            $duration = $request->duration_minutes ?? 15;

            // Check if astrologer provides the requested service
            if ($serviceType) {
                $hasService = $astrologer->services()->where('name', $serviceType)->exists();
                if (!$hasService) {
                    return $this->errorResponse('Astrologer does not provide this service');
                }
            }

            // Get astrologer's working hours (you can customize this)
            $workingHours = $this->getWorkingHours($astrologer, $date);

            if (empty($workingHours)) {
                return $this->successResponse([
                    'astrologer' => [
                        'id' => $astrologer->id,
                        'name' => $astrologer->user->name,
                        'specialization' => $astrologer->skills->first() ? $astrologer->skills->first()->category->name : 'General',
                        'rating' => $astrologer->total_rating,
                        'experience' => $astrologer->experience_years
                    ],
                    'date' => $date->format('Y-m-d'),
                    'slots' => [],
                    'message' => 'No slots available for this date'
                ], 'No slots available for this date');
            }

            // Generate time slots
            $slots = $this->generateTimeSlots($workingHours, $duration, $astrologerId, $date);

            return $this->successResponse([
                'astrologer' => [
                    'id' => $astrologer->id,
                    'name' => $astrologer->user->name,
                    'specialization' => $astrologer->skills->first() ? $astrologer->skills->first()->category->name : 'General',
                    'rating' => $astrologer->total_rating,
                    'experience' => $astrologer->experience_years,
                    'profile_image' => $astrologer->user->profile_image
                ],
                'date' => $date->format('Y-m-d'),
                'slots' => $slots,
                'duration_minutes' => $duration,
                'service_type' => $serviceType
            ], 'Available slots retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error retrieving slots: ' . $e->getMessage());
        }
    }

    /**
     * Get astrologer's availability for a date range
     */
    public function getAvailability(Request $request, $astrologerId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after_or_equal:start_date',
                'service_type' => 'nullable|in:chat,call,video_call'
            ]);

            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors());
            }

            $astrologer = Astrologer::with(['user', 'skills.category'])->find($astrologerId);

            if (!$astrologer) {
                return $this->notFoundResponse('Astrologer not found');
            }

            if ($astrologer->status !== 'approved') {
                return $this->errorResponse('Astrologer is not available for bookings');
            }

            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            $serviceType = $request->service_type;

            $availability = [];

            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $workingHours = $this->getWorkingHours($astrologer, $date);

                if (!empty($workingHours)) {
                    $availability[] = [
                        'date' => $date->format('Y-m-d'),
                        'day' => $date->format('l'),
                        'available' => true,
                        'working_hours' => $workingHours
                    ];
                } else {
                    $availability[] = [
                        'date' => $date->format('Y-m-d'),
                        'day' => $date->format('l'),
                        'available' => false,
                        'working_hours' => []
                    ];
                }
            }

            return $this->successResponse([
                'astrologer' => [
                    'id' => $astrologer->id,
                    'name' => $astrologer->user->name,
                    'specialization' => $astrologer->skills->first() ? $astrologer->skills->first()->category->name : 'General',
                    'rating' => $astrologer->total_rating,
                    'experience' => $astrologer->experience_years
                ],
                'availability' => $availability,
                'service_type' => $serviceType
            ], 'Availability retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error retrieving availability: ' . $e->getMessage());
        }
    }

    /**
     * Get astrologer's schedule for a specific date
     */
    public function getSchedule(Request $request, $astrologerId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'date' => 'required|date|after_or_equal:today'
            ]);

            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors());
            }

            $astrologer = Astrologer::with(['user'])->find($astrologerId);

            if (!$astrologer) {
                return $this->notFoundResponse('Astrologer not found');
            }

            $date = Carbon::parse($request->date);

            // Get appointments for this date
            $appointments = Appointment::where('astrologer_id', $astrologerId)
                ->whereDate('scheduled_at', $date)
                ->whereIn('status', ['pending', 'accepted', 'in_progress'])
                ->orderBy('scheduled_at')
                ->get();

            $schedule = [];
            foreach ($appointments as $appointment) {
                $schedule[] = [
                    'id' => $appointment->id,
                    'start_time' => $appointment->scheduled_at->format('H:i'),
                    'end_time' => $appointment->scheduled_at->addMinutes($appointment->duration_minutes)->format('H:i'),
                    'duration_minutes' => $appointment->duration_minutes,
                    'service_type' => $appointment->service_type,
                    'status' => $appointment->status,
                    'user' => [
                        'id' => $appointment->user->id,
                        'name' => $appointment->user->name,
                        'phone' => $appointment->user->phone
                    ]
                ];
            }

            return $this->successResponse([
                'astrologer' => [
                    'id' => $astrologer->id,
                    'name' => $astrologer->user->name
                ],
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('l'),
                'schedule' => $schedule,
                'total_appointments' => count($schedule)
            ], 'Schedule retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error retrieving schedule: ' . $e->getMessage());
        }
    }

    /**
     * Get working hours for an astrologer on a specific date
     */
    protected function getWorkingHours($astrologer, $date)
    {
        // This is a simplified version. You can extend this based on your requirements
        // For now, we'll use default working hours

        $dayOfWeek = strtolower($date->format('l'));

        // Default working hours (you can make this configurable)
        $defaultHours = [
            'monday' => ['09:00', '18:00'],
            'tuesday' => ['09:00', '18:00'],
            'wednesday' => ['09:00', '18:00'],
            'thursday' => ['09:00', '18:00'],
            'friday' => ['09:00', '18:00'],
            'saturday' => ['09:00', '16:00'],
            'sunday' => ['10:00', '14:00']
        ];

        if (!isset($defaultHours[$dayOfWeek])) {
            return [];
        }

        return [
            'start' => $defaultHours[$dayOfWeek][0],
            'end' => $defaultHours[$dayOfWeek][1],
            'break_start' => '12:00',
            'break_end' => '13:00'
        ];
    }

    /**
     * Generate time slots for a given date and duration
     */
    protected function generateTimeSlots($workingHours, $duration, $astrologerId, $date)
    {
        $slots = [];

        $startTime = Carbon::parse($date->format('Y-m-d') . ' ' . $workingHours['start']);
        $endTime = Carbon::parse($date->format('Y-m-d') . ' ' . $workingHours['end']);
        $breakStart = Carbon::parse($date->format('Y-m-d') . ' ' . $workingHours['break_start']);
        $breakEnd = Carbon::parse($date->format('Y-m-d') . ' ' . $workingHours['break_end']);

        $currentTime = $startTime->copy();

        while ($currentTime->addMinutes($duration)->lte($endTime)) {
            $slotStart = $currentTime->copy();
            $slotEnd = $currentTime->copy()->addMinutes($duration);

            // Skip break time
            if ($slotStart->lt($breakEnd) && $slotEnd->gt($breakStart)) {
                $currentTime = $breakEnd;
                continue;
            }

            // Check if slot is available (no conflicting appointments)
            $conflict = Appointment::where('astrologer_id', $astrologerId)
                ->whereDate('scheduled_at', $date)
                ->whereIn('status', ['pending', 'accepted', 'in_progress'])
                ->where(function ($query) use ($slotStart, $slotEnd) {
                    $query->whereBetween('scheduled_at', [$slotStart, $slotEnd])
                        ->orWhereBetween(DB::raw('DATE_ADD(scheduled_at, INTERVAL duration_minutes MINUTE)'), [$slotStart, $slotEnd]);
                })
                ->exists();

            if (!$conflict) {
                $slots[] = [
                    'start_time' => $slotStart->format('H:i'),
                    'end_time' => $slotEnd->format('H:i'),
                    'duration_minutes' => $duration,
                    'available' => true,
                    'formatted_time' => $slotStart->format('g:i A') . ' - ' . $slotEnd->format('g:i A')
                ];
            }

            $currentTime->addMinutes(30); // 30-minute intervals
        }

        return $slots;
    }

    /**
     * Get astrologer's pricing for different services
     */
    public function getPricing(Request $request, $astrologerId)
    {
        try {
            $astrologer = Astrologer::with(['user', 'pricing'])->find($astrologerId);

            if (!$astrologer) {
                return $this->notFoundResponse('Astrologer not found');
            }

            $pricing = AppointmentSetting::getServicePricing();
            $availableDurations = AppointmentSetting::getAvailableDurations();

            $astrologerPricing = [];
            foreach (['chat', 'call', 'video_call'] as $serviceType) {
                $servicePricing = $pricing[$serviceType] ?? $pricing['chat'];

                foreach ($availableDurations as $duration) {
                    $basePrice = $servicePricing['base_price'] + ($servicePricing['per_minute'] * $duration);

                    $astrologerPricing[] = [
                        'service_type' => $serviceType,
                        'duration_minutes' => $duration,
                        'base_price' => $basePrice,
                        'per_minute_rate' => $servicePricing['per_minute'],
                        'formatted_price' => '₹' . number_format($basePrice, 2)
                    ];
                }
            }

            return $this->successResponse([
                'astrologer' => [
                    'id' => $astrologer->id,
                    'name' => $astrologer->user->name,
                    'specialization' => $astrologer->skills->first() ? $astrologer->skills->first()->category->name : 'General',
                    'rating' => $astrologer->total_rating,
                    'experience' => $astrologer->experience_years
                ],
                'pricing' => $astrologerPricing,
                'available_durations' => $availableDurations
            ], 'Pricing retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error retrieving pricing: ' . $e->getMessage());
        }
    }
}
