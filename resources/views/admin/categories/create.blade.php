@extends('admin.layouts.app')

@section('title', 'Add Category')
@section('page-title', 'Add Category')
@section('page-subtitle', 'Create a new astrologer category')

@section('content')
<div class="row mt-4">
    <div class="col-md-7 col-lg-6">
        <div class="card shadow-lg border-0">
            <div class="card-header text-white" style="background: linear-gradient(90deg, #4f8cff 0%, #6fd6ff 100%);">
                <h4 class="mb-0 py-2"><i class="fas fa-star me-2"></i> Add Category</h4>
            </div>
            <div class="card-body p-5">
                <form method="POST" action="{{ route('admin.categories.store') }}">
                    @csrf
                    <div class="form-floating mb-4">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="Category Name">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating mb-4">
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" style="height: 100px; min-height: 60px;" placeholder="Description">{{ old('description') }}</textarea>
                        <label for="description">Description</label>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label d-block mb-2">Status</label>
                        <div class="form-check form-switch form-switch-lg ps-0">
                            <input class="form-check-input ms-2" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }} style="width:2.5em; height:1.5em;">
                            <label class="form-check-label ms-3" for="is_active">
                                {{ old('is_active', 1) ? 'Active' : 'Inactive' }}
                            </label>
                        </div>
                        @error('is_active')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary btn-lg">Cancel</a>
                        <button type="submit" class="btn btn-primary btn-lg shadow px-5">
                            <i class="fas fa-plus me-1"></i> Create Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
