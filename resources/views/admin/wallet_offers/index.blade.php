@extends('admin.layouts.app')

@section('title', 'Wallet Offers')

@push('datatables-css')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="stats-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Wallet Offers</h5>
        <a href="{{ route('admin.wallet-offers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add Offer
        </a>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="table-responsive">
        <table class="table table-hover" id="walletOffersTable">
            <thead>
                <tr>
                    <th>Amount (₹)</th>
                    <th>Extra %</th>
                    <th>Label</th>
                    <th>Most Popular</th>
                    <th>Status</th>
                    <th>Sort Order</th>
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
    $('#walletOffersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('admin.wallet-offers.list') }}',
            type: 'POST',
            data: {_token: '{{ csrf_token() }}'},
            error: function (xhr, error, thrown) {
                console.error('DataTable error:', error);
                console.error('Response:', xhr.responseText);
            }
        },
        columns: [
            { name: 'amount', orderable: true, searchable: true },
            { name: 'extra_percent', orderable: true, searchable: false },
            { name: 'label', orderable: true, searchable: true },
            { name: 'is_popular', orderable: true, searchable: false },
            { name: 'status', orderable: true, searchable: false },
            { name: 'sort_order', orderable: true, searchable: false },
            { name: 'actions', orderable: false, searchable: false },
        ],
        order: [[0, 'asc']]
    });
});
</script>
@endpush
