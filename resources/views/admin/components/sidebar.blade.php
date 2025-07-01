<!-- Sidebar Component -->
<div class="sidebar p-3" id="sidebar">
    <div class="text-center mb-4">
        <h4><i class="fas fa-shield-alt me-2"></i>Admin Panel</h4>
        <small class="text-muted">v{{ config('app.version', '1.0.0') }}</small>
    </div>

    <!-- User Profile Section -->
    <div class="sidebar-user mb-4">
        <div class="d-flex align-items-center p-2 rounded" style="background: rgba(255, 255, 255, 0.1);">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=667eea&color=fff"
                 alt="Avatar" class="rounded-circle me-2" width="40">
            <div class="flex-grow-1">
                <div class="fw-bold">{{ Auth::user()->name }}</div>
                <small class="text-muted">{{ Auth::user()->is_admin ? 'Administrator' : 'User' }}</small>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="nav flex-column">
        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
        </a>
        <a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="{{ route('admin.users') }}">
            <i class="fas fa-users me-2"></i>Users
        </a>
        <a class="nav-link {{ request()->routeIs('admin.example') ? 'active' : '' }}" href="{{ route('admin.example') }}">
            <i class="fas fa-code me-2"></i>Examples
        </a>

        <!-- Settings Section -->
        <div class="sidebar-section mt-3">
            <small class="text-muted text-uppercase px-3 mb-2 d-block">Settings</small>
            <a class="nav-link" href="#" onclick="AdminCommon.Toast.info('Settings feature coming soon!')">
                <i class="fas fa-cog me-2"></i>General Settings
            </a>
            <a class="nav-link" href="#" onclick="AdminCommon.Toast.info('Profile feature coming soon!')">
                <i class="fas fa-user-cog me-2"></i>Profile
            </a>
        </div>

        <!-- System Section -->
        <div class="sidebar-section mt-3">
            <small class="text-muted text-uppercase px-3 mb-2 d-block">System</small>
            <a class="nav-link" href="#" onclick="showSystemInfo()">
                <i class="fas fa-info-circle me-2"></i>System Info
            </a>
            <a class="nav-link" href="#" onclick="AdminCommon.Toast.info('Logs feature coming soon!')">
                <i class="fas fa-file-alt me-2"></i>Logs
            </a>
        </div>

        <!-- Divider -->
        <hr class="my-3" style="border-color: rgba(255, 255, 255, 0.1);">

        <!-- Logout -->
        <a class="nav-link text-danger" href="#" onclick="confirmLogout()">
            <i class="fas fa-sign-out-alt me-2"></i>Logout
        </a>
    </nav>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer mt-auto pt-3">
        <div class="text-center">
            <small class="text-muted">
                <i class="fas fa-heart text-danger"></i> Made with Laravel
            </small>
        </div>
    </div>
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
        window.location.href = '{{ route("admin.users") }}';
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
