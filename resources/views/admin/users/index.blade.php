@extends('admin.layouts.app')

@section('title', 'Customers Management')
@section('page-title', 'Customers Management')
@section('page-subtitle', 'Manage customers')

@php
$breadcrumbs = [
    ['title' => 'Customers Management', 'url' => route('admin.users.index')]
];
@endphp

@push('datatables-css')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="stats-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">{{ isset($showDeleted) && $showDeleted ? 'Deleted Customers' : 'All Customers' }}</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Customer
            </a>
        </div>
    </div>
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link{{ !isset($showDeleted) || !$showDeleted ? ' active' : '' }}" href="{{ route('admin.users.index') }}">Active Customers</a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ isset($showDeleted) && $showDeleted ? ' active' : '' }}" href="{{ route('admin.users.trashed') }}">Deleted Customers</a>
        </li>
    </ul>
    <div class="table-responsive">
        <table class="table table-hover" id="usersTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Created</th>
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
    $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ isset($showDeleted) && $showDeleted ? route('admin.users.trashed.list') : route('admin.users.list') }}',
            data: function(d) {
                @if(isset($showDeleted) && $showDeleted)
                d.showDeleted = true;
                @endif
            }
        },
        columns: [
            { name: 'id', orderable: true, searchable: false },
            { name: 'name', orderable: false, searchable: true },
            { name: 'phone', orderable: false, searchable: true },
            { name: 'status', orderable: true, searchable: true },
            { name: 'created_at', orderable: true, searchable: false },
            { name: 'actions', orderable: false, searchable: false },
        ],
        order: [[0, 'desc']]
    });
});
</script>
@endpush

