<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Appointment extends Model
{

    protected $fillable = [
        'user_id',
        'astrologer_id',
        'original_astrologer_id',
        'service_type',
        'booking_type',
        'scheduled_at',
        'duration_minutes',
        'requested_at',
        'accepted_at',
        'started_at',
        'ended_at',
        'status',
        'base_amount',
        'final_amount',
        'amount_paid',
        'payment_status',
        'payment_timing',
        'is_broadcast',
        'max_wait_time',
        'cancellation_reason',
        'cancelled_by',
        'user_notes',
        'astrologer_notes',
        'rating',
        'review',
        'session_id',
        'session_meta'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'requested_at' => 'datetime',
        'accepted_at' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'session_meta' => 'array',
        'is_broadcast' => 'boolean',
        'base_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2'
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function astrologer(): BelongsTo
    {
        return $this->belongsTo(Astrologer::class);
    }

    public function originalAstrologer(): BelongsTo
    {
        return $this->belongsTo(Astrologer::class, 'original_astrologer_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeInstant($query)
    {
        return $query->where('booking_type', 'instant');
    }

    public function scopeScheduled($query)
    {
        return $query->where('booking_type', 'scheduled');
    }

    public function scopeByServiceType($query, $serviceType)
    {
        return $query->where('service_type', $serviceType);
    }

    public function scopeForAstrologer($query, $astrologerId)
    {
        return $query->where('astrologer_id', $astrologerId);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeAvailableForBroadcast($query)
    {
        return $query->where('is_broadcast', true)
                    ->where('status', 'pending')
                    ->whereNull('astrologer_id');
    }

    // Helper methods
    public function isInstant(): bool
    {
        return $this->booking_type === 'instant';
    }

    public function isScheduled(): bool
    {
        return $this->booking_type === 'scheduled';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired';
    }

    public function canBeAccepted(): bool
    {
        return $this->isPending() && !$this->isExpired();
    }

    public function canBeStarted(): bool
    {
        return $this->isAccepted() && !$this->started_at;
    }

    public function canBeCompleted(): bool
    {
        return $this->isInProgress() && $this->started_at;
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'accepted', 'in_progress']);
    }

    public function getDurationInMinutes(): int
    {
        return $this->duration_minutes ?? 15;
    }

    public function getRemainingTime(): ?int
    {
        if (!$this->started_at) {
            return null;
        }

        $endTime = $this->started_at->addMinutes($this->getDurationInMinutes());
        $remaining = now()->diffInSeconds($endTime, false);

        return $remaining > 0 ? $remaining : 0;
    }

    public function getSessionDuration(): ?int
    {
        if (!$this->started_at || !$this->ended_at) {
            return null;
        }

        return $this->started_at->diffInSeconds($this->ended_at);
    }

    public function isPaymentOnRequest(): bool
    {
        return $this->payment_timing === 'on_request';
    }

    public function isPaymentOnAccept(): bool
    {
        return $this->payment_timing === 'on_accept';
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isPaymentPending(): bool
    {
        return $this->payment_status === 'pending';
    }

    public function getAmountToPay(): float
    {
        return (float) $this->final_amount - (float) $this->amount_paid;
    }

    public function getDiscountAmount(): float
    {
        return (float) $this->base_amount - (float) $this->final_amount;
    }

    public function getDiscountPercentage(): float
    {
        if ($this->base_amount <= 0) {
            return 0;
        }

        return round((($this->base_amount - $this->final_amount) / $this->base_amount) * 100, 2);
    }

    // Status update methods
    public function markAsAccepted(): bool
    {
        return $this->update([
            'status' => 'accepted',
            'accepted_at' => now()
        ]);
    }

    public function markAsInProgress(): bool
    {
        return $this->update([
            'status' => 'in_progress',
            'started_at' => now()
        ]);
    }

    public function markAsCompleted(): bool
    {
        return $this->update([
            'status' => 'completed',
            'ended_at' => now()
        ]);
    }

    public function markAsCancelled(string $reason = null, string $cancelledBy = 'user'): bool
    {
        return $this->update([
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
            'cancelled_by' => $cancelledBy
        ]);
    }

    public function markAsExpired(): bool
    {
        return $this->update([
            'status' => 'expired'
        ]);
    }

    public function assignAstrologer(int $astrologerId): bool
    {
        return $this->update([
            'astrologer_id' => $astrologerId
        ]);
    }

    public function updatePaymentStatus(string $status): bool
    {
        return $this->update([
            'payment_status' => $status
        ]);
    }

    public function addPaymentAmount(float $amount): bool
    {
        return $this->update([
            'amount_paid' => $this->amount_paid + $amount
        ]);
    }

    // Static methods
    public static function getAvailableDurations(): array
    {
        return [10, 15, 20];
    }

    public static function getServiceTypes(): array
    {
        return ['chat', 'call', 'video_call'];
    }

    public static function getBookingTypes(): array
    {
        return ['instant', 'scheduled'];
    }

    public static function getStatuses(): array
    {
        return [
            'pending',
            'accepted',
            'in_progress',
            'completed',
            'cancelled',
            'expired',
            'no_astrologer'
        ];
    }

    public static function getPaymentStatuses(): array
    {
        return ['pending', 'paid', 'refunded'];
    }

    public static function getPaymentTimings(): array
    {
        return ['on_request', 'on_accept'];
    }
}
