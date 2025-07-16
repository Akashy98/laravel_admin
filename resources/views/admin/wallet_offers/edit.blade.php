@extends('admin.layouts.app')

@section('title', 'Edit Wallet Offer')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Edit Wallet Offer</h2>
        <a href="{{ route('admin.wallet-offers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.wallet-offers.update', $wallet_offer) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="amount" class="form-label">Amount (₹) *</label>
                        <input type="number" step="0.01" min="1" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount', $wallet_offer->amount) }}" required>
                        @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="extra_percent" class="form-label">Extra % *</label>
                        <input type="number" min="0" max="100" class="form-control @error('extra_percent') is-invalid @enderror" id="extra_percent" name="extra_percent" value="{{ old('extra_percent', $wallet_offer->extra_percent) }}" required>
                        @error('extra_percent')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="label" class="form-label">Label/Tag</label>
                        <input type="text" class="form-control @error('label') is-invalid @enderror" id="label" name="label" value="{{ old('label', $wallet_offer->label) }}" placeholder="e.g. Most Popular">
                        @error('label')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" id="is_popular" name="is_popular" value="1" {{ old('is_popular', $wallet_offer->is_popular) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_popular">Most Popular</label>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="status" class="form-label">Status *</label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="active" {{ old('status', $wallet_offer->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $wallet_offer->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="sort_order" class="form-label">Sort Order</label>
                        <input type="number" min="0" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', $wallet_offer->sort_order) }}">
                        @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Offer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
