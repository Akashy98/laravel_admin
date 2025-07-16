@extends('admin.layouts.app')

@section('title', 'Edit Video')
@section('page-title', 'Edit Video')
@section('page-subtitle', 'Update video details')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white border-bottom-0">
        <h5 class="mb-0"><i class="fas fa-video me-2"></i> Edit Video</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.videos.update', $video->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $video->title) }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="sort_order" class="form-label">Sort Order</label>
                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', $video->sort_order) }}" min="0">
                    @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-12">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $video->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="video_url" class="form-label">Video URL (YouTube, Vimeo, etc.)</label>
                    <input type="url" class="form-control @error('video_url') is-invalid @enderror" id="video_url" name="video_url" value="{{ old('video_url', $video->video_url) }}">
                    @error('video_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="video_file" class="form-label">Upload Video File</label>
                    @if($video->video_file)
                        <div class="mb-2">
                            <a href="{{ asset('storage/' . $video->video_file) }}" target="_blank">Current Video</a>
                        </div>
                    @endif
                    <input type="file" class="form-control @error('video_file') is-invalid @enderror" id="video_file" name="video_file" accept="video/*">
                    @error('video_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="thumbnail" class="form-label">Thumbnail Image</label>
                    @if($video->thumbnail)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $video->thumbnail) }}" alt="Thumbnail" style="max-width: 120px; max-height: 60px; object-fit: cover;">
                        </div>
                    @endif
                    <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" id="thumbnail" name="thumbnail" accept="image/*">
                    @error('thumbnail')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 d-flex align-items-center">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $video->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-success">Update Video</button>
                <a href="{{ route('admin.videos.index') }}" class="btn btn-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
