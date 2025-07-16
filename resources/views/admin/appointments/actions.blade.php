<div class="btn-group" role="group">
    <a href="{{ route('admin.appointments.show', $appointment->id) }}"
       class="btn btn-sm btn-info"
       title="View Details">
        <i class="fas fa-eye"></i>
    </a>

    @if($appointment->status === 'pending')
        <button type="button"
                class="btn btn-sm btn-success"
                title="Accept Appointment"
                onclick="acceptAppointment({{ $appointment->id }})">
            <i class="fas fa-check"></i>
        </button>
    @endif

    @if($appointment->status === 'accepted')
        <button type="button"
                class="btn btn-sm btn-primary"
                title="Start Session"
                onclick="startSession({{ $appointment->id }})">
            <i class="fas fa-play"></i>
        </button>
    @endif

    @if($appointment->status === 'in_progress')
        <button type="button"
                class="btn btn-sm btn-success"
                title="Complete Session"
                onclick="completeSession({{ $appointment->id }})">
            <i class="fas fa-stop"></i>
        </button>
    @endif

    @if(in_array($appointment->status, ['pending', 'accepted', 'in_progress']))
        <button type="button"
                class="btn btn-sm btn-warning"
                title="Cancel Appointment"
                onclick="cancelAppointment({{ $appointment->id }})">
            <i class="fas fa-times"></i>
        </button>
    @endif

    <div class="btn-group" role="group">
        <button type="button"
                class="btn btn-sm btn-secondary dropdown-toggle"
                data-bs-toggle="dropdown"
                aria-expanded="false">
            <i class="fas fa-cog"></i>
        </button>
        <ul class="dropdown-menu">
            <li>
                <a class="dropdown-item" href="#"
                   onclick="assignAstrologer({{ $appointment->id }})">
                    <i class="fas fa-user-plus me-2"></i>Assign Astrologer
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="#"
                   onclick="updateStatus({{ $appointment->id }})">
                    <i class="fas fa-edit me-2"></i>Update Status
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item text-danger" href="#"
                   onclick="deleteAppointment({{ $appointment->id }})">
                    <i class="fas fa-trash me-2"></i>Delete
                </a>
            </li>
        </ul>
    </div>
</div>

<script>
function acceptAppointment(id) {
    if (confirm('Are you sure you want to accept this appointment?')) {
        updateAppointmentStatus(id, 'accepted');
    }
}

function startSession(id) {
    if (confirm('Are you sure you want to start this session?')) {
        updateAppointmentStatus(id, 'in_progress');
    }
}

function completeSession(id) {
    if (confirm('Are you sure you want to complete this session?')) {
        updateAppointmentStatus(id, 'completed');
    }
}

function cancelAppointment(id) {
    const reason = prompt('Please provide a reason for cancellation:');
    if (reason !== null) {
        updateAppointmentStatus(id, 'cancelled', reason);
    }
}

function updateAppointmentStatus(id, status, notes = '') {
    fetch(`/admin/appointments/${id}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            status: status,
            notes: notes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the appointment.');
    });
}

function assignAstrologer(id) {
    // This would open a modal to select an astrologer
    alert('Astrologer assignment feature will be implemented here.');
}

function updateStatus(id) {
    // This would open a modal to update status
    alert('Status update modal will be implemented here.');
}

function deleteAppointment(id) {
    if (confirm('Are you sure you want to delete this appointment? This action cannot be undone.')) {
        fetch(`/admin/appointments/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the appointment.');
        });
    }
}
</script>
