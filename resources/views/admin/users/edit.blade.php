@extends('admin.layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit User')
@section('page-subtitle', 'Edit user details')

@php
$breadcrumbs = [
    ['title' => 'Users Management', 'url' => route('admin.users.index')],
    ['title' => 'Edit User']
];
@endphp

@section('content')
<div class="container-fluid py-4">
    <h3 class="mb-1 fw-bold"><i class="fas fa-user-edit me-2 text-warning"></i>Edit User</h3>
    <p class="text-muted mb-4">Update the details below.</p>
    <div class="row">
        <div class="col-12 mx-2">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" autocomplete="off">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password <span class="text-muted">(leave blank to keep current)</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-check mb-4">
                            <input type="checkbox" class="form-check-input" id="is_admin" name="is_admin" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_admin">
                                <i class="fas fa-user-shield me-1"></i> Make Admin
                            </label>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning text-white px-4">
                                <i class="fas fa-save me-2"></i>Update User
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-arrow-left me-1"></i>Back to Users
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
