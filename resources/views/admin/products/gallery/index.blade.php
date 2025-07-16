@extends('admin.layouts.app')

@section('title', 'Product Gallery - ' . $product->name)
@section('page-title', 'Product Gallery')
@section('page-subtitle', $product->name)
@php($breadcrumbs = [
    ['title' => 'Products', 'url' => route('admin.products.index')],
    ['title' => $product->name, 'url' => route('admin.products.edit', $product)],
    ['title' => 'Gallery']
])

@section('content')
    <div class="row justify-content-center mb-4">
        <div class="col-xl-8 col-lg-9 col-md-10 col-12">
            <div class="card shadow-sm border-0 upload-card mb-0">
                <div class="card-body text-center p-4">
                    <div class="upload-area neutral-bg" id="uploadArea">
                        <div class="upload-content">
                            <div class="mb-3">
                                <i class="fas fa-cloud-upload-alt fa-3x text-secondary"></i>
                            </div>
                            <h5 class="fw-semibold mb-1">Drag & Drop Images Here</h5>
                            <p class="text-muted mb-3">or click to browse</p>
                            <input type="file" id="imageUpload" multiple accept="image/*" style="display: none;">
                            <button type="button" class="btn btn-primary btn-lg px-4 rounded-pill" id="uploadBtn">
                                <i class="fas fa-upload me-2"></i>Choose Images
                            </button>
                        </div>
                    </div>
                    <div class="row mt-4" id="uploadProgress" style="display: none;">
                        <div class="col-12">
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                            </div>
                            <small class="text-muted" id="uploadStatus">Preparing upload...</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mt-4">
        <div class="col-xl-10 col-lg-11 col-12">
            <div class="row g-3" id="galleryGrid">
                @forelse($images as $image)
                    <div class="col-lg-2 col-md-3 col-sm-4 col-6 d-flex">
                        <div class="gallery-item card border-0 shadow-sm w-100 h-100 position-relative overflow-hidden">
                            <div class="gallery-item-image bg-light d-flex align-items-center justify-content-center">
                                <img src="{{ $image->image_path }}" alt="Gallery Image" class="img-fluid gallery-img">
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 delete-image" data-image-id="{{ $image->id }}" data-product-id="{{ $product->id }}" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-state text-center py-5">
                            <i class="fas fa-images fa-3x text-muted mb-3"></i>
                            <h5 class="fw-semibold">No Images Yet</h5>
                            <p class="text-muted">Upload some images to get started</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

<style>
.upload-card {
    background: var(--bs-body-bg, #fff);
    border-radius: 1.25rem;
}
.upload-area {
    border: 2px dashed #d1d5db;
    border-radius: 1rem;
    padding: 40px 16px;
    background: var(--bs-light, #f8f9fa);
    transition: border-color 0.3s, background 0.3s;
    cursor: pointer;
    position: relative;
}
.upload-area:hover, .upload-area.dragover {
    border-color: #0d6efd;
    background: #f1f5f9;
}
.gallery-item {
    border-radius: 1rem;
    overflow: hidden;
    transition: box-shadow 0.2s;
    background: var(--bs-body-bg, #fff);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    min-height: 100%;
}
.gallery-item-image {
    position: relative;
    width: 100%;
    aspect-ratio: 1/1;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
}
.gallery-img {
    max-width: 100%;
    max-height: 160px;
    width: auto;
    height: auto;
    object-fit: cover;
    border-radius: 1rem;
    transition: transform 0.2s;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.gallery-item:hover .gallery-img {
    transform: scale(1.04);
}
.gallery-item .delete-image {
    opacity: 0;
    transition: opacity 0.2s;
    z-index: 2;
}
.gallery-item:hover .delete-image {
    opacity: 1;
}
.empty-state {
    color: var(--bs-secondary-color, #6c757d);
}
@media (max-width: 991.98px) {
    .gallery-img { max-height: 120px; }
}
@media (max-width: 767.98px) {
    .upload-card { padding: 0; }
    .upload-area { padding: 24px 4px; }
    .gallery-item { border-radius: 0.75rem; }
    .gallery-item-image { border-radius: 0.75rem; }
    .gallery-img { max-height: 90px; }
}
</style>

@push('scripts')
<script>
$(document).ready(function() {
    const uploadArea = $('#uploadArea');
    const imageUpload = $('#imageUpload');
    const uploadProgress = $('#uploadProgress');
    const uploadStatus = $('#uploadStatus');
    const galleryGrid = $('#galleryGrid');
    const emptyState = $('.empty-state');
    const productId = {{ $product->id }};

    // Drag and drop functionality
    uploadArea.on('dragover', function(e) {
        e.preventDefault();
        $(this).addClass('dragover');
    });
    uploadArea.on('dragleave', function(e) {
        e.preventDefault();
        $(this).removeClass('dragover');
    });
    uploadArea.on('drop', function(e) {
        e.preventDefault();
        $(this).removeClass('dragover');
        const files = e.originalEvent.dataTransfer.files;
        handleFileUpload(files);
    });
    uploadArea.on('click', function() {
        imageUpload.click();
    });
    $('#uploadBtn').on('click', function() {
        imageUpload.click();
    });
    imageUpload.on('change', function() {
        handleFileUpload(this.files);
    });
    function handleFileUpload(files) {
        if (files.length === 0) return;
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (!file.type.startsWith('image/')) {
                alert('Please select only image files');
                return;
            }
        }
        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('images[]', files[i]);
        }
        uploadProgress.show();
        uploadStatus.text('Uploading images...');
        uploadArea.addClass('uploading');
        $('#uploadBtn').prop('disabled', true).text('Uploading...');
        $.ajax({
            url: `{{ route('admin.products.gallery.store', $product) }}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            xhr: function() {
                const xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(evt) {
                    if (evt.lengthComputable) {
                        const percentComplete = (evt.loaded / evt.total) * 100;
                        $('.progress-bar').css('width', percentComplete + '%');
                    }
                }, false);
                return xhr;
            },
            success: function(response) {
                if (response.success) {
                    uploadStatus.text('Upload completed!');
                    setTimeout(() => {
                        uploadProgress.hide();
                        uploadArea.removeClass('uploading');
                        $('#uploadBtn').prop('disabled', false).html('<i class="fas fa-upload me-2"></i>Choose Images');
                        location.reload();
                    }, 1000);
                } else {
                    uploadStatus.text('Upload failed: ' + response.message);
                    uploadArea.removeClass('uploading');
                    $('#uploadBtn').prop('disabled', false).html('<i class="fas fa-upload me-2"></i>Choose Images');
                }
            },
            error: function(xhr) {
                let errorMessage = 'Upload failed';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    errorMessage = xhr.responseText;
                }
                uploadStatus.text(errorMessage);
                uploadArea.removeClass('uploading');
                $('#uploadBtn').prop('disabled', false).html('<i class="fas fa-upload me-2"></i>Choose Images');
            }
        });
    }
    $(document).on('click', '.delete-image', function() {
        const imageId = $(this).data('image-id');
        const productId = $(this).data('product-id');
        if (confirm('Are you sure you want to delete this image?')) {
            $.ajax({
                url: `/admin/products/${productId}/gallery/${imageId}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $(`.gallery-item[data-image-id="${imageId}"]`).fadeOut(300, function() {
                            $(this).remove();
                            if ($('.gallery-item').length === 0) {
                                emptyState.show();
                            }
                        });
                    } else {
                        alert('Failed to delete image: ' + response.message);
                    }
                },
                error: function() {
                    alert('Failed to delete image');
                }
            });
        }
    });
});
</script>
@endpush
