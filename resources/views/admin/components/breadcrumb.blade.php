<!-- Breadcrumb Component -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">
                <i class="fas fa-home me-1"></i>Dashboard
            </a>
        </li>
        @if(isset($breadcrumbs))
            @foreach($breadcrumbs as $breadcrumb)
                @if($loop->last)
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ $breadcrumb['title'] }}
                    </li>
                @else
                    <li class="breadcrumb-item">
                        <a href="{{ $breadcrumb['url'] }}" class="text-decoration-none">
                            {{ $breadcrumb['title'] }}
                        </a>
                    </li>
                @endif
            @endforeach
        @else
            <li class="breadcrumb-item active" aria-current="page">
                @yield('page-title', 'Dashboard')
            </li>
        @endif
    </ol>
</nav>
