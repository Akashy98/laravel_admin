@extends('admin.layouts.app')

@section('title', 'Add Product')
@section('page-title', 'Add Product')
@section('page-subtitle', 'Create a new product')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white border-bottom-0">
        <h5 class="mb-0"><i class="fas fa-box me-2"></i> Add Product</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Name</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-tag"></i></span>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="slug" class="form-label">Slug</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-link"></i></span>
                        <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug') }}" required readonly>
                        @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-12">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label for="price" class="form-label">Price</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-rupee-sign"></i></span>
                        <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" required>
                        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="offer_percentage" class="form-label">Offer Percentage</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-percentage"></i></span>
                        <input type="number" step="0.01" min="0" max="100" class="form-control @error('offer_percentage') is-invalid @enderror" id="offer_percentage" name="offer_percentage" value="{{ old('offer_percentage', 0) }}" placeholder="0.00">
                        @error('offer_percentage')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <small class="text-muted">Enter percentage (0-100)</small>
                    <div id="offer_preview"></div>
                </div>
                <div class="col-md-3">
                    <label for="rating" class="form-label">Rating</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-star"></i></span>
                        <input type="number" step="0.01" min="0" max="5" class="form-control @error('rating') is-invalid @enderror" id="rating" name="rating" value="{{ old('rating', 0) }}" placeholder="0.00">
                        @error('rating')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <small class="text-muted">Enter rating (0-5)</small>
                    <div id="rating_preview"></div>
                </div>
                <div class="col-md-3">
                    <label for="stock" class="form-label">Stock</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-boxes"></i></span>
                        <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock') }}" required>
                        @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-12">
                    <label for="is_active" class="form-label">Status</label>
                    <div class="form-switch pt-2">
                        <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" {{ old('is_active', 1) ? 'checked' : '' }}>
                        <label class="form-check-label ms-2" for="is_active">Active</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="image" class="form-label">Main Image</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i> Create Product</button>
                <button type="button" class="btn btn-secondary" onclick="window.location='{{ route('admin.products.index') }}'">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function generateSlug(name) {
    const randomNum = Math.floor(100 + Math.random() * 900); // 3-digit random
    let slug = name.toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-');
    return `product-${randomNum}-${slug}`;
}

function updateOfferPreview() {
    const offerPercentage = parseFloat(document.getElementById('offer_percentage').value) || 0;
    const price = parseFloat(document.getElementById('price').value) || 0;
    const discountAmount = (price * offerPercentage) / 100;
    const finalPrice = price - discountAmount;

    const offerPreview = document.getElementById('offer_preview');
    if (offerPercentage > 0) {
        offerPreview.innerHTML = `<small class="text-success">Save ₹${discountAmount.toFixed(2)} (Final Price: ₹${finalPrice.toFixed(2)})</small>`;
    } else {
        offerPreview.innerHTML = '';
    }
}

function updateRatingPreview() {
    const rating = parseFloat(document.getElementById('rating').value) || 0;
    const ratingPreview = document.getElementById('rating_preview');

    if (rating > 0) {
        const stars = '★'.repeat(Math.floor(rating)) + '☆'.repeat(5 - Math.floor(rating));
        ratingPreview.innerHTML = `<small class="text-warning">${stars} (${rating})</small>`;
    } else {
        ratingPreview.innerHTML = '';
    }
}

document.getElementById('name').addEventListener('input', function() {
    document.getElementById('slug').value = generateSlug(this.value);
});

document.getElementById('offer_percentage').addEventListener('input', updateOfferPreview);
document.getElementById('price').addEventListener('input', updateOfferPreview);
document.getElementById('rating').addEventListener('input', updateRatingPreview);
</script>
@endpush
