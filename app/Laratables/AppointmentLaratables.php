<?php

namespace App\Laratables;

use App\Models\Appointment;

class AppointmentLaratables
{
    public static function laratablesQueryConditions($query)
    {
        $query = $query->select([
            'id', 'session_id', 'service_type', 'booking_type', 'status',
            'payment_status', 'scheduled_at', 'created_at', 'user_id',
            'astrologer_id', 'original_astrologer_id', 'is_fake', 'base_amount',
            'final_amount', 'amount_paid', 'duration_minutes', 'rating'
        ])
        ->with(['user', 'astrologer.user', 'originalAstrologer.user', 'astrologer.skills.category', 'originalAstrologer.skills.category'])
        ->orderBy('created_at', 'desc');

        // Filter by status if provided
        $status = request('status', 'all');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        return $query;
    }

    public static function laratablesSearchableColumns()
    {
        return ['session_id', 'service_type', 'booking_type', 'status', 'payment_status'];
    }

    public static function laratablesOrderColumns()
    {
        return ['id', 'session_id', 'service_type', 'booking_type', 'status', 'payment_status', 'scheduled_at'];
    }

    public static function laratablesColumns()
    {
        return ['id', 'user_info', 'astrologer_info', 'fake_indicator', 'service_type', 'booking_type', 'status', 'date_time', 'pricing', 'payment_status', 'rating', 'created_at', 'actions'];
    }

    public static function laratablesRawColumns()
    {
        return ['id', 'user_info', 'astrologer_info', 'fake_indicator', 'service_type', 'booking_type', 'status', 'date_time', 'pricing', 'payment_status', 'rating', 'created_at', 'actions'];
    }

    public static function laratablesCustomUserInfo($appointment)
    {
        if (!$appointment->user) {
            return '<span class="text-muted">User not found</span>';
        }

        $user = $appointment->user;
        $avatar = $user->profile_image ?
            '<img src="' . asset('storage/' . $user->profile_image) . '" class="rounded-circle me-2" width="30" height="30">' :
            '<div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                <i class="fas fa-user text-white"></i>
            </div>';

        return $avatar . '<div class="d-inline-block">
            <div class="fw-bold">' . $user->name . '</div>
            <small class="text-muted">' . $user->phone . '</small>
        </div>';
    }

    public static function laratablesCustomAstrologerInfo($appointment)
    {
        $astrologer = $appointment->astrologer;
        $originalAstrologer = $appointment->originalAstrologer;

        if (!$astrologer && !$originalAstrologer) {
            return '<span class="text-muted">Not assigned</span>';
        }

        $displayAstrologer = $astrologer ?: $originalAstrologer;
        $isFake = $appointment->is_fake;

        $avatar = $displayAstrologer->user->profile_image ?
            '<img src="' . asset('storage/' . $displayAstrologer->user->profile_image) . '" class="rounded-circle me-2" width="30" height="30">' :
            '<div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                <i class="fas fa-user text-white"></i>
            </div>';

        $fakeBadge = $isFake ? '<span class="badge badge-warning ms-1">Fake</span>' : '';

        $html = $avatar . '<div class="d-inline-block">
            <div class="fw-bold">' . $displayAstrologer->user->name . $fakeBadge . '</div>
            <small class="text-muted">' . ($displayAstrologer->skills->first() ? $displayAstrologer->skills->first()->category->name : 'General') . '</small>';

        // Show both astrologers if it's a fake appointment
        if ($isFake && $originalAstrologer) {
            $html .= '<br><small class="text-info"><i class="fas fa-info-circle me-1"></i>Original: ' . $originalAstrologer->user->name . '</small>';
        }

        $html .= '</div>';

        return $html;
    }

    public static function laratablesCustomServiceType($appointment)
    {
        $serviceIcons = [
            'chat' => 'fas fa-comments',
            'call' => 'fas fa-phone',
            'video_call' => 'fas fa-video'
        ];

        $serviceLabels = [
            'chat' => 'Chat',
            'call' => 'Call',
            'video_call' => 'Video Call'
        ];

        $icon = $serviceIcons[$appointment->service_type] ?? 'fas fa-question';
        $label = $serviceLabels[$appointment->service_type] ?? ucfirst($appointment->service_type);

        return '<i class="' . $icon . ' me-2"></i>' . $label;
    }

    public static function laratablesCustomBookingType($appointment)
    {
        $typeColors = [
            'instant' => 'success',
            'scheduled' => 'info'
        ];

        $typeLabels = [
            'instant' => 'Instant',
            'scheduled' => 'Scheduled'
        ];

        $color = $typeColors[$appointment->booking_type] ?? 'secondary';
        $label = $typeLabels[$appointment->booking_type] ?? ucfirst($appointment->booking_type);

        return '<span class="badge badge-' . $color . '">' . $label . '</span>';
    }

