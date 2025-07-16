@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card shadow">
                <div class="card-body">
                    <h2 class="mb-4">Create Astrologer</h2>
                    <form method="POST" action="{{ route('admin.astrologers.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="role_id" value="3">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                            <div class="col-md-6">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender">
                                    <option value="">Select</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="profile_image" class="form-label">Profile Image</label>
                                <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                                <small class="text-muted">Max size: 10MB. Supported formats: JPG, PNG, GIF, WEBP</small>
                            </div>
                            <div class="col-12">
                                <label for="about_me" class="form-label">About</label>
                                <textarea class="form-control" id="about_me" name="about_me" rows="2"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="experience_years" class="form-label">Experience (years)</label>
                                <input type="number" class="form-control" id="experience_years" name="experience_years" min="0">
                            </div>
                        </div>
                        <div class="mt-4 d-flex">
                            <button type="submit" class="btn btn-success me-2">Create Astrologer</button>
                            <a href="{{ route('admin.astrologers.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
