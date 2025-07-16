<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>@yield('title', 'Admin Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- DataTables CSS (if needed) -->
    @stack('datatables-css')

    <!-- Admin CSS -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">

    <!-- CKEditor Custom CSS -->
    <link href="{{ asset('css/ckeditor-custom.css') }}" rel="stylesheet">

    <!-- Page specific styles -->
    @stack('styles')
</head>
<body style="min-height: 100vh; display: flex; flex-direction: column;">
    <button class="sidebar-toggle" onclick="AdminCommon.Sidebar.toggle()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar Component -->
    @include('admin.components.sidebar')

    <!-- Main Content -->
    <div class="main-content p-4" style="flex: 1 0 auto;">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>@yield('page-title', 'Dashboard')</h2>
                <p class="text-muted mb-0">@yield('page-subtitle', 'Welcome to the admin panel')</p>
            </div>
            <div class="d-flex align-items-center">
                <!-- Theme Toggle Button -->
                <button id="theme-toggle" class="btn btn-outline-secondary me-3" title="Toggle Theme">
                    <i class="fas fa-sun" id="light-icon"></i>
                    <i class="fas fa-moon" id="dark-icon" style="display: none;"></i>
                </button>

                <span class="me-3">Welcome, {{ Auth::user()->name }}</span>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=667eea&color=fff"
                     alt="Avatar" class="rounded-circle" width="40">
            </div>
        </div>

        <!-- Breadcrumb Component -->
        @include('admin.components.breadcrumb')

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Copyright Footer -->
    <footer class="footer border-top bg-white" style="flex-shrink: 0; padding: 0.75rem 2rem; font-size: 0.95rem; margin-left: 250px;">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div class="text-muted mb-2 mb-md-0">
                &copy; {{ date('Y') }} Code Brew Labs. All rights reserved.
            </div>
            <div>
                <a href="#" class="text-muted text-decoration-none me-3">About Us</a>
                <a href="#" class="text-muted text-decoration-none me-3">Help</a>
                <a href="#" class="text-muted text-decoration-none">Contact Us</a>
            </div>
        </div>
    </footer>

    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- jQuery (for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS (if needed) -->
    @stack('datatables-js')

    <!-- Chart.js (if needed) -->
    @stack('chartjs')

    <!-- CKEditor CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>

    <!-- CKEditor Configuration -->
    <script src="{{ asset('js/ckeditor-config.js') }}"></script>

    <!-- Admin Common JS -->
    <script src="{{ asset('js/admin-common.js') }}"></script>

    <!-- Admin JS -->
    <script src="{{ asset('js/admin.js') }}"></script>

    <!-- Page specific scripts -->
    @stack('scripts')

    <!-- Prevent browser back button issues -->
    <script>
        // Prevent browser back button from going to login page when authenticated
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                // Page was loaded from back-forward cache
                window.location.reload();
            }
        });

        // Prevent form resubmission on page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>
</html>
