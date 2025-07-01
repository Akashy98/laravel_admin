@extends('admin.layouts.app')

@section('title', 'Users Management')
@section('page-title', 'Users Management')
@section('page-subtitle', 'Manage system users')

@php
$breadcrumbs = [
    ['title' => 'Users Management', 'url' => route('admin.users')]
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
            <button class="btn btn-outline-secondary" id="exportUsers" data-bs-toggle="tooltip" title="Export to CSV">
                <i class="fas fa-download me-2"></i>Export
            </button>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="fas fa-plus me-2"></i>Add User
            </button>
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
                            <button class="btn btn-sm btn-outline-primary"
                                    data-action="edit-user"
                                    data-user-id="{{ $user->id }}"
                                    data-bs-toggle="tooltip"
                                    title="Edit User">
                                <i class="fas fa-edit"></i>
                            </button>
                            @if(!$user->is_admin)
                            <button class="btn btn-sm btn-outline-success"
                                    data-action="make-admin"
                                    data-user-id="{{ $user->id }}"
                                    data-bs-toggle="tooltip"
                                    title="Make Admin">
                                <i class="fas fa-user-shield"></i>
                            </button>
                            @endif
                            @if($user->id !== Auth::id())
                            <button class="btn btn-sm btn-outline-danger"
                                    data-action="delete-user"
                                    data-user-id="{{ $user->id }}"
                                    data-bs-toggle="tooltip"
                                    title="Delete User">
                                <i class="fas fa-trash"></i>
                            </button>
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

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addUserForm" action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_admin" name="is_admin">
                            <label class="form-check-label" for="is_admin">
                                Make Admin
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="loading-spinner d-none"></span>
                        Add User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">Password (leave blank to keep current)</label>
                        <input type="password" class="form-control" id="edit_password" name="password">
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="edit_is_admin" name="is_admin">
                            <label class="form-check-label" for="edit_is_admin">
                                Make Admin
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="loading-spinner d-none"></span>
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
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
    // Wait a bit to ensure all scripts are loaded
    setTimeout(function() {
        // Use the AdminDataTable wrapper
        const usersTable = AdminDataTable.init('#usersTable', {
            order: [[0, 'desc']],
            columnDefs: [
                { orderable: false, targets: -1 }
            ]
        });

        // Store reference for later use
        window.usersDataTable = usersTable;
    }, 100);

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Form submission handlers
document.getElementById('addUserForm').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    const spinner = submitBtn.querySelector('.loading-spinner');

    submitBtn.disabled = true;
    spinner.classList.remove('d-none');
});

document.getElementById('editUserForm').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    const spinner = submitBtn.querySelector('.loading-spinner');

    submitBtn.disabled = true;
    spinner.classList.remove('d-none');
});

// Handle window resize to refresh DataTable
window.addEventListener('resize', function() {
    if (window.usersDataTable) {
        AdminDataTable.refresh('#usersTable');
    }
});
</script>
@endpush