    public static function laratablesCustomStatus($appointment)
    {
        $statusColors = [
            'pending' => 'warning',
            'accepted' => 'info',
            'in_progress' => 'primary',
            'completed' => 'success',
            'cancelled' => 'danger',
            'expired' => 'secondary',
            'no_astrologer' => 'dark'
        ];

        $statusLabels = [
            'pending' => 'Awaiting Response',
            'accepted' => 'Confirmed',
            'in_progress' => 'In Session',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            'expired' => 'Expired',
            'no_astrologer' => 'No Astrologer'
        ];

        $color = $statusColors[$appointment->status] ?? 'secondary';
        $label = $statusLabels[$appointment->status] ?? ucfirst($appointment->status);

        return '<span class="badge badge-' . $color . '">' . $label . '</span>';
    }

    public static function laratablesCustomDateTime($appointment)
    {
        $dateTime = $appointment->scheduled_at ?: $appointment->requested_at;

        if (!$dateTime) {
            return '<span class="text-muted">N/A</span>';
        }

        $date = $dateTime->format('M d, Y');
        $time = $dateTime->format('h:i A');
        $duration = $appointment->duration_minutes . ' min';

        return '<div>
            <div class="fw-bold">' . $date . '</div>
            <small class="text-muted">' . $time . ' (' . $duration . ')</small>
        </div>';
    }

    public static function laratablesCustomPricing($appointment)
    {
        $baseAmount = '₹' . number_format($appointment->base_amount, 2);
        $finalAmount = '₹' . number_format($appointment->final_amount, 2);
        $paidAmount = '₹' . number_format($appointment->amount_paid, 2);

        $discount = $appointment->base_amount > $appointment->final_amount;
        $discountBadge = $discount ? '<span class="badge badge-success ms-1">-' . $appointment->getDiscountPercentage() . '%</span>' : '';

        return '<div>
            <div class="fw-bold">' . $finalAmount . $discountBadge . '</div>
            <small class="text-muted">Paid: ' . $paidAmount . '</small>
        </div>';
    }

    public static function laratablesCustomPaymentStatus($appointment)
    {
        $statusColors = [
            'pending' => 'warning',
            'paid' => 'success',
            'refunded' => 'info'
        ];

        $statusIcons = [
            'pending' => 'fas fa-clock',
            'paid' => 'fas fa-check-circle',
            'refunded' => 'fas fa-undo'
        ];

        $color = $statusColors[$appointment->payment_status] ?? 'secondary';
        $icon = $statusIcons[$appointment->payment_status] ?? 'fas fa-question';

        return '<span class="badge badge-' . $color . '">
            <i class="' . $icon . ' me-1"></i>' . ucfirst($appointment->payment_status) . '
        </span>';
    }

    public static function laratablesCustomSessionInfo($appointment)
    {
        if ($appointment->status === 'in_progress' && $appointment->started_at) {
            $remainingTime = $appointment->getRemainingTime();
            if ($remainingTime !== null) {
                $minutes = floor($remainingTime / 60);
                $seconds = $remainingTime % 60;
                return '<div class="text-primary">
                    <i class="fas fa-clock me-1"></i>
                    <span class="fw-bold">' . sprintf('%02d:%02d', $minutes, $seconds) . '</span>
                    <small class="text-muted">remaining</small>
                </div>';
            }
        }

        if ($appointment->status === 'completed' && $appointment->session_duration) {
            $duration = $appointment->getSessionDuration();
            $minutes = floor($duration / 60);
            $seconds = $duration % 60;
            return '<div class="text-success">
                <i class="fas fa-check-circle me-1"></i>
                <span class="fw-bold">' . sprintf('%02d:%02d', $minutes, $seconds) . '</span>
                <small class="text-muted">completed</small>
            </div>';
        }

        return '<span class="text-muted">-</span>';
    }

    public static function laratablesCustomRating($appointment)
    {
        if (!$appointment->rating) {
            return '<span class="text-muted">Not rated</span>';
        }

        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            $color = $i <= $appointment->rating ? 'text-warning' : 'text-muted';
            $stars .= '<i class="fas fa-star ' . $color . '"></i>';
        }

        return '<div>
            <div>' . $stars . '</div>
            <small class="text-muted">' . $appointment->rating . '/5</small>
        </div>';
    }

    public static function laratablesCustomCreatedAt($appointment)
    {
        return '<div>
            <div class="fw-bold">' . $appointment->created_at->format('M d, Y') . '</div>
            <small class="text-muted">' . $appointment->created_at->format('h:i A') . '</small>
        </div>';
    }

    public static function laratablesCustomActions($appointment)
    {
        return view('admin.appointments.actions', compact('appointment'))->render();
    }

    public static function laratablesCustomFakeIndicator($appointment)
    {
        if ($appointment->is_fake) {
            return '<span class="badge badge-warning"><i class="fas fa-exclamation-triangle me-1"></i>Fake Astrologer</span>';
        }

        return '<span class="badge badge-success"><i class="fas fa-check me-1"></i>Original</span>';
    }
}
