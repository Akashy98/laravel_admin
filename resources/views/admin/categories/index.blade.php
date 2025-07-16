@extends('admin.layouts.app')

@section('title', 'Categories Management')
@section('page-title', 'Categories Management')
@section('page-subtitle', 'Manage astrologer categories')

@push('datatables-css')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="stats-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">All Categories</h5>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-2"></i> Add Category
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover" id="categoriesTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Status</th>
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
    $('#categoriesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('admin.categories.list') }}',
            error: function (xhr, error, thrown) {
                console.error('DataTable error:', error);
                console.error('Response:', xhr.responseText);
            }
        },
        columns: [
            { name: 'name', orderable: true, searchable: true },
            { name: 'description', orderable: false, searchable: true },
            { name: 'status', orderable: true, searchable: false },
            { name: 'actions', orderable: false, searchable: false },
        ],
        order: [[0, 'asc']]
    });
});
</script>
@endpush
