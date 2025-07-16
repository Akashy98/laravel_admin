@extends('admin.layouts.app')

@section('title', 'Appointment Settings')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-cog me-2"></i>Appointment Settings
        </h1>
        <div>
            <a href="{{ route('admin.appointment-settings.reset') }}" class="btn btn-warning me-2" onclick="return confirm('Are you sure you want to reset all settings to defaults?')">
                <i class="fas fa-undo me-2"></i>Reset to Defaults
            </a>
            <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Appointments
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.appointment-settings.update') }}" method="POST" id="settingsForm">
        @csrf
        @method('PUT')

        <!-- Booking Settings -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-calendar-check me-2"></i>Booking Settings
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($groupedSettings['booking'] ?? [] as $setting)
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="{{ $setting->key }}" class="form-label fw-bold">
                                    {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                                    @if($setting->description)
                                        <i class="fas fa-info-circle text-muted ms-1" title="{{ $setting->description }}"></i>
                                    @endif
                                </label>

                                @if($setting->type === 'boolean')
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="settings[{{ $setting->key }}][key]" value="{{ $setting->key }}">
                                        <input type="hidden" name="settings[{{ $setting->key }}][type]" value="{{ $setting->type }}">
                                        <input class="form-check-input" type="checkbox"
                                               name="settings[{{ $setting->key }}][value]"
                                               value="true"
                                               id="{{ $setting->key }}"
                                               {{ $setting->getValue() ? 'checked' : '' }}>
                                        <label class="form-check-label" for="{{ $setting->key }}">
                                            Enable {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                                        </label>
                                    </div>
                                @elseif($setting->type === 'integer')
                                    <input type="number" class="form-control"
                                           name="settings[{{ $setting->key }}][value]"
                                           value="{{ $setting->getValue() }}"
                                           id="{{ $setting->key }}">
                                    <input type="hidden" name="settings[{{ $setting->key }}][key]" value="{{ $setting->key }}">
                                    <input type="hidden" name="settings[{{ $setting->key }}][type]" value="{{ $setting->type }}">
                                @else
                                    <input type="text" class="form-control"
                                           name="settings[{{ $setting->key }}][value]"
                                           value="{{ $setting->getValue() }}"
                                           id="{{ $setting->key }}">
                                    <input type="hidden" name="settings[{{ $setting->key }}][key]" value="{{ $setting->key }}">
                                    <input type="hidden" name="settings[{{ $setting->key }}][type]" value="{{ $setting->type }}">
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Payment Settings -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-credit-card me-2"></i>Payment Settings
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($groupedSettings['payment'] ?? [] as $setting)
                        @if($setting->key === 'payment_timing')
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="{{ $setting->key }}" class="form-label fw-bold">
                                        Payment Timing
                                        <i class="fas fa-info-circle text-muted ms-1" title="When to charge payment"></i>
                                    </label>
                                    <select class="form-control" name="settings[{{ $setting->key }}][value]" id="{{ $setting->key }}">
                                        <option value="on_accept" {{ $setting->getValue() === 'on_accept' ? 'selected' : '' }}>On Accept</option>
                                        <option value="on_booking" {{ $setting->getValue() === 'on_booking' ? 'selected' : '' }}>On Booking</option>
                                        <option value="on_completion" {{ $setting->getValue() === 'on_completion' ? 'selected' : '' }}>On Completion</option>
                                    </select>
                                    <input type="hidden" name="settings[{{ $setting->key }}][key]" value="{{ $setting->key }}">
                                    <input type="hidden" name="settings[{{ $setting->key }}][type]" value="{{ $setting->type }}">
                                </div>
                            </div>
                        @elseif($setting->key === 'service_pricing')
                            <div class="col-12 mb-3">
                                <div class="form-group">
                                    <label class="form-label fw-bold">
                                        Service Pricing
                                        <i class="fas fa-info-circle text-muted ms-1" title="Pricing structure for different services"></i>
                                    </label>

                                    @php
                                        $pricing = $setting->getValue();
                                        $chatPrice = $pricing['chat']['base_price'] ?? 100;
                                        $chatPerMinute = $pricing['chat']['per_minute'] ?? 10;
                                        $callPrice = $pricing['call']['base_price'] ?? 150;
                                        $callPerMinute = $pricing['call']['per_minute'] ?? 15;
                                        $videoPrice = $pricing['video_call']['base_price'] ?? 200;
                                        $videoPerMinute = $pricing['video_call']['per_minute'] ?? 20;
                                    @endphp

                                    <div class="row">
                                        <div class="col-md-4">
                                            <h6 class="text-primary">Chat Service</h6>
                                            <div class="mb-2">
                                                <label class="form-label">Base Price (₹)</label>
                                                <input type="number" class="form-control" name="chat_base_price" value="{{ $chatPrice }}" min="0">
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">Per Minute (₹)</label>
                                                <input type="number" class="form-control" name="chat_per_minute" value="{{ $chatPerMinute }}" min="0">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <h6 class="text-primary">Call Service</h6>
                                            <div class="mb-2">
                                                <label class="form-label">Base Price (₹)</label>
                                                <input type="number" class="form-control" name="call_base_price" value="{{ $callPrice }}" min="0">
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">Per Minute (₹)</label>
                                                <input type="number" class="form-control" name="call_per_minute" value="{{ $callPerMinute }}" min="0">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <h6 class="text-primary">Video Call Service</h6>
                                            <div class="mb-2">
                                                <label class="form-label">Base Price (₹)</label>
                                                <input type="number" class="form-control" name="video_base_price" value="{{ $videoPrice }}" min="0">
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label">Per Minute (₹)</label>
                                                <input type="number" class="form-control" name="video_per_minute" value="{{ $videoPerMinute }}" min="0">
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="settings[{{ $setting->key }}][key]" value="{{ $setting->key }}">
                                    <input type="hidden" name="settings[{{ $setting->key }}][type]" value="{{ $setting->type }}">
                                    <input type="hidden" name="settings[{{ $setting->key }}][value]" id="service_pricing_json">
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Timing Settings -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-clock me-2"></i>Timing Settings
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($groupedSettings['timing'] ?? [] as $setting)
                        @if($setting->key === 'max_wait_time')
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="{{ $setting->key }}" class="form-label fw-bold">
                                        Maximum Wait Time (seconds)
                                        <i class="fas fa-info-circle text-muted ms-1" title="Maximum wait time before auto-cancellation"></i>
                                    </label>
                                    <input type="number" class="form-control"
                                           name="settings[{{ $setting->key }}][value]"
                                           value="{{ $setting->getValue() }}"
                                           id="{{ $setting->key }}" min="60" max="3600">
                                    <input type="hidden" name="settings[{{ $setting->key }}][key]" value="{{ $setting->key }}">
                                    <input type="hidden" name="settings[{{ $setting->key }}][type]" value="{{ $setting->type }}">
                                    <small class="form-text text-muted">Enter time in seconds (60-3600)</small>
                                </div>
                            </div>
                        @elseif($setting->key === 'available_durations')
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label fw-bold">
                                        Available Appointment Durations (minutes)
                                        <i class="fas fa-info-circle text-muted ms-1" title="Available appointment durations for users to choose"></i>
                                    </label>
                                    @php
                                        $durations = $setting->getValue();
                                    @endphp
                                    <div class="row">
                                        @foreach([10, 15, 20, 30, 45, 60] as $duration)
                                            <div class="col-md-4 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                           name="duration_{{ $duration }}"
                                                           value="{{ $duration }}"
                                                           id="duration_{{ $duration }}"
                                                           {{ in_array($duration, $durations) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="duration_{{ $duration }}">
                                                        {{ $duration }} minutes
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <input type="hidden" name="settings[{{ $setting->key }}][key]" value="{{ $setting->key }}">
                                    <input type="hidden" name="settings[{{ $setting->key }}][type]" value="{{ $setting->type }}">
                                    <input type="hidden" name="settings[{{ $setting->key }}][value]" id="available_durations_json">
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Discount Settings -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-percentage me-2"></i>Discount Settings
                </h6>
            </div>
            <div class="card-body">
                @foreach($groupedSettings['discounts'] ?? [] as $setting)
                    @php
                        $discounts = $setting->getValue();
                        $discountEnabled = $discounts['enabled'] ?? true;
                        $firstTimeDiscount = $discounts['first_time_discount'] ?? 20;
                        $bulkEnabled = $discounts['bulk_discount']['enabled'] ?? true;
                        $bulkThreshold = $discounts['bulk_discount']['threshold'] ?? 3;
                        $bulkDiscount = $discounts['bulk_discount']['discount'] ?? 10;
                    @endphp

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="form-label fw-bold">Enable Discounts</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox"
                                           name="discount_enabled"
                                           value="true"
                                           id="discount_enabled"
                                           {{ $discountEnabled ? 'checked' : '' }}>
                                    <label class="form-check-label" for="discount_enabled">
                                        Enable discount features
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="form-label fw-bold">First Time Discount (%)</label>
                                <input type="number" class="form-control"
                                       name="first_time_discount"
                                       value="{{ $firstTimeDiscount }}"
                                       min="0" max="100">
                                <small class="form-text text-muted">Discount percentage for first booking</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="form-label fw-bold">Enable Bulk Discount</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox"
                                           name="bulk_discount_enabled"
                                           value="true"
                                           id="bulk_discount_enabled"
                                           {{ $bulkEnabled ? 'checked' : '' }}>
                                    <label class="form-check-label" for="bulk_discount_enabled">
                                        Enable bulk booking discount
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="form-label fw-bold">Bulk Threshold</label>
                                <input type="number" class="form-control"
                                       name="bulk_threshold"
                                       value="{{ $bulkThreshold }}"
                                       min="2" max="10">
                                <small class="form-text text-muted">Minimum bookings for bulk discount</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="form-label fw-bold">Bulk Discount (%)</label>
                                <input type="number" class="form-control"
                                       name="bulk_discount"
                                       value="{{ $bulkDiscount }}"
                                       min="0" max="100">
                                <small class="form-text text-muted">Discount percentage for bulk bookings</small>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="settings[{{ $setting->key }}][key]" value="{{ $setting->key }}">
                    <input type="hidden" name="settings[{{ $setting->key }}][type]" value="{{ $setting->type }}">
                    <input type="hidden" name="settings[{{ $setting->key }}][value]" id="discount_settings_json">
                @endforeach
            </div>
        </div>

        <!-- Reminder Settings -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-bell me-2"></i>Reminder Settings
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($groupedSettings['reminders'] ?? [] as $setting)
                        @if($setting->key === 'auto_reminder_enabled')
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="{{ $setting->key }}" class="form-label fw-bold">
                                        Enable Auto Reminders
                                        <i class="fas fa-info-circle text-muted ms-1" title="Enable automatic appointment reminders"></i>
                                    </label>
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="settings[{{ $setting->key }}][key]" value="{{ $setting->key }}">
                                        <input type="hidden" name="settings[{{ $setting->key }}][type]" value="{{ $setting->type }}">
                                        <input class="form-check-input" type="checkbox"
                                               name="settings[{{ $setting->key }}][value]"
                                               value="true"
                                               id="{{ $setting->key }}"
                                               {{ $setting->getValue() ? 'checked' : '' }}>
                                        <label class="form-check-label" for="{{ $setting->key }}">
                                            Enable automatic reminders
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @elseif($setting->key === 'reminder_timing')
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label fw-bold">
                                        Reminder Timing (minutes before appointment)
                                        <i class="fas fa-info-circle text-muted ms-1" title="When to send reminders before appointment"></i>
                                    </label>
                                    @php
                                        $timings = $setting->getValue();
                                    @endphp
                                    <div class="row">
                                        @foreach([15, 30, 60, 120, 1440] as $minutes)
                                            <div class="col-md-4 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                           name="reminder_{{ $minutes }}"
                                                           value="{{ $minutes }}"
                                                           id="reminder_{{ $minutes }}"
                                                           {{ in_array($minutes, $timings) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="reminder_{{ $minutes }}">
                                                        @if($minutes >= 1440)
                                                            {{ $minutes/1440 }} day(s)
                                                        @elseif($minutes >= 60)
                                                            {{ $minutes/60 }} hour(s)
                                                        @else
                                                            {{ $minutes }} minute(s)
                                                        @endif
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <input type="hidden" name="settings[{{ $setting->key }}][key]" value="{{ $setting->key }}">
                                    <input type="hidden" name="settings[{{ $setting->key }}][type]" value="{{ $setting->type }}">
                                    <input type="hidden" name="settings[{{ $setting->key }}][value]" id="reminder_timing_json">
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- System Settings -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-cogs me-2"></i>System Settings
                </h6>
            </div>
            <div class="card-body">
                @foreach($groupedSettings['system'] ?? [] as $setting)
                    @if($setting->key === 'timezone_handling')
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="{{ $setting->key }}" class="form-label fw-bold">
                                    Timezone Handling
                                    <i class="fas fa-info-circle text-muted ms-1" title="How to handle timezones in the system"></i>
                                </label>
                                <select class="form-control" name="settings[{{ $setting->key }}][value]" id="{{ $setting->key }}">
                                    <option value="user_timezone" {{ $setting->getValue() === 'user_timezone' ? 'selected' : '' }}>User Timezone</option>
                                    <option value="astrologer_timezone" {{ $setting->getValue() === 'astrologer_timezone' ? 'selected' : '' }}>Astrologer Timezone</option>
                                    <option value="utc" {{ $setting->getValue() === 'utc' ? 'selected' : '' }}>UTC</option>
                                </select>
                                <input type="hidden" name="settings[{{ $setting->key }}][key]" value="{{ $setting->key }}">
                                <input type="hidden" name="settings[{{ $setting->key }}][type]" value="{{ $setting->type }}">
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Submit Button -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-body text-center">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Save Settings
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Convert form data to JSON before submission
document.getElementById('settingsForm').addEventListener('submit', function(e) {
    // Service Pricing JSON
    const servicePricing = {
        chat: {
            base_price: parseInt(document.querySelector('input[name="chat_base_price"]').value) || 100,
            per_minute: parseInt(document.querySelector('input[name="chat_per_minute"]').value) || 10
        },
        call: {
            base_price: parseInt(document.querySelector('input[name="call_base_price"]').value) || 150,
            per_minute: parseInt(document.querySelector('input[name="call_per_minute"]').value) || 15
        },
        video_call: {
            base_price: parseInt(document.querySelector('input[name="video_base_price"]').value) || 200,
            per_minute: parseInt(document.querySelector('input[name="video_per_minute"]').value) || 20
        }
    };
    document.getElementById('service_pricing_json').value = JSON.stringify(servicePricing);

    // Available Durations JSON
    const durations = [];
    document.querySelectorAll('input[name^="duration_"]:checked').forEach(checkbox => {
        durations.push(parseInt(checkbox.value));
    });
    document.getElementById('available_durations_json').value = JSON.stringify(durations);

    // Discount Settings JSON
    const discountSettings = {
        enabled: document.querySelector('input[name="discount_enabled"]').checked,
        first_time_discount: parseInt(document.querySelector('input[name="first_time_discount"]').value) || 20,
        bulk_discount: {
            enabled: document.querySelector('input[name="bulk_discount_enabled"]').checked,
            threshold: parseInt(document.querySelector('input[name="bulk_threshold"]').value) || 3,
            discount: parseInt(document.querySelector('input[name="bulk_discount"]').value) || 10
        }
    };
    document.getElementById('discount_settings_json').value = JSON.stringify(discountSettings);

    // Reminder Timing JSON
    const reminderTimings = [];
    document.querySelectorAll('input[name^="reminder_"]:checked').forEach(checkbox => {
        reminderTimings.push(parseInt(checkbox.value));
    });
    document.getElementById('reminder_timing_json').value = JSON.stringify(reminderTimings);
});
</script>
@endsection
