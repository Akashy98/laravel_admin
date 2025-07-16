@extends('admin.layouts.app')

@section('title', 'Appointment Details')

@push('styles')
<style>
    .details-card {
        border-radius: 1rem;
        box-shadow: 0 2px 16px rgba(0,0,0,0.07);
        background: #fff;
        margin-bottom: 2rem;
    }
    .details-header {
        background: linear-gradient(90deg, #4e73df 0%, #36b9cc 100%);
        color: #fff;
        border-radius: 1rem 1rem 0 0;
        padding: 1.5rem 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .details-header .icon {
        font-size: 2.5rem;
        opacity: 0.8;
    }
    .details-section {
        padding: 2rem;
    }
    .details-label {
        font-weight: 600;
        color: #4e73df;
        font-size: 1rem;
    }
    .details-value {
        font-size: 1.1rem;
        color: #343a40;
    }
    .details-row {
        margin-bottom: 1.25rem;
    }
    .avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #36b9cc;
        margin-right: 1rem;
    }
    .back-btn {
        margin-top: 2rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="details-card">
        <div class="details-header">
            <span class="icon"><i class="fas fa-calendar-check"></i></span>
            <div>
                <h2 class="mb-0">Appointment #{{ $appointment->id }}</h2>
                <small>{{ $appointment->created_at->format('M d, Y h:i A') }}</small>
            </div>
            <span class="ms-auto badge bg-{{ $appointment->status === 'completed' ? 'success' : ($appointment->status === 'cancelled' ? 'danger' : 'primary') }} fs-6">{{ ucfirst(str_replace('_', ' ', $appointment->status)) }}</span>
        </div>
        <div class="row details-section">
            <!-- User Info -->
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <img src="{{ $appointment->user && $appointment->user->profile_image ? asset('storage/' . $appointment->user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($appointment->user ? $appointment->user->name : 'User') }}" class="avatar me-3" alt="User Avatar">
                        <div>
                            <div class="details-label">User</div>
                            <div class="details-value">{{ $appointment->user ? $appointment->user->name : 'N/A' }}</div>
                            <div class="text-muted small"><i class="fas fa-phone-alt me-1"></i>{{ $appointment->user ? $appointment->user->phone : 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Astrologer Info -->
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <img src="{{ $appointment->astrologer && $appointment->astrologer->user && $appointment->astrologer->user->profile_image ? asset('storage/' . $appointment->astrologer->user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($appointment->astrologer && $appointment->astrologer->user ? $appointment->astrologer->user->name : 'Astrologer') }}" class="avatar me-3" alt="Astrologer Avatar">
                        <div>
                            <div class="details-label">Astrologer</div>
                            <div class="details-value">{{ $appointment->astrologer && $appointment->astrologer->user ? $appointment->astrologer->user->name : 'N/A' }}</div>
                            <div class="text-muted small"><i class="fas fa-star text-warning me-1"></i>{{ $appointment->astrologer ? ($appointment->astrologer->total_rating ?? 'N/A') : 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Service Info -->
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="details-label mb-1">Service Type</div>
                        <div class="details-value mb-2"><i class="fas fa-{{ $appointment->service_type === 'chat' ? 'comments' : ($appointment->service_type === 'call' ? 'phone' : 'video') }} me-1"></i>{{ ucfirst(str_replace('_', ' ', $appointment->service_type)) }}</div>
                        <div class="details-label mb-1">Booking Type</div>
                        <div class="details-value mb-2"><span class="badge bg-info">{{ ucfirst($appointment->booking_type) }}</span></div>
                        <div class="details-label mb-1">Duration</div>
                        <div class="details-value">{{ $appointment->duration_minutes }} min</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row details-section">
            <!-- Session Info -->
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="details-label mb-1"><i class="fas fa-clock me-1"></i>Session Timing</div>
                        <div class="details-value mb-2">
                            <span class="me-3"><strong>Scheduled:</strong> {{ $appointment->scheduled_at ? $appointment->scheduled_at->format('M d, Y h:i A') : 'N/A' }}</span><br>
                            <span class="me-3"><strong>Requested:</strong> {{ $appointment->requested_at ? $appointment->requested_at->format('M d, Y h:i A') : 'N/A' }}</span><br>
                            <span class="me-3"><strong>Started:</strong> {{ $appointment->started_at ? $appointment->started_at->format('M d, Y h:i A') : 'N/A' }}</span><br>
                            <span class="me-3"><strong>Ended:</strong> {{ $appointment->ended_at ? $appointment->ended_at->format('M d, Y h:i A') : 'N/A' }}</span>
                        </div>
                        <div class="details-label mb-1">Session ID</div>
                        <div class="details-value">{{ $appointment->session_id ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
            <!-- Payment Info -->
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="details-label mb-1"><i class="fas fa-wallet me-1"></i>Payment</div>
                        <div class="details-value mb-2">
                            <span class="me-3"><strong>Status:</strong> <span class="badge bg-{{ $appointment->payment_status === 'paid' ? 'success' : ($appointment->payment_status === 'pending' ? 'warning' : 'secondary') }}">{{ ucfirst($appointment->payment_status) }}</span></span><br>
                            <span class="me-3"><strong>Base:</strong> ₹{{ number_format($appointment->base_amount, 2) }}</span><br>
                            <span class="me-3"><strong>Final:</strong> ₹{{ number_format($appointment->final_amount, 2) }}</span><br>
                            <span class="me-3"><strong>Paid:</strong> ₹{{ number_format($appointment->amount_paid, 2) }}</span>
                        </div>
                        <div class="details-label mb-1">Rating</div>
                        <div class="details-value">
                            @if($appointment->rating)
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $appointment->rating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                                <span class="ms-2">{{ $appointment->rating }}/5</span>
                            @else
                                <span class="text-muted">Not rated</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row details-section">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="details-label mb-1"><i class="fas fa-align-left me-1"></i>Notes & Review</div>
                        <div class="details-value mb-2">
                            <strong>User Notes:</strong> {{ $appointment->user_notes ?? 'N/A' }}<br>
                            <strong>Astrologer Notes:</strong> {{ $appointment->astrologer_notes ?? 'N/A' }}<br>
                            <strong>Review:</strong> {{ $appointment->review ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-end back-btn">
            <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline-primary"><i class="fas fa-arrow-left me-1"></i>Back to List</a>
        </div>
    </div>
</div>
@endsection
