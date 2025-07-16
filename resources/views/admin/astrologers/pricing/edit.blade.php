@extends('admin.layouts.app')
@section('title', 'Edit Astrologer Pricing')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Edit Pricing</h5>
                    <form method="POST" action="{{ route('admin.astrologers.pricing.update', [$pricing->astrologer_id, $pricing->id]) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Service Type</label>
                            <input type="text" class="form-control" value="{{ $pricing->service ? $pricing->service->name : 'Unknown' }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price/Minute</label>
                            <input type="number" step="0.01" name="price_per_minute" class="form-control @error('price_per_minute') is-invalid @enderror" value="{{ old('price_per_minute', $pricing->price_per_minute) }}" required>
                            @error('price_per_minute')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Offer Price</label>
                            <input type="number" step="0.01" name="offer_price" class="form-control @error('offer_price') is-invalid @enderror" value="{{ old('offer_price', $pricing->offer_price) }}" placeholder="Offer Price (optional)">
                            @error('offer_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Update Pricing</button>
                            <a href="{{ route('admin.astrologers.show', [$pricing->astrologer_id, 'tab' => 'pricing']) }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
