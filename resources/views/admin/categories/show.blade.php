@extends('admin.layouts.app')

@section('title', 'Category Details')
@section('page-title', 'Category Details')
@section('page-subtitle', 'View astrologer category')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
        <span>Category Details</span>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-secondary">Back to List</a>
    </div>
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-4">Name</dt>
            <dd class="col-sm-8">{{ $category->name }}</dd>

            <dt class="col-sm-4">Description</dt>
            <dd class="col-sm-8">{{ $category->description }}</dd>

            <dt class="col-sm-4">Status</dt>
            <dd class="col-sm-8">
                @if($category->is_active)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-secondary">Inactive</span>
                @endif
            </dd>
        </dl>
    </div>
</div>
@endsection
