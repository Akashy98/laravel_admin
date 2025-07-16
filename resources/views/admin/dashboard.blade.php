@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome to the admin panel')

@push('chartjs')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <div class="stats-icon bg-primary-gradient me-3">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <h3 class="mb-0">{{ $users }}</h3>
                    <p class="text-muted mb-0">Total Users</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <div class="stats-icon bg-info-gradient me-3">
                    <i class="fas fa-user-astronaut"></i>
                </div>
                <div>
                    <h3 class="mb-0">{{ $astrologers }}</h3>
                    <p class="text-muted mb-0">Total Astrologers</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <div class="stats-icon bg-success-gradient me-3">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div>
                    <h3 class="mb-0">{{ $admins }}</h3>
                    <p class="text-muted mb-0">Admin Users</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <div class="stats-icon bg-warning-gradient me-3">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div>
                    <h3 class="mb-0">0</h3>
                    <p class="text-muted mb-0">Active Sessions</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Activity Section -->
<div class="row">
    <!-- User Statistics Chart -->
    <div class="col-md-6 mb-4">
        <div class="stats-card h-100 d-flex flex-column">
            <h5 class="mb-3">User Statistics</h5>
            <div class="flex-grow-1 d-flex flex-column align-items-center justify-content-center" style="height: 300px;">
                <canvas id="userChart" style="max-width: 350px; width: 100%; height: 100%;"></canvas>
            </div>
            <div class="mt-3">
                <ul class="list-inline text-center mb-0">
                    <li class="list-inline-item mx-3">
                        <span style="display:inline-block;width:16px;height:16px;background:#4e73df;border-radius:3px;margin-right:6px;"></span>
                        Customers: <span class="fw-bold">{{ $users }}</span>
                    </li>
                    <li class="list-inline-item mx-3">
                        <span style="display:inline-block;width:16px;height:16px;background:#6f42c1;border-radius:3px;margin-right:6px;"></span>
                        Astrologers: <span class="fw-bold">{{ $astrologers }}</span>
                    </li>
                    <li class="list-inline-item mx-3">
                        <span style="display:inline-block;width:16px;height:16px;background:#1cc88a;border-radius:3px;margin-right:6px;"></span>
                        Admin Users: <span class="fw-bold">{{ $admins }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Activity Chart -->
    <div class="col-md-6 mb-4">
        <div class="stats-card h-100 d-flex flex-column">
            <h5 class="mb-3">User Activity</h5>
            <div class="flex-grow-1" style="height: 300px;">
                <canvas id="activityChart" style="width: 100%; height: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity and Quick Actions -->
<div class="row">
    <div class="col-md-8">
        <div class="stats-card">
            <h5 class="mb-3">Recent Activity</h5>
            <div id="recentActivity">
                <ul class="list-group list-group-flush">
                    @forelse($recent as $item)
                        @php
                            $typeMap = [
                                'User' => ['icon' => 'fa-user', 'color' => 'primary'],
                                'Astrologer' => ['icon' => 'fa-user-astronaut', 'color' => 'purple'],
                                'Banner' => ['icon' => 'fa-image', 'color' => 'warning'],
                                'Wallet Offer' => ['icon' => 'fa-wallet', 'color' => 'success'],
                                'Category' => ['icon' => 'fa-tags', 'color' => 'info'],
                                'Page' => ['icon' => 'fa-file-alt', 'color' => 'secondary'],
                                'Service' => ['icon' => 'fa-concierge-bell', 'color' => 'pink'],
                            ];
                            $type = $item->type;
                            $icon = $typeMap[$type]['icon'] ?? 'fa-question';
                            $color = $typeMap[$type]['color'] ?? 'secondary';
                            $badgeClass = $color === 'purple' ? 'bg-purple' : ($color === 'pink' ? 'bg-pink' : 'bg-' . $color);
                        @endphp
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <div>
                                <span class="badge {{ $badgeClass }} me-2" style="min-width: 2.5em;">
                                    <i class="fas {{ $icon }}"></i>
                                    {{ $type }}
                                </span>
                                <span>{{ $item->label }}</span>
                            </div>
                            <small class="text-muted">{{ $item->created_at->diffForHumans() }}</small>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted">No recent activity found.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stats-card">
            <h5 class="mb-3">Quick Actions</h5>
            <div class="d-grid gap-2">
                <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
                    <i class="fas fa-users me-2"></i>Manage Users
                </a>
                <button class="btn btn-outline-secondary" onclick="AdminCommon.Toast.info('Settings feature coming soon!')">
                    <i class="fas fa-cog me-2"></i>Settings
                </button>
                <button class="btn btn-outline-secondary" onclick="AdminJS.ExportFunctions.exportUsersCSV()">
                    <i class="fas fa-download me-2"></i>Export Data
                </button>
                <button class="btn btn-outline-info" onclick="AdminCommon.Toast.info('System is running smoothly!')">
                    <i class="fas fa-heartbeat me-2"></i>System Status
                </button>
            </div>
        </div>

        <!-- System Info -->
        <div class="stats-card mt-3">
            <h6 class="mb-3">System Information</h6>
            <div class="row text-center">
                <div class="col-6 mb-2">
                    <div class="text-muted small">PHP Version</div>
                    <div class="fw-bold">{{ phpversion() }}</div>
                </div>
                <div class="col-6 mb-2">
                    <div class="text-muted small">Laravel</div>
                    <div class="fw-bold">{{ app()->version() }}</div>
                </div>
                <div class="col-6 mb-2">
                    <div class="text-muted small">Environment</div>
                    <div class="fw-bold">{{ config('app.env') }}</div>
                </div>
                <div class="col-6 mb-2">
                    <div class="text-muted small">Debug Mode</div>
                    <div class="fw-bold">{{ config('app.debug') ? 'On' : 'Off' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// User statistics pie chart data
const userStats = {
    customers: {{ $users }},
    astrologers: {{ $astrologers }},
    admin: {{ $admins }}
};
const userLabels = ["Customers", "Astrologers", "Admin Users"];
const userColors = ["#4e73df", "#6f42c1", "#1cc88a"];

const userCtx = document.getElementById('userChart').getContext('2d');
new Chart(userCtx, {
    type: 'doughnut',
    data: {
        labels: userLabels,
        datasets: [{
            data: [userStats.customers, userStats.astrologers, userStats.admin],
            backgroundColor: userColors,
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize dashboard functionality
    if (typeof AdminJS !== 'undefined' && AdminJS.Dashboard) {
        AdminJS.Dashboard.init();
    }
});

// Add some interactive features
document.addEventListener('DOMContentLoaded', function() {
    // Add click handlers to stats cards
    const statsCards = document.querySelectorAll('.stats-card');
    statsCards.forEach(card => {
        card.addEventListener('click', function() {
            // Add a subtle animation
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });

    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + 1 for Dashboard
        if ((e.ctrlKey || e.metaKey) && e.key === '1') {
            e.preventDefault();
            window.location.href = '{{ route("admin.dashboard") }}';
        }

        // Ctrl/Cmd + 2 for Users
        if ((e.ctrlKey || e.metaKey) && e.key === '2') {
            e.preventDefault();
            window.location.href = '{{ route("admin.users.index") }}';
        }
    });
});

// Dynamic user activity chart data
const userActivity = @json($userActivity);
const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
const activityData = Array(12).fill(0);
Object.keys(userActivity).forEach(month => {
    activityData[parseInt(month) - 1] = userActivity[month];
});

const ctx = document.getElementById('activityChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: months,
        datasets: [{
            label: 'User Registrations',
            data: activityData,
            borderColor: '#4e73df',
            backgroundColor: 'rgba(78,115,223,0.1)',
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
@endpush

<style>
.bg-purple { background: #6f42c1 !important; color: #fff !important; }
.bg-pink { background: #e83e8c !important; color: #fff !important; }
</style>
