@extends('admin.layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit User')
@section('page-subtitle', 'Edit user details')

@php
$breadcrumbs = [
    ['title' => 'Users Management', 'url' => route('admin.users.index')],
    ['title' => 'Edit User']
];
@endphp

@section('content')
<div class="container-fluid py-4">
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <h3 class="mb-1 fw-bold"><i class="fas fa-user-edit me-2 text-warning"></i>Edit User</h3>
    <p class="text-muted mb-4">Update the details below.</p>
    <div class="row">
        <div class="col-12 mx-2">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        @method('PUT')
                        <ul class="nav nav-tabs mb-3" id="editUserTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab" aria-controls="personal" aria-selected="true">Personal Info</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="address-tab" data-bs-toggle="tab" data-bs-target="#address" type="button" role="tab" aria-controls="address" aria-selected="false">Address</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="contacts-tab" data-bs-toggle="tab" data-bs-target="#contacts" type="button" role="tab" aria-controls="contacts" aria-selected="false">Contacts</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Profile</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="future-tab" data-bs-toggle="tab" data-bs-target="#future" type="button" role="tab" aria-controls="future" aria-selected="false">More</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="editUserTabContent">
                            <!-- Personal Info Tab -->
                            <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label fw-semibold">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label fw-semibold">Phone</label>
                                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label fw-semibold">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="country_code" class="form-label fw-semibold">Country Code</label>
                                        <input type="text" class="form-control" id="country_code" name="country_code" value="{{ old('country_code', $user->country_code) }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="gender" class="form-label fw-semibold">Gender</label>
                                        <select class="form-select" id="gender" name="gender">
                                            <option value="">Select Gender</option>
                                            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                            <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="status" class="form-label fw-semibold">Status</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="1" {{ old('status', $user->status) == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ old('status', $user->status) == 0 ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="profile_image" class="form-label fw-semibold">Profile Image</label>
                                        <div class="d-flex align-items-center gap-3">
                                            @if($user->profile_image)
                                                <div class="profile-image-preview">
                                                    <img src="{{ $user->profile_image }}" alt="Profile Image" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
                                                </div>
                                            @endif
                                            <div class="flex-grow-1">
                                                <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                                                <small class="text-muted">Leave empty to keep current image. Max size: 10MB</small>
                                            </div>
                                        </div>
                                        @if($user->profile_image)
                                            <div class="mt-2">
                                                <small class="text-muted">Current: {{ $user->profile_image }}</small>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label fw-semibold">Password <span class="text-muted">(leave blank to keep current)</span></label>
                                        <input type="password" class="form-control" id="password" name="password" autocomplete="new-password">
                                    </div>
                                </div>
                            </div>
                            <!-- Address Tab -->
                            <div class="tab-pane fade" id="address" role="tabpanel" aria-labelledby="address-tab">
                                <div id="addresses-wrapper">
                                    @foreach($user->addresses as $i => $address)
                                    @php
                                        // Find country ID by name
                                        $selectedCountryId = null;
                                        foreach($countries as $country) {
                                            if (strtolower($country->name) === strtolower($address->country ?? '')) {
                                                $selectedCountryId = $country->id;
                                                break;
                                            }
                                        }
                                        $selectedCountryId = old('addresses.'.$i.'.country_id', $selectedCountryId);
                                    @endphp
                                    <div class="address-block card mb-3 p-3 position-relative">
                                        <input type="hidden" name="addresses[{{ $i }}][id]" value="{{ $address->id }}">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold">Address Type</label>
                                                <select class="form-select" name="addresses[{{ $i }}][address_type]">
                                                    <option value="">Select Address Type</option>
                                                    @foreach(config('constants.ADDRESS_TYPES') as $key => $label)
                                                        <option value="{{ $key }}" {{ old('addresses.'.$i.'.address_type', $address->address_type) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold">Country</label>
                                                <select class="form-select country-select" name="addresses[{{ $i }}][country_id]" data-index="{{ $i }}">
                                                    <option value="">Select Country</option>
                                                    @foreach($countries as $country)
                                                        <option value="{{ $country->id }}" {{ $selectedCountryId == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold">State</label>
                                                @php
                                                    // Find state ID by name (for selected country)
                                                    $selectedStateId = null;
                                                    $states = $selectedCountryId ? \App\Models\State::where('country_id', $selectedCountryId)->get() : collect();
                                                    foreach($states as $state) {
                                                        if (strtolower($state->name) === strtolower($address->state ?? '')) {
                                                            $selectedStateId = $state->id;
                                                            break;
                                                        }
                                                    }
                                                    $selectedStateId = old('addresses.'.$i.'.state_id', $selectedStateId);
                                                @endphp
                                                <select class="form-select state-select" name="addresses[{{ $i }}][state_id]" data-index="{{ $i }}">
                                                    <option value="">Select State</option>
                                                    @foreach($states as $state)
                                                        <option value="{{ $state->id }}" {{ $selectedStateId == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold">City</label>
                                                @php
                                                    // Find city ID by name (for selected state)
                                                    $selectedCityId = null;
                                                    $cities = $selectedStateId ? \App\Models\City::where('state_id', $selectedStateId)->get() : collect();
                                                    foreach($cities as $city) {
                                                        if (strtolower($city->name) === strtolower($address->city ?? '')) {
                                                            $selectedCityId = $city->id;
                                                            break;
                                                        }
                                                    }
                                                    $selectedCityId = old('addresses.'.$i.'.city_id', $selectedCityId);
                                                @endphp
                                                <select class="form-select city-select" name="addresses[{{ $i }}][city_id]" data-index="{{ $i }}">
                                                    <option value="">Select City</option>
                                                    @foreach($cities as $city)
                                                        <option value="{{ $city->id }}" {{ $selectedCityId == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold">Address</label>
                                                <input type="text" class="form-control" name="addresses[{{ $i }}][address]" value="{{ old('addresses.'.$i.'.address', $address->address) }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold">Postal Code</label>
                                                <input type="text" class="form-control" name="addresses[{{ $i }}][postal_code]" value="{{ old('addresses.'.$i.'.postal_code', $address->postal_code) }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold">Timezone</label>
                                                <select class="form-select" name="addresses[{{ $i }}][timezone]">
                                                    <option value="">Select Timezone</option>
                                                    @foreach(config('constants.TIMEZONES') as $key => $label)
                                                        <option value="{{ $key }}" {{ old('addresses.'.$i.'.timezone', $address->timezone) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @if($i > 0)
                                        <button type="button" class="btn btn-danger btn-sm remove-address position-absolute top-0 end-0 m-2"><i class="fas fa-times"></i></button>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-outline-primary" id="add-address-btn"><i class="fas fa-plus"></i> Add Address</button>
                            </div>
                            <!-- Address block template (hidden) -->
                            <template id="address-template">
                                <div class="address-block card mb-3 p-3 position-relative">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Address Type</label>
                                            <select class="form-select" name="__NAME__[address_type]">
                                                <option value="">Select Address Type</option>
                                                @foreach(config('constants.ADDRESS_TYPES') as $key => $label)
                                                    <option value="{{ $key }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Country</label>
                                            <select class="form-select country-select" name="__NAME__[country_id]" data-index="__INDEX__">
                                                <option value="">Select Country</option>
                                                @foreach($countries as $country)
                                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">State</label>
                                            <select class="form-select state-select" name="__NAME__[state_id]" data-index="__INDEX__">
                                                <option value="">Select State</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">City</label>
                                            <select class="form-select city-select" name="__NAME__[city_id]" data-index="__INDEX__">
                                                <option value="">Select City</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Address</label>
                                            <input type="text" class="form-control" name="__NAME__[address]">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Postal Code</label>
                                            <input type="text" class="form-control" name="__NAME__[postal_code]">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Timezone</label>
                                            <select class="form-select" name="__NAME__[timezone]">
                                                <option value="">Select Timezone</option>
                                                @foreach(config('constants.TIMEZONES') as $key => $label)
                                                    <option value="{{ $key }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-danger btn-sm remove-address position-absolute top-0 end-0 m-2"><i class="fas fa-times"></i></button>
                                </div>
                            </template>
                            <!-- Contacts Tab -->
                            <div class="tab-pane fade" id="contacts" role="tabpanel" aria-labelledby="contacts-tab">
                                <div id="contacts-wrapper">
                                    @foreach($user->contacts as $i => $contact)
                                    <div class="contact-block card mb-3 p-3 position-relative">
                                        <input type="hidden" name="contacts[{{ $i }}][id]" value="{{ $contact->id }}">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold">Contact Type</label>
                                                <select class="form-select" name="contacts[{{ $i }}][contact_type]">
                                                    <option value="">Select Contact Type</option>
                                                    @foreach(config('constants.CONTACT_TYPES') as $key => $label)
                                                        <option value="{{ $key }}" {{ old('contacts.'.$i.'.contact_type', $contact->contact_type) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold">Contact Name</label>
                                                <input type="text" class="form-control" name="contacts[{{ $i }}][contact_name]" value="{{ old('contacts.'.$i.'.contact_name', $contact->contact_name) }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold">Phone Number</label>
                                                <input type="text" class="form-control" name="contacts[{{ $i }}][phone_number]" value="{{ old('contacts.'.$i.'.phone_number', $contact->phone_number) }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold">Relationship</label>
                                                <input type="text" class="form-control" name="contacts[{{ $i }}][relationship]" value="{{ old('contacts.'.$i.'.relationship', $contact->relationship) }}">
                                            </div>
                                        </div>
                                        @if($i > 0)
                                        <button type="button" class="btn btn-danger btn-sm remove-contact position-absolute top-0 end-0 m-2"><i class="fas fa-times"></i></button>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-outline-primary" id="add-contact-btn"><i class="fas fa-plus"></i> Add Contact</button>
                            </div>
                            <!-- Contact block template (hidden) -->
                            <template id="contact-template">
                                <div class="contact-block card mb-3 p-3 position-relative">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Contact Type</label>
                                            <select class="form-select" name="__NAME__[contact_type]">
                                                <option value="">Select Contact Type</option>
                                                @foreach(config('constants.CONTACT_TYPES') as $key => $label)
                                                    <option value="{{ $key }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Contact Name</label>
                                            <input type="text" class="form-control" name="__NAME__[contact_name]">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Phone Number</label>
                                            <input type="text" class="form-control" name="__NAME__[phone_number]">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Relationship</label>
                                            <input type="text" class="form-control" name="__NAME__[relationship]">
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-danger btn-sm remove-contact position-absolute top-0 end-0 m-2"><i class="fas fa-times"></i></button>
                                </div>
                            </template>
                            <!-- Profile Tab -->
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="birth_date" class="form-label fw-semibold">Birth Date</label>
                                        <input type="date" class="form-control" id="birth_date" name="birth_date" value="{{ old('birth_date', $user->profile && $user->profile->birth_date ? (is_a($user->profile->birth_date, 'Carbon\\Carbon') ? $user->profile->birth_date->format('Y-m-d') : $user->profile->birth_date) : '') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="birth_time" class="form-label fw-semibold">Birth Time</label>
                                        <input type="time" class="form-control" id="birth_time" name="birth_time" value="{{ old('birth_time', $user->profile && $user->profile->birth_time ? (is_a($user->profile->birth_time, 'Carbon\\Carbon') ? $user->profile->birth_time->format('H:i') : \Illuminate\Support\Str::substr($user->profile->birth_time, 0, 5)) : '') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="birth_place" class="form-label fw-semibold">Birth Place</label>
                                        <input type="text" class="form-control" id="birth_place" name="birth_place" value="{{ old('birth_place', $user->profile && $user->profile->birth_place ? $user->profile->birth_place : '') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="marital_status" class="form-label fw-semibold">Marital Status</label>
                                        <select class="form-select" id="marital_status" name="marital_status">
                                            <option value="">Select Marital Status</option>
                                            @foreach(config('constants.MARITAL_STATUSES') as $key => $label)
                                                <option value="{{ $key }}" {{ old('marital_status', $user->profile && $user->profile->marital_status ? $user->profile->marital_status : '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="marriage_date" class="form-label fw-semibold">Marriage Date</label>
                                        <input type="date" class="form-control" id="marriage_date" name="marriage_date" value="{{ old('marriage_date', $user->profile && $user->profile->marriage_date ? (is_a($user->profile->marriage_date, 'Carbon\\Carbon') ? $user->profile->marriage_date->format('Y-m-d') : $user->profile->marriage_date) : '') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="religion" class="form-label fw-semibold">Religion</label>
                                        <input type="text" class="form-control" id="religion" name="religion" value="{{ old('religion', $user->profile && $user->profile->religion ? $user->profile->religion : '') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="caste" class="form-label fw-semibold">Caste</label>
                                        <input type="text" class="form-control" id="caste" name="caste" value="{{ old('caste', $user->profile && $user->profile->caste ? $user->profile->caste : '') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="gotra" class="form-label fw-semibold">Gotra</label>
                                        <input type="text" class="form-control" id="gotra" name="gotra" value="{{ old('gotra', $user->profile && $user->profile->gotra ? $user->profile->gotra : '') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="nakshatra" class="form-label fw-semibold">Nakshatra</label>
                                        <input type="text" class="form-control" id="nakshatra" name="nakshatra" value="{{ old('nakshatra', $user->profile && $user->profile->nakshatra ? $user->profile->nakshatra : '') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="rashi" class="form-label fw-semibold">Rashi</label>
                                        <input type="text" class="form-control" id="rashi" name="rashi" value="{{ old('rashi', $user->profile && $user->profile->rashi ? $user->profile->rashi : '') }}">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="about_me" class="form-label fw-semibold">About Me</label>
                                        <textarea class="form-control" id="about_me" name="about_me" rows="2">{{ old('about_me', $user->profile && $user->profile->about_me ? $user->profile->about_me : '') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <!-- Future Tab -->
                            <div class="tab-pane fade" id="future" role="tabpanel" aria-labelledby="future-tab">
                                <div class="alert alert-info">Add more sections here in the future without redesigning the form!</div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-warning text-white px-4">
                                <i class="fas fa-save me-2"></i>Update User
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-arrow-left me-1"></i>Back to Users
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Helper to get next address index
    function getNextAddressIndex() {
        return $('#addresses-wrapper .address-block').length;
    }

    // Add address block
    $('#add-address-btn').click(function() {
        var index = getNextAddressIndex();
        var template = document.getElementById('address-template').content.cloneNode(true);
        var html = $('<div>').append(template).html().replace(/__NAME__/g, 'addresses[' + index + ']').replace(/__INDEX__/g, index);
        $('#addresses-wrapper').append(html);
    });

    // Remove address block
    $(document).on('click', '.remove-address', function() {
        $(this).closest('.address-block').remove();
    });

    // Dynamic state/city loading for each address block
    function loadStates(countryId, stateSelect, selectedStateId) {
        stateSelect.html('<option value="">Select State</option>');
        var citySelect = stateSelect.closest('.row').find('.city-select');
        citySelect.html('<option value="">Select City</option>');
        if (countryId) {
            $.get('/api/locations/states?country_id=' + countryId, function(states) {
                var stateList = Array.isArray(states.data) ? states.data : states;
                if (Array.isArray(stateList) && stateList.length > 0) {
                    $.each(stateList, function(i, state) {
                        var selected = selectedStateId == state.id ? 'selected' : '';
                        stateSelect.append('<option value="' + state.id + '" ' + selected + '>' + state.name + '</option>');
                    });
                } else {
                    stateSelect.append('<option value="">No states found</option>');
                }
            }).fail(function() {
                stateSelect.append('<option value="">Error loading states</option>');
            });
        }
    }
    function loadCities(countryId, stateId, citySelect, selectedCityId) {
        citySelect.html('<option value="">Select City</option>');
        if (countryId && stateId) {
            $.get('/api/locations/cities?country_id=' + countryId + '&state_id=' + stateId, function(cities) {
                var cityList = Array.isArray(cities.data) ? cities.data : cities;
                if (Array.isArray(cityList) && cityList.length > 0) {
                    $.each(cityList, function(i, city) {
                        var selected = selectedCityId == city.id ? 'selected' : '';
                        citySelect.append('<option value="' + city.id + '" ' + selected + '>' + city.name + '</option>');
                    });
                } else {
                    citySelect.append('<option value="">No cities found</option>');
                }
            }).fail(function() {
                citySelect.append('<option value="">Error loading cities</option>');
            });
        }
    }
    // On country change in any address block
    $(document).on('change', '.country-select', function() {
        var countryId = $(this).val();
        var stateSelect = $(this).closest('.row').find('.state-select');
        loadStates(countryId, stateSelect, null);
    });
    // On state change in any address block
    $(document).on('change', '.state-select', function() {
        var stateId = $(this).val();
        var countryId = $(this).closest('.row').find('.country-select').val();
        var citySelect = $(this).closest('.row').find('.city-select');
        loadCities(countryId, stateId, citySelect, null);
    });
    // On page load, initialize all address blocks
    $('#addresses-wrapper .address-block').each(function() {
        var block = $(this);
        var countryId = block.find('.country-select').val();
        var stateSelect = block.find('.state-select');
        var selectedStateId = stateSelect.find('option[selected]').val();
        if (countryId) {
            loadStates(countryId, stateSelect, selectedStateId);
        }
        var stateId = stateSelect.val();
        var citySelect = block.find('.city-select');
        var selectedCityId = citySelect.find('option[selected]').val();
        if (countryId && stateId) {
            loadCities(countryId, stateId, citySelect, selectedCityId);
        }
    });

    // Helper to get next contact index
    function getNextContactIndex() {
        return $('#contacts-wrapper .contact-block').length;
    }
    // Add contact block
    $('#add-contact-btn').click(function() {
        var index = getNextContactIndex();
        var template = document.getElementById('contact-template').content.cloneNode(true);
        var html = $('<div>').append(template).html().replace(/__NAME__/g, 'contacts[' + index + ']');
        $('#contacts-wrapper').append(html);
    });
    // Remove contact block
    $(document).on('click', '.remove-contact', function() {
        $(this).closest('.contact-block').remove();
    });
});
</script>
@endpush
