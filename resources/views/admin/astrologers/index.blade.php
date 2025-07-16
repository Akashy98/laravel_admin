@extends('admin.layouts.app')

@section('title', 'Astrologers Management')
@section('page-title', 'Astrologers Management')
@section('page-subtitle', 'Manage astrologers')

@push('datatables-css')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="stats-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">All Astrologers</h5>
        <a href="{{ route('admin.astrologers.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-2"></i> Create Astrologer
        </a>
    </div>
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link{{ !isset($showDeleted) || !$showDeleted ? ' active' : '' }}" href="{{ route('admin.astrologers.index') }}">Active Astrologers</a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ isset($showDeleted) && $showDeleted ? ' active' : '' }}" href="{{ route('admin.astrologers.trashed') }}">Deleted Astrologers</a>
        </li>
    </ul>
    <div class="table-responsive">
        <table class="table table-hover" id="astrologersTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('datatables-js')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    $('#astrologersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ isset($showDeleted) && $showDeleted ? route('admin.astrologers.trashed.list') : route('admin.astrologers.list') }}',
            data: function(d) {
                @if(isset($showDeleted) && $showDeleted)
                d.showDeleted = true;
                @endif
            },
            error: function (xhr, error, thrown) {
                console.error('DataTable error:', error);
                console.error('Response:', xhr.responseText);
            }
        },
        columns: [
            { name: 'id', orderable: true, searchable: false },
            { name: 'name', orderable: false, searchable: true },
            { name: 'user.email', orderable: false, searchable: true },
            { name: 'user.phone', orderable: false, searchable: true },
            { name: 'status', orderable: true, searchable: true },
            { name: 'actions', orderable: false, searchable: false },
        ],
        order: [[0, 'desc']]
    });
});
</script>
@endpush
