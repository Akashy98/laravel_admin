@extends('admin.layouts.app')

@section('content')
<div>
    <div class="mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.astrologers.index') }}">Astrologers</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $astrologer->user->name ?? '-' }}</li>
            </ol>
        </nav>
    </div>
    <div class="card mb-4">
        <div class="card-body d-flex align-items-center justify-content-between">
            <div>
                <h3 class="mb-0">{{ $astrologer->user->name ?? '-' }}</h3>
                <div class="text-muted">{{ $astrologer->user->email ?? '-' }}</div>
            </div>
            <div>
                <span class="badge bg-{{ $astrologer->status == 'approved' ? 'success' : ($astrologer->status == 'pending' ? 'warning' : 'secondary') }}">
                    {{ ucfirst($astrologer->status) }}
                </span>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs mb-3">
                <li class="nav-item"><a class="nav-link {{ $tab == 'profile' ? 'active' : '' }}" href="?tab=profile">Profile</a></li>
                <li class="nav-item"><a class="nav-link {{ $tab == 'skills' ? 'active' : '' }}" href="?tab=skills">Skills</a></li>
                <li class="nav-item"><a class="nav-link {{ $tab == 'languages' ? 'active' : '' }}" href="?tab=languages">Languages</a></li>
                <li class="nav-item"><a class="nav-link {{ $tab == 'availability' ? 'active' : '' }}" href="?tab=availability">Availability</a></li>
                <li class="nav-item"><a class="nav-link {{ $tab == 'pricing' ? 'active' : '' }}" href="?tab=pricing">Pricing</a></li>
                <li class="nav-item"><a class="nav-link {{ $tab == 'documents' ? 'active' : '' }}" href="?tab=documents">Documents</a></li>
                <li class="nav-item"><a class="nav-link {{ $tab == 'bank' ? 'active' : '' }}" href="?tab=bank">Bank Details</a></li>
                <li class="nav-item"><a class="nav-link {{ $tab == 'reviews' ? 'active' : '' }}" href="?tab=reviews">Reviews</a></li>
                <li class="nav-item"><a class="nav-link {{ $tab == 'services' ? 'active' : '' }}" href="?tab=services">Services</a></li>
                <li class="nav-item"><a class="nav-link {{ $tab == 'wallet' ? 'active' : '' }}" href="?tab=wallet">Wallet</a></li>
            </ul>
            <div class="tab-content mt-3">
                @include('admin.astrologers.tabs.' . $tab)
            </div>
        </div>
    </div>
</div>
@endsection
