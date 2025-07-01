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

    <div class="col-md-6 col-lg-3 mb-3">
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <div class="stats-icon bg-info-gradient me-3">
                    <i class="fas fa-database"></i>
                </div>
                <div>
                    <h3 class="mb-0">0</h3>
                    <p class="text-muted mb-0">Total Records</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Activity Section -->
<div class="row">
    <!-- User Statistics Chart -->
    <div class="col-md-6 mb-4">
        <div class="stats-card">
            <h5 class="mb-3">User Statistics</h5>
            <div style="height: 300px;">
                <canvas id="userChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Activity Chart -->
    <div class="col-md-6 mb-4">
        <div class="stats-card">
            <h5 class="mb-3">User Activity</h5>
            <div style="height: 300px;">
                <canvas id="activityChart"></canvas>
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
                <div class="text-center text-muted py-4">
                    <i class="fas fa-spinner fa-spin me-2"></i>Loading recent activity...
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stats-card">
            <h5 class="mb-3">Quick Actions</h5>
            <div class="d-grid gap-2">
                <a href="{{ route('admin.users') }}" class="btn btn-primary">
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
// Pass user stats to JavaScript
window.userStats = {
    regular: {{ $users }},
    admin: {{ $admins }}
};

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
            window.location.href = '{{ route("admin.users") }}';
        }
    });
});
</script>
@endpush
