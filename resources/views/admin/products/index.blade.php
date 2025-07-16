@extends('admin.layouts.app')

@section('title', 'Products')

@push('datatables-css')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="stats-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Products</h5>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add Product
        </a>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="table-responsive">
        <table class="table table-hover" id="productsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('datatables-js')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    $('#productsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('admin.products.list') }}',
            type: 'POST',
            data: {_token: '{{ csrf_token() }}'},
            error: function (xhr, error, thrown) {
                console.error('DataTable error:', error);
                console.error('Response:', xhr.responseText);
            }
        },
        columns: [
            { name: 'id', orderable: true, searchable: true },
            { name: 'name', orderable: true, searchable: true },
            { name: 'slug', orderable: true, searchable: true },
            { name: 'price', orderable: true, searchable: false },
            { name: 'stock', orderable: true, searchable: false },
            { name: 'is_active', orderable: true, searchable: false },
            { name: 'image', orderable: false, searchable: false },
            { name: 'actions', orderable: false, searchable: false },
        ],
        order: [[0, 'desc']]
    });
});
</script>
@endpush
