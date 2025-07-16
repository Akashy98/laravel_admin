<!-- Sidebar Component -->
<div class="sidebar p-3" id="sidebar">
    <div class="text-center mb-4">
        <h4><i class="fas fa-shield-alt me-2"></i>Astro India</h4>
        <small class="text-muted">v{{ config('app.version', '1.0.0') }}</small>
    </div>

    <!-- User Profile Section -->
    <div class="sidebar-user mb-4">
        <div class="d-flex align-items-center p-2 rounded" style="background: rgba(255, 255, 255, 0.1);">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=667eea&color=fff"
                 alt="Avatar" class="rounded-circle me-2" width="40">
            <div class="flex-grow-1">
                <div class="fw-bold">{{ Auth::user()->name }}</div>
                <small class="sidebar-gradient-text sidebar-admin-glow">{{ Auth::user()->isAdmin() ? 'Administrator' : 'User' }}</small>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="nav flex-column">
        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
        </a>
        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
            <i class="fas fa-user-friends me-2"></i>Customers
        </a>
        <a class="nav-link {{ request()->routeIs('admin.astrologers.*') ? 'active' : '' }}" href="{{ route('admin.astrologers.index') }}">
            <i class="fas fa-user-astronaut me-2"></i>Astrologers
        </a>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.categories.index') }}">
                <i class="fas fa-list-alt me-2"></i> Categories
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                <i class="fas fa-box me-2"></i> Products
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.wallet-offers.index') }}">
                <i class="fas fa-gift me-2"></i> Wallet Offers
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.banners.index') }}">
                <i class="fas fa-image me-2"></i> Banners
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.videos.*') ? 'active' : '' }}" href="{{ route('admin.videos.index') }}">
                <i class="fas fa-video me-2"></i> Videos
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.astrologer_reviews.*') ? 'active' : '' }}" href="{{ route('admin.astrologer_reviews.index') }}">
                <i class="fas fa-star me-2"></i> Reviews
            </a>
        </li>

        <!-- Appointments Section -->
        <div class="sidebar-section mt-3">
            <small class="sidebar-gradient-text text-uppercase px-3 mb-2 d-block">Appointments</small>
            <a class="nav-link {{ request()->routeIs('admin.appointments.index') ? 'active' : '' }}" href="{{ route('admin.appointments.index') }}">
                <i class="fas fa-calendar-check me-2"></i>All Appointments
            </a>
            <a class="nav-link {{ request()->routeIs('admin.appointments.statistics') ? 'active' : '' }}" href="{{ route('admin.appointments.statistics') }}">
                <i class="fas fa-chart-bar me-2"></i>Statistics
            </a>
            <a class="nav-link {{ request()->routeIs('admin.appointment-settings.*') ? 'active' : '' }}" href="{{ route('admin.appointment-settings.index') }}">
                <i class="fas fa-cog me-2"></i>Settings
            </a>
            <a class="nav-link" href="#" onclick="AdminCommon.Toast.info('Reports feature coming soon!')">
                <i class="fas fa-file-alt me-2"></i>Reports
            </a>
        </div>

        <!-- Settings Section -->
        <div class="sidebar-section mt-3">
            <small class="sidebar-gradient-text text-uppercase px-3 mb-2 d-block">Settings</small>
            <a class="nav-link" href="#" onclick="AdminCommon.Toast.info('Settings feature coming soon!')">
                <i class="fas fa-cog me-2"></i>General Settings
            </a>
            <a class="nav-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}" href="{{ route('admin.pages.index') }}">
                <i class="fas fa-file-alt me-2"></i>Pages
            </a>
            {{-- <a class="nav-link" href="#" onclick="AdminCommon.Toast.info('Profile feature coming soon!')">
                <i class="fas fa-user-cog me-2"></i>Profile
            </a> --}}
        </div>

        <!-- System Section -->
        <div class="sidebar-section mt-3">
            <small class="sidebar-gradient-text text-uppercase px-3 mb-2 d-block">System</small>
            {{-- <a class="nav-link" href="#" onclick="showSystemInfo()">
                <i class="fas fa-info-circle me-2"></i>System Info
            </a> --}}
            <a class="nav-link {{ request()->routeIs('logs') ? 'active' : '' }}" href="{{ route('logs') }}">
                <i class="fas fa-file-alt me-2"></i>Logs
            </a>
        </div>

        <!-- Divider -->
        <hr class="sidebar-gradient-divider my-3">

        <!-- Logout -->
        <a class="nav-link text-danger" href="#" onclick="confirmLogout()">
            <i class="fas fa-sign-out-alt me-2"></i>Logout
        </a>
    </nav>

    <!-- Sidebar Footer -->
    {{-- <div class="sidebar-footer mt-auto pt-3">
        <div class="text-center">
            <small class="text-white">
                <i class="fas fa-heart text-danger"></i> Code Brew
            </small>
        </div>
    </div> --}}
