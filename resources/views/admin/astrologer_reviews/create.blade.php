@extends('admin.layouts.app')
@section('title', 'Add Astrologer Review')
@section('content')
<div class="card mb-4">
    <div class="card-body">
        <h5 class="mb-3">Add Review</h5>
        <form method="POST" action="{{ route('admin.astrologer_reviews.store') }}">
            @csrf
            <div class="mb-3">
                <label for="astrologer_id" class="form-label">Astrologer</label>
                <select name="astrologer_id" id="astrologer_id" class="form-control @error('astrologer_id') is-invalid @enderror" required>
                    <option value="">Select Astrologer</option>
                    @foreach($astrologers as $astrologer)
                        <option value="{{ $astrologer->id }}" {{ old('astrologer_id') == $astrologer->id ? 'selected' : '' }}>
                            {{ $astrologer->user->name ?? 'Astrologer #'.$astrologer->id }}
                        </option>
                    @endforeach
                </select>
                @error('astrologer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="user_id" class="form-label">User</label>
                <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                    <option value="">Select User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->phone }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="rating" class="form-label">Rating</label>
                <select name="rating" id="rating" class="form-control @error('rating') is-invalid @enderror" required>
                    <option value="">Select Rating</option>
                    @for($i=1; $i<=5; $i++)
                        <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
                @error('rating')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="review" class="form-label">Review</label>
                <textarea name="review" id="review" rows="4" class="form-control @error('review') is-invalid @enderror">{{ old('review') }}</textarea>
                @error('review')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.astrologer_reviews.index') }}" class="btn btn-secondary">Back</a>
                <button type="submit" class="btn btn-primary">Submit Review</button>
            </div>
        </form>
    </div>
</div>
@endsection
