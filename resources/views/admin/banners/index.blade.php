@extends('admin.layouts.app')

@section('title', 'Banners')

@push('datatables-css')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="stats-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Banners</h5>
        <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add Banner
        </a>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="table-responsive">
        <table class="table table-hover" id="bannersTable">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Subtitle</th>
                    <th>Description</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Sort Order</th>
                    <th>Show On</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Astrologer</th>
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
    $('#bannersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('admin.banners.list') }}',
            type: 'POST',
            data: {_token: '{{ csrf_token() }}'},
            error: function (xhr, error, thrown) {
                console.error('DataTable error:', error);
                console.error('Response:', xhr.responseText);
            }
        },
        columns: [
            { name: 'title', orderable: true, searchable: true },
            { name: 'subtitle', orderable: true, searchable: true },
            { name: 'description', orderable: true, searchable: true },
            { name: 'type', orderable: true, searchable: true },
            { name: 'status', orderable: true, searchable: false },
            { name: 'sort_order', orderable: true, searchable: false },
            { name: 'show_on', orderable: true, searchable: true },
            { name: 'start_date', orderable: true, searchable: false },
            { name: 'end_date', orderable: true, searchable: false },
            { name: 'astrologer', orderable: true, searchable: false },
            { name: 'image', orderable: false, searchable: false },
            { name: 'actions', orderable: false, searchable: false },
        ],
        order: [[0, 'asc']]
    });
});
</script>
@endpush
