@extends('admin.layouts.app')

@section('title', 'Edit Banner')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Edit Banner</h2>
        <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $banner->title) }}">
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="subtitle" class="form-label">Subtitle</label>
                        <input type="text" class="form-control @error('subtitle') is-invalid @enderror" id="subtitle" name="subtitle" value="{{ old('subtitle', $banner->subtitle) }}">
                        @error('subtitle')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $banner->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="cta_text" class="form-label">CTA Text</label>
                        <input type="text" class="form-control @error('cta_text') is-invalid @enderror" id="cta_text" name="cta_text" value="{{ old('cta_text', $banner->cta_text) }}">
                        @error('cta_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="cta_url" class="form-label">CTA URL</label>
                        <input type="text" class="form-control @error('cta_url') is-invalid @enderror" id="cta_url" name="cta_url" value="{{ old('cta_url', $banner->cta_url) }}">
                        @error('cta_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="type" class="form-label">Type *</label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="card" {{ old('type', $banner->type) === 'card' ? 'selected' : '' }}>Card</option>
                            <option value="popup" {{ old('type', $banner->type) === 'popup' ? 'selected' : '' }}>Popup</option>
                        </select>
                        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="image" class="form-label">Banner Image</label>
                        @if($banner->image)
                            <div class="mb-2">
                                <img src="{{ $banner->image }}" alt="Banner Image" class="img-fluid rounded" style="max-height:120px;">
                                <div class="mt-1">
                                    <small class="text-muted">Current: {{ $banner->image }}</small>
                                </div>
                            </div>
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        <small class="text-muted">Leave blank to keep current image. Max size: 10MB. Supported formats: JPG, PNG, GIF, WEBP</small>
                        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="show_on" class="form-label">Show On</label>
                        <input type="text" class="form-control @error('show_on') is-invalid @enderror" id="show_on" name="show_on" value="{{ old('show_on', $banner->show_on) }}" placeholder="e.g. home, wallet">
                        @error('show_on')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="status" class="form-label">Status *</label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="active" {{ old('status', $banner->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $banner->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="sort_order" class="form-label">Sort Order</label>
                        <input type="number" min="0" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', $banner->sort_order) }}">
                        @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date', $banner->start_date ? $banner->start_date->format('Y-m-d\TH:i') : '') }}">
                        @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date', $banner->end_date ? $banner->end_date->format('Y-m-d\TH:i') : '') }}">
                        @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="astrologer_id" class="form-label">Astrologer</label>
                        <select class="form-select @error('astrologer_id') is-invalid @enderror" id="astrologer_id" name="astrologer_id">
                            <option value="">Select Astrologer</option>
                            @foreach($astrologers as $astrologer)
                                @php
                                    $specialization = $astrologer->skills->first() ? $astrologer->skills->first()->category->name : 'General';
                                @endphp
                                <option value="{{ $astrologer->id }}" {{ old('astrologer_id', $banner->astrologer_id) == $astrologer->id ? 'selected' : '' }}>
                                    {{ $astrologer->user->name }} ({{ $specialization }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Select an astrologer for call/chat booking when banner is clicked</small>
                        @error('astrologer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Banner
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