</div>

<!-- System Info Modal -->
<div class="modal fade" id="systemInfoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>System Information
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Application</h6>
                        <table class="table table-sm">
                            <tr><td>Laravel Version</td><td>{{ app()->version() }}</td></tr>
                            <tr><td>PHP Version</td><td>{{ phpversion() }}</td></tr>
                            <tr><td>Environment</td><td><span class="badge bg-{{ config('app.env') === 'production' ? 'success' : 'warning' }}">{{ config('app.env') }}</span></td></tr>
                            <tr><td>Debug Mode</td><td><span class="badge bg-{{ config('app.debug') ? 'danger' : 'success' }}">{{ config('app.debug') ? 'On' : 'Off' }}</span></td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">Server</h6>
                        <table class="table table-sm">
                            <tr><td>Server Software</td><td>{{ $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' }}</td></tr>
                            <tr><td>Database</td><td>{{ config('database.default') }}</td></tr>
                            <tr><td>Cache Driver</td><td>{{ config('cache.default') }}</td></tr>
                            <tr><td>Session Driver</td><td>{{ config('session.driver') }}</td></tr>
                        </table>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-primary">Memory Usage</h6>
                        <div class="progress mb-2">
                            <div class="progress-bar" role="progressbar" style="width: {{ (memory_get_usage(true) / memory_get_peak_usage(true)) * 100 }}%"></div>
                        </div>
                        <small class="text-muted">
                            {{ number_format(memory_get_usage(true) / 1024 / 1024, 2) }} MB /
                            {{ number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) }} MB
                        </small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="copySystemInfo()">
                    <i class="fas fa-copy me-2"></i>Copy Info
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Sidebar-specific JavaScript functions
function showSystemInfo() {
    AdminCommon.ModalHelper.show('systemInfoModal');
}

function confirmLogout() {
    if (confirm('Are you sure you want to logout?')) {
        document.getElementById('logout-form').submit();
    }
}

function copySystemInfo() {
    const info = `
System Information:
- Laravel Version: {{ app()->version() }}
- PHP Version: {{ phpversion() }}
- Environment: {{ config('app.env') }}
- Debug Mode: {{ config('app.debug') ? 'On' : 'Off' }}
- Server: {{ $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' }}
- Database: {{ config('database.default') }}
- Memory Usage: {{ number_format(memory_get_usage(true) / 1024 / 1024, 2) }} MB
    `;

    AdminCommon.Utils.copyToClipboard(info.trim())
        .then(() => {
            AdminCommon.Toast.success('System information copied to clipboard!');
        })
        .catch(() => {
            AdminCommon.Toast.error('Failed to copy system information');
        });
}

// Add keyboard shortcuts for sidebar navigation
document.addEventListener('keydown', function(e) {
    // Only if no input is focused
    if (document.activeElement.tagName === 'INPUT' || document.activeElement.tagName === 'TEXTAREA') {
        return;
    }

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

    // Ctrl/Cmd + 3 for Examples
    if ((e.ctrlKey || e.metaKey) && e.key === '3') {
        e.preventDefault();
        window.location.href = '{{ route("admin.example") }}';
    }

    // Ctrl/Cmd + L for Logout
    if ((e.ctrlKey || e.metaKey) && e.key === 'l') {
        e.preventDefault();
        confirmLogout();
    }
});
</script>
