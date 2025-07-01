@extends('admin.layouts.app')

@section('title', 'Users Management')
@section('page-title', 'Users Management')
@section('page-subtitle', 'Manage system users')

@php
$breadcrumbs = [
    ['title' => 'Users Management', 'url' => route('admin.users.index')]
];
@endphp

@push('datatables-css')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="stats-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">All Users</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add User
            </a>
        </div>
    </div>

    <!-- Search and Filter Bar -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" class="form-control" id="userSearch" placeholder="Search users...">
            </div>
        </div>
        <div class="col-md-3">
            <select class="form-select table-filter" data-filter="role">
                <option value="all">All Roles</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select table-filter" data-filter="status">
                <option value="all">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover" id="usersTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=667eea&color=fff"
                                 alt="Avatar" class="rounded-circle me-2" width="32">
                            {{ $user->name }}
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td data-role="{{ $user->is_admin ? 'admin' : 'user' }}">
                        @if($user->is_admin)
                            <span class="badge bg-success">Admin</span>
                        @else
                            <span class="badge bg-secondary">User</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit User">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($user->id !== Auth::id())
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete User" onclick="return confirm('Are you sure you want to delete this user?');">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="d-flex justify-content-center mt-3">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection

@push('datatables-js')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
@endpush

@push('scripts')
<script>
// Initialize DataTable using the common wrapper
$(document).ready(function() {
    setTimeout(function() {
        const usersTable = AdminDataTable.init('#usersTable', {
            order: [[0, 'desc']],
            columnDefs: [
                { orderable: false, targets: -1 }
            ]
        });
        window.usersDataTable = usersTable;
    }, 100);
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
window.addEventListener('resize', function() {
    if (window.usersDataTable) {
        AdminDataTable.refresh('#usersTable');
    }
});
</script>
@endpush
