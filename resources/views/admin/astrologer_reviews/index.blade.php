@extends('admin.layouts.app')
@section('title', 'Astrologer Reviews')
@section('page-title', 'Astrologer Reviews')
@section('page-subtitle', 'Manage reviews for astrologers')

@push('datatables-css')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="stats-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">All Reviews</h5>
        <a href="{{ route('admin.astrologer_reviews.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-2"></i> Add Review
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover" id="reviewsTable">
            <thead>
                <tr>
                    <th>Astrologer</th>
                    <th>User</th>
                    <th>Rating</th>
                    <th>Review</th>
                    <th>Date</th>
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
    $('#reviewsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('admin.astrologer_reviews.list') }}',
            error: function (xhr, error, thrown) {
                console.error('DataTable error:', error);
                console.error('Response:', xhr.responseText);
            }
        },
        columns: [
            { name: 'astrologer', orderable: true, searchable: true },
            { name: 'user', orderable: true, searchable: true },
            { name: 'rating', orderable: true, searchable: false },
            { name: 'review', orderable: false, searchable: true },
            { name: 'created_at', orderable: true, searchable: false },
            { name: 'actions', orderable: false, searchable: false },
        ],
        order: [[4, 'desc']]
    });
});
</script>
@endpush
