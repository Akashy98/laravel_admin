@extends('admin.layouts.app')

@section('title', 'User Details')
@section('page-title', 'User Details')
@section('page-subtitle', 'View user details')

@php
$breadcrumbs = [
    ['title' => 'Users Management', 'url' => route('admin.users.index')],
    ['title' => 'User Details']
];
@endphp

@section('content')
<div class="container-fluid py-4">
    <h3 class="mb-1 fw-bold"><i class="fas fa-user me-2 text-primary"></i>User Details</h3>
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <ul class="nav nav-tabs mb-3" id="userTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true">Basic Info</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="birth-tab" data-bs-toggle="tab" data-bs-target="#birth" type="button" role="tab" aria-controls="birth" aria-selected="false">Birth Details</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="address-tab" data-bs-toggle="tab" data-bs-target="#address" type="button" role="tab" aria-controls="address" aria-selected="false">Addresses</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Contacts</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="astro-tab" data-bs-toggle="tab" data-bs-target="#astro" type="button" role="tab" aria-controls="astro" aria-selected="false">Astro Info</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="userTabContent">
                        <!-- Basic Info Tab -->
                        <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <dl class="row mb-0 py-3">
                                <dt class="col-sm-3">Name:</dt>
                                <dd class="col-sm-9">{{ $user->name }}</dd>

                                <dt class="col-sm-3">First Name:</dt>
                                <dd class="col-sm-9">{{ $user->first_name ?? '-' }}</dd>

                                <dt class="col-sm-3">Last Name:</dt>
                                <dd class="col-sm-9">{{ $user->last_name ?? '-' }}</dd>

                                <dt class="col-sm-3">Email:</dt>
                                <dd class="col-sm-9">{{ $user->email }}</dd>

                                <dt class="col-sm-3">Phone:</dt>
                                <dd class="col-sm-9">{{ $user->phone ?? '-' }}</dd>

                                <dt class="col-sm-3">Country Code:</dt>
                                <dd class="col-sm-9">{{ $user->country_code ?? '-' }}</dd>

                                <dt class="col-sm-3">Gender:</dt>
                                <dd class="col-sm-9">{{ $user->gender ?? '-' }}</dd>

                                <dt class="col-sm-3">Profile Image:</dt>
                                <dd class="col-sm-9">
                                    @if($user->profile_image)
                                        <img src="{{ $user->profile_image }}" alt="Profile" class="img-thumbnail" style="max-width: 100px;">
                                    @else
                                        <span class="text-muted">No image</span>
                                    @endif
                                </dd>

                                <dt class="col-sm-3">Email Verified:</dt>
                                <dd class="col-sm-9">
                                    @if($user->email_verified_at)
                                        <span class="badge bg-success">Yes ({{ $user->email_verified_at->format('d M Y H:i') }})</span>
                                    @else
                                        <span class="badge bg-warning">No</span>
                                    @endif
                                </dd>

                                <dt class="col-sm-3">Created At:</dt>
                                <dd class="col-sm-9">{{ $user->created_at->format('d M Y H:i') }}</dd>

                                <dt class="col-sm-3">Updated At:</dt>
                                <dd class="col-sm-9">{{ $user->updated_at->format('d M Y H:i') }}</dd>
                            </dl>
                        </div>

                        <!-- Birth Details Tab -->
                        <div class="tab-pane fade" id="birth" role="tabpanel" aria-labelledby="birth-tab">
                            <dl class="row mb-0 py-3">
                                <dt class="col-sm-3">Birth Date:</dt>
                                <dd class="col-sm-9">{{ $user->profile->birth_date ? $user->profile->birth_date->format('d M Y') : '-' }}</dd>

                                <dt class="col-sm-3">Birth Time:</dt>
                                <dd class="col-sm-9">{{ $user->profile->birth_time ?? '-' }}</dd>

                                <dt class="col-sm-3">Birth Time Accuracy:</dt>
                                <dd class="col-sm-9">{{ $user->profile->birth_time_accuracy ?? '-' }}</dd>

                                <dt class="col-sm-3">Birth Place:</dt>
                                <dd class="col-sm-9">{{ $user->profile->birth_place ?? '-' }}</dd>

                                <dt class="col-sm-3">Birth Notes:</dt>
                                <dd class="col-sm-9">{{ $user->profile->birth_notes ?? '-' }}</dd>

                                <dt class="col-sm-3">Gender:</dt>
                                <dd class="col-sm-9">{{ $user->profile->gender ?? '-' }}</dd>

                                <dt class="col-sm-3">Marital Status:</dt>
                                <dd class="col-sm-9">{{ $user->profile->marital_status ?? '-' }}</dd>

                                <dt class="col-sm-3">Marriage Date:</dt>
                                <dd class="col-sm-9">{{ $user->profile->marriage_date ? $user->profile->marriage_date->format('d M Y') : '-' }}</dd>
                            </dl>
                        </div>

                        <!-- Addresses Tab -->
                        <div class="tab-pane fade" id="address" role="tabpanel" aria-labelledby="address-tab">
                            @if($user->addresses && $user->addresses->count() > 0)
                                @foreach($user->addresses as $address)
                                    <div class="card mb-3">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">{{ ucfirst($address->address_type) }} Address</h6>
                                            @if($address->is_primary)
                                                <span class="badge bg-primary">Primary</span>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <dl class="row mb-0">
                                                <dt class="col-sm-3">Country:</dt>
                                                <dd class="col-sm-9">{{ $address->country ?? '-' }}</dd>

                                                <dt class="col-sm-3">State:</dt>
                                                <dd class="col-sm-9">{{ $address->state ?? '-' }}</dd>

                                                <dt class="col-sm-3">City:</dt>
                                                <dd class="col-sm-9">{{ $address->city ?? '-' }}</dd>

                                                <dt class="col-sm-3">Address:</dt>
                                                <dd class="col-sm-9">{{ $address->address ?? '-' }}</dd>

                                                <dt class="col-sm-3">Postal Code:</dt>
                                                <dd class="col-sm-9">{{ $address->postal_code ?? '-' }}</dd>

                                                <dt class="col-sm-3">Latitude:</dt>
                                                <dd class="col-sm-9">{{ $address->latitude ?? '-' }}</dd>

                                                <dt class="col-sm-3">Longitude:</dt>
                                                <dd class="col-sm-9">{{ $address->longitude ?? '-' }}</dd>

                                                <dt class="col-sm-3">Timezone:</dt>
                                                <dd class="col-sm-9">{{ $address->timezone ?? '-' }}</dd>

                                                <dt class="col-sm-3">Status:</dt>
                                                <dd class="col-sm-9">
                                                    <span class="badge {{ $address->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ $address->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-map-marker-alt fa-3x mb-3"></i>
                                    <p>No addresses found for this user.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Contacts Tab -->
                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                            @if($user->contacts && $user->contacts->count() > 0)
                                @foreach($user->contacts as $contact)
                                    <div class="card mb-3">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">{{ ucfirst($contact->contact_type) }} Contact</h6>
                                            @if($contact->is_primary)
                                                <span class="badge bg-primary">Primary</span>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <dl class="row mb-0">
                                                <dt class="col-sm-3">Contact Name:</dt>
                                                <dd class="col-sm-9">{{ $contact->contact_name ?? '-' }}</dd>

                                                <dt class="col-sm-3">Phone Number:</dt>
                                                <dd class="col-sm-9">{{ $contact->phone_number ?? '-' }}</dd>

                                                <dt class="col-sm-3">Relationship:</dt>
                                                <dd class="col-sm-9">{{ $contact->relationship ?? '-' }}</dd>

                                                <dt class="col-sm-3">Country Code:</dt>
                                                <dd class="col-sm-9">{{ $contact->country_code ?? '-' }}</dd>

                                                <dt class="col-sm-3">Status:</dt>
                                                <dd class="col-sm-9">
                                                    <span class="badge {{ $contact->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ $contact->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-address-book fa-3x mb-3"></i>
                                    <p>No contacts found for this user.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Astro Info Tab -->
                        <div class="tab-pane fade" id="astro" role="tabpanel" aria-labelledby="astro-tab">
                            <dl class="row mb-0 py-3">
                                <dt class="col-sm-3">Religion:</dt>
                                <dd class="col-sm-9">{{ $user->profile->religion ?? '-' }}</dd>

                                <dt class="col-sm-3">Caste:</dt>
                                <dd class="col-sm-9">{{ $user->profile->caste ?? '-' }}</dd>

                                <dt class="col-sm-3">Gotra:</dt>
                                <dd class="col-sm-9">{{ $user->profile->gotra ?? '-' }}</dd>

                                <dt class="col-sm-3">Nakshatra:</dt>
                                <dd class="col-sm-9">{{ $user->profile->nakshatra ?? '-' }}</dd>

                                <dt class="col-sm-3">Rashi:</dt>
                                <dd class="col-sm-9">{{ $user->profile->rashi ?? '-' }}</dd>

                                <dt class="col-sm-3">About Me:</dt>
                                <dd class="col-sm-9">{{ $user->profile->about_me ?? '-' }}</dd>

                                <dt class="col-sm-3">Additional Notes:</dt>
                                <dd class="col-sm-9">{{ $user->profile->additional_notes ?? '-' }}</dd>

                                <dt class="col-sm-3">Profile Complete:</dt>
                                <dd class="col-sm-9">
                                    <span class="badge {{ $user->profile->is_profile_complete ? 'bg-success' : 'bg-warning' }}">
                                        {{ $user->profile->is_profile_complete ? 'Yes' : 'No' }}
                                    </span>
                                </dd>

                                <dt class="col-sm-3">Profile Status:</dt>
                                <dd class="col-sm-9">
                                    <span class="badge {{ $user->profile->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $user->profile->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-light fw-bold">Actions</div>
                <div class="card-body">
                    <div class="mb-3 text-center">
                        <span class="badge {{ $user->status == 1 ? 'bg-success' : 'bg-secondary' }}">
                            {{ $user->status == 1 ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <form action="{{ route('admin.users.status', $user->id) }}" method="POST" class="mb-3">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-{{ $user->status == 1 ? 'secondary' : 'success' }} w-100">
                            <i class="fas fa-toggle-{{ $user->status == 1 ? 'off' : 'on' }} me-1"></i>
                            {{ $user->status == 1 ? 'Set Inactive' : 'Set Active' }}
                        </button>
                    </form>
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning w-100 mb-2"><i class="fas fa-edit me-1"></i>Edit User</a>
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100"><i class="fas fa-trash me-1"></i>Delete User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card mt-3">
                <div class="card-header bg-light fw-bold">User Device Tokens</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Device Type</th>
                                    <th>Device ID</th>
                                    <th>FCM Token</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($user->deviceTokens as $token)
                                <tr>
                                    <td>
                                        @if(strtolower($token->device_type) === 'android')
                                            <i class="fas fa-mobile-alt me-1"></i> Android
                                        @elseif(strtolower($token->device_type) === 'ios')
                                            <i class="fab fa-apple me-1"></i> iOS
                                        @else
                                            {{ $token->device_type }}
                                        @endif
                                    </td>
                                    <td>{{ $token->device_id }}</td>
                                    <td style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <span class="fcm-token-text">{{ $token->fcm_token }}</span>
                                    </td>
                                    <td>{{ $token->created_at->format('d M Y h:i A') }}</td>
                                    <td>{{ $token->updated_at->format('d M Y h:i A') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary copy-fcm-token" data-token="{{ $token->fcm_token }}"><i class="far fa-copy"></i> Copy</button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No device tokens found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card mt-3">
                <div class="card-header bg-light fw-bold">Wallet</div>
                <div class="card-body">
                    <p><strong>Balance:</strong> ₹{{ number_format($user->wallet->balance ?? 0, 2) }}</p>
                    <h6>Recent Transactions</h6>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($user->wallet && $user->wallet->transactions ? $user->wallet->transactions : [] as $txn)
                                    <tr>
                                        <td>{{ $txn->created_at->format('d M Y H:i') }}</td>
                                        <td>{{ ucfirst($txn->type) }}</td>
                                        <td>
                                            @if($txn->type == 'debit')
                                                -₹{{ number_format($txn->amount, 2) }}
                                            @else
                                                +₹{{ number_format($txn->amount, 2) }}
                                            @endif
                                        </td>
                                        <td>{{ $txn->description }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4">No transactions found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.copy-fcm-token').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const token = this.getAttribute('data-token');
                navigator.clipboard.writeText(token);
                this.innerHTML = '<i class="far fa-check-circle"></i> Copied';
                setTimeout(() => {
                    this.innerHTML = '<i class="far fa-copy"></i> Copy';
                }, 2000);
            });
        });
    });
</script>
@endpush
