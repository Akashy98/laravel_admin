@extends('admin.layouts.app')

@section('title', 'Add User')
@section('page-title', 'Add User')
@section('page-subtitle', 'Create a new user')

@php
$breadcrumbs = [
    ['title' => 'Users Management', 'url' => route('admin.users.index')],
    ['title' => 'Add User']
];
@endphp

@section('content')
<div class="container-fluid py-4">
    <h3 class="mb-1 fw-bold"><i class="fas fa-user-plus me-2 text-primary"></i>Add New User</h3>
    <p class="text-muted mb-4">Fill in the details below to create a new user.</p>
    <div class="row">
        <div class="col-12 mx-2">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <ul class="nav nav-tabs mb-3" id="createUserTab" role="tablist">
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
                        </ul>
                        <div class="tab-content" id="createUserTabContent">
                            <!-- Personal Info Tab -->
                            <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label fw-semibold">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label fw-semibold">Phone</label>
                                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label fw-semibold">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="country_code" class="form-label fw-semibold">Country Code</label>
                                        <input type="text" class="form-control" id="country_code" name="country_code" value="{{ old('country_code', '+91') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="gender" class="form-label fw-semibold">Gender</label>
                                        <select class="form-select" id="gender" name="gender">
                                            <option value="">Select Gender</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="status" class="form-label fw-semibold">Status</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="profile_image" class="form-label fw-semibold">Profile Image</label>
                                        <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                                        <small class="text-muted">Max size: 10MB. Supported formats: JPG, PNG, GIF, WEBP</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label fw-semibold">Password</label>
                                        <input type="password" class="form-control" id="password" name="password">
                                    </div>
                                </div>
                            </div>
                            <!-- Address Tab -->
                            <div class="tab-pane fade" id="address" role="tabpanel" aria-labelledby="address-tab">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="address_type" class="form-label fw-semibold">Address Type</label>
                                        <select class="form-select" id="address_type" name="address_type">
                                            <option value="">Select Address Type</option>
                                            @foreach(config('constants.ADDRESS_TYPES') as $key => $label)
                                                <option value="{{ $key }}" {{ old('address_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="country_id" class="form-label fw-semibold">Country</label>
                                        @php
                                            $indiaId = null;
                                            foreach($countries as $country) {
                                                if (strtolower($country->name) === 'india') {
                                                    $indiaId = $country->id;
                                                    break;
                                                }
                                            }
                                            $selectedCountry = old('country_id', $indiaId);
                                        @endphp
                                        <select class="form-select" id="country_id" name="country_id">
                                            <option value="">Select Country</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}" {{ $selectedCountry == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="state_id" class="form-label fw-semibold">State</label>
                                        <select class="form-select" id="state_id" name="state_id">
                                            <option value="">Select State</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="city_id" class="form-label fw-semibold">City</label>
                                        <select class="form-select" id="city_id" name="city_id">
                                            <option value="">Select City</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="address" class="form-label fw-semibold">Address</label>
                                        <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="postal_code" class="form-label fw-semibold">Postal Code</label>
                                        <input type="text" class="form-control" id="postal_code" name="postal_code" value="{{ old('postal_code') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="timezone" class="form-label fw-semibold">Timezone</label>
                                        <select class="form-select" id="timezone" name="timezone">
                                            <option value="">Select Timezone</option>
                                            @foreach(config('constants.TIMEZONES') as $key => $label)
                                                <option value="{{ $key }}" {{ old('timezone') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- Contacts Tab -->
                            <div class="tab-pane fade" id="contacts" role="tabpanel" aria-labelledby="contacts-tab">
                                <div id="contacts-wrapper">
                                    <div class="contact-block card mb-3 p-3 position-relative">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold">Contact Type</label>
                                                <select class="form-select" name="contacts[0][contact_type]">
                                                    <option value="">Select Contact Type</option>
                                                    @foreach(config('constants.CONTACT_TYPES') as $key => $label)
                                                        <option value="{{ $key }}">{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold">Contact Name</label>
                                                <input type="text" class="form-control" name="contacts[0][contact_name]">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold">Phone Number</label>
                                                <input type="text" class="form-control" name="contacts[0][phone_number]">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold">Relationship</label>
                                                <input type="text" class="form-control" name="contacts[0][relationship]">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-primary" id="add-contact-btn"><i class="fas fa-plus"></i> Add Contact</button>
                            </div>
                            <!-- Contact block template (hidden) -->
                            <div id="contact-template" class="d-none">
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
                            </div>
                            <!-- Profile Tab -->
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="birth_date" class="form-label fw-semibold">Birth Date</label>
                                        <input type="date" class="form-control" id="birth_date" name="birth_date" value="{{ old('birth_date') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="birth_time" class="form-label fw-semibold">Birth Time</label>
                                        <input type="time" class="form-control" id="birth_time" name="birth_time" value="{{ old('birth_time') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="birth_place" class="form-label fw-semibold">Birth Place</label>
                                        <input type="text" class="form-control" id="birth_place" name="birth_place" value="{{ old('birth_place') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="marital_status" class="form-label fw-semibold">Marital Status</label>
                                        <select class="form-select" id="marital_status" name="marital_status">
                                            <option value="">Select Marital Status</option>
                                            @foreach(config('constants.MARITAL_STATUSES') as $key => $label)
                                                <option value="{{ $key }}" {{ old('marital_status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-plus me-2"></i>Add User
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
        function loadStates(countryId, selectedStateId) {
            $('#state_id').html('<option value="">Select State</option>');
            $('#city_id').html('<option value="">Select City</option>');
            if (countryId) {
                $.get('/api/locations/states?country_id=' + countryId, function(states) {
                    var stateList = Array.isArray(states.data) ? states.data : states;
                    if (Array.isArray(stateList) && stateList.length > 0) {
                        $.each(stateList, function(i, state) {
                            var selected = selectedStateId == state.id ? 'selected' : '';
                            $('#state_id').append('<option value="' + state.id + '" ' + selected + '>' + state.name + '</option>');
                        });
                    } else {
                        $('#state_id').append('<option value="">No states found</option>');
                    }
                }).fail(function() {
                    $('#state_id').append('<option value="">Error loading states</option>');
                });
            }
        }
        function loadCities(countryId, stateId, selectedCityId) {
            $('#city_id').html('<option value="">Select City</option>');
            if (countryId && stateId) {
                $.get('/api/locations/cities?country_id=' + countryId + '&state_id=' + stateId, function(cities) {
                    var cityList = Array.isArray(cities.data) ? cities.data : cities;
                    if (Array.isArray(cityList) && cityList.length > 0) {
                        $.each(cityList, function(i, city) {
                            var selected = selectedCityId == city.id ? 'selected' : '';
                            $('#city_id').append('<option value="' + city.id + '" ' + selected + '>' + city.name + '</option>');
                        });
                    } else {
                        $('#city_id').append('<option value="">No cities found</option>');
                    }
                }).fail(function() {
                    $('#city_id').append('<option value="">Error loading cities</option>');
                });
            }
        }
        var initialCountry = $('#country_id').val();
        var initialState = '';
        var initialCity = '';
        if (initialCountry) {
            loadStates(initialCountry, initialState);
            if (initialState) {
                loadCities(initialCountry, initialState, initialCity);
            }
        }
        $('#country_id').change(function() {
            loadStates($(this).val(), null);
        });
        $('#state_id').change(function() {
            loadCities($('#country_id').val(), $(this).val(), null);
        });
        // Helper to get next contact index
        function getNextContactIndex() {
            return $('#contacts-wrapper .contact-block').length;
        }
        // Add contact block
        $('#add-contact-btn').click(function() {
            var index = getNextContactIndex();
            var template = $('#contact-template').html();
            var namePrefix = 'contacts[' + index + ']';
            var html = template.replace(/__NAME__/g, namePrefix);
            $('#contacts-wrapper').append(html);
        });
        // Remove contact block
        $(document).on('click', '.remove-contact', function() {
            $(this).closest('.contact-block').remove();
        });
    });
</script>
@endpush
