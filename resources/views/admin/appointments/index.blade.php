@extends('admin.layouts.app')

@section('title', 'Appointments Management')

@push('datatables-css')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-calendar-check me-2"></i>Appointments Management
        </h1>
        <div>
            <a href="{{ route('admin.appointments.statistics') }}" class="btn btn-info btn-sm">
                <i class="fas fa-chart-bar me-1"></i>Statistics
            </a>
            <a href="{{ route('admin.appointments.export', ['status' => $status]) }}" class="btn btn-success btn-sm">
                <i class="fas fa-download me-1"></i>Export
            </a>
        </div>
    </div>

    <!-- Status Filter Cards -->
    <div class="row mb-4">
        @foreach($statuses as $statusKey => $statusLabel)
        <div class="col-xl-2 col-md-3 col-sm-6 mb-3">
            <div class="card border-left-{{ $statusKey === $status ? 'primary' : 'secondary' }} shadow h-100 py-2 cursor-pointer"
                 onclick="filterByStatus('{{ $statusKey }}')"
                 style="cursor: pointer;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-{{ $statusKey === $status ? 'primary' : 'secondary' }} text-uppercase mb-1">
                                {{ $statusLabel }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($counts[$statusKey]) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            @php
                                $icons = [
                                    'all' => 'fas fa-list',
                                    'pending' => 'fas fa-clock',
                                    'accepted' => 'fas fa-check-circle',
                                    'in_progress' => 'fas fa-play-circle',
                                    'completed' => 'fas fa-check-double',
                                    'cancelled' => 'fas fa-times-circle',
                                    'expired' => 'fas fa-hourglass-end',
                                    'no_astrologer' => 'fas fa-user-slash'
                                ];
                            @endphp
                            <i class="{{ $icons[$statusKey] ?? 'fas fa-question' }} fa-2x text-{{ $statusKey === $status ? 'primary' : 'secondary' }}"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Appointments Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table me-2"></i>{{ $statuses[$status] ?? 'All Appointments' }}
            </h6>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="filterByStatus('all')">All Appointments</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterByStatus('pending')">Awaiting Response</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterByStatus('accepted')">Confirmed Bookings</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterByStatus('in_progress')">Active Sessions</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterByStatus('completed')">Completed Sessions</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterByStatus('cancelled')">Cancelled Bookings</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterByStatus('expired')">Expired Requests</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterByStatus('no_astrologer')">No Astrologer Available</a></li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="appointmentsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Astrologer</th>
                            <th>Fake/Original</th>
                            <th>Service</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Date & Time</th>
                            <th>Pricing</th>
                            <th>Payment</th>
                            <th>Rating</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats Modal -->
<div class="modal fade" id="quickStatsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quick Statistics</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Today's Activity</h6>
                        <div class="list-group">
                            <div class="list-group-item d-flex justify-content-between">
                                <span>New Appointments</span>
                                <span class="badge bg-primary">{{ $counts['pending'] }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between">
                                <span>Active Sessions</span>
                                <span class="badge bg-warning">{{ $counts['in_progress'] }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between">
                                <span>Completed Today</span>
                                <span class="badge bg-success">{{ $counts['completed'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Revenue Overview</h6>
                        <div class="list-group">
                            <div class="list-group-item d-flex justify-content-between">
                                <span>Total Revenue</span>
                                <span class="text-success">₹{{ number_format(App\Models\Appointment::where('payment_status', 'paid')->sum('amount_paid'), 2) }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between">
                                <span>Today's Revenue</span>
                                <span class="text-info">₹{{ number_format(App\Models\Appointment::where('payment_status', 'paid')->whereDate('created_at', today())->sum('amount_paid'), 2) }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between">
                                <span>This Month</span>
                                <span class="text-primary">₹{{ number_format(App\Models\Appointment::where('payment_status', 'paid')->whereMonth('created_at', now()->month)->sum('amount_paid'), 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('datatables-js')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
@endpush

@push('styles')
<style>
.cursor-pointer {
    cursor: pointer;
    transition: all 0.3s ease;
}

.cursor-pointer:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.border-left-primary {
    border-left: 4px solid #4e73df !important;
}

.border-left-secondary {
    border-left: 4px solid #858796 !important;
}

.border-left-success {
    border-left: 4px solid #1cc88a !important;
}

.border-left-info {
    border-left: 4px solid #36b9cc !important;
}

.border-left-warning {
    border-left: 4px solid #f6c23e !important;
}

.border-left-danger {
    border-left: 4px solid #e74a3b !important;
}

.border-left-dark {
    border-left: 4px solid #5a5c69 !important;
}

#appointmentsTable {
    font-size: 0.875rem;
}

#appointmentsTable td {
    vertical-align: middle;
}

.badge {
    font-size: 0.75rem;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>
@endpush

@push('scripts')
<script>
let appointmentsTable;

$(document).ready(function() {
    // Initialize DataTable
    appointmentsTable = $('#appointmentsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.appointments.list") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status: '{{ $status }}'
            },
            error: function (xhr, error, thrown) {
                console.error('DataTable error:', error);
                console.error('Response:', xhr.responseText);
            }
        },
        columns: [
            { name: 'id', orderable: true, searchable: false },
            { name: 'user_info', orderable: false, searchable: false },
            { name: 'astrologer_info', orderable: false, searchable: false },
            { name: 'fake_indicator', orderable: false, searchable: false },
            { name: 'service_type', orderable: false, searchable: false },
            { name: 'booking_type', orderable: false, searchable: false },
            { name: 'status', orderable: false, searchable: false },
            { name: 'date_time', orderable: false, searchable: false },
            { name: 'pricing', orderable: false, searchable: false },
            { name: 'payment_status', orderable: false, searchable: false },
            { name: 'rating', orderable: false, searchable: false },
            { name: 'created_at', orderable: false, searchable: false },
            { name: 'actions', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']],
        pageLength: 25,
        responsive: true,
        language: {
            search: "Search appointments:",
            lengthMenu: "Show _MENU_ appointments per page",
            info: "Showing _START_ to _END_ of _TOTAL_ appointments",
            infoEmpty: "No appointments found",
            infoFiltered: "(filtered from _MAX_ total appointments)",
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
        }
    });

    // Auto-refresh every 30 seconds for active sessions
    setInterval(function() {
        if (appointmentsTable) {
            appointmentsTable.ajax.reload(null, false);
        }
    }, 30000);
});

function filterByStatus(status) {
    window.location.href = '{{ route("admin.appointments.index") }}?status=' + status;
}

// Keyboard shortcuts
$(document).keydown(function(e) {
    if (e.ctrlKey && e.keyCode === 70) { // Ctrl+F
        e.preventDefault();
        $('#appointmentsTable_filter input').focus();
    }
    if (e.ctrlKey && e.keyCode === 82) { // Ctrl+R
        e.preventDefault();
        appointmentsTable.ajax.reload();
    }
});

// Show quick stats on double click
$('#appointmentsTable').on('dblclick', 'tr', function() {
    $('#quickStatsModal').modal('show');
});
</script>
@endpush
