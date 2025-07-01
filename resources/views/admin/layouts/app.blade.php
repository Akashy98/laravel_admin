<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- DataTables CSS (if needed) -->
    @stack('datatables-css')

    <!-- Admin CSS -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">

    <!-- Page specific styles -->
    @stack('styles')
</head>
<body>
    <button class="sidebar-toggle" onclick="AdminCommon.Sidebar.toggle()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar Component -->
    @include('admin.components.sidebar')

    <!-- Main Content -->
    <div class="main-content p-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>@yield('page-title', 'Dashboard')</h2>
                <p class="text-muted mb-0">@yield('page-subtitle', 'Welcome to the admin panel')</p>
            </div>
            <div class="d-flex align-items-center">
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

    <!-- Admin Common JS -->
    <script src="{{ asset('js/admin-common.js') }}"></script>

    <!-- Admin JS -->
    <script src="{{ asset('js/admin.js') }}"></script>

    <!-- Page specific scripts -->
    @stack('scripts')
</body>
</html>
