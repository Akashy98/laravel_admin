<div class="row">
    <div class="col-lg-4 col-md-5 col-12 d-flex align-items-center justify-content-center">
        <div class="card mb-3 w-100">
            <div class="card-body d-flex flex-column align-items-center justify-content-center h-100 p-4">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($astrologer->user->name ?? '-') }}&background=667eea&color=fff" class="rounded-circle mb-3" width="96" height="96" alt="Avatar">
                <h4 class="fw-bold mb-1 text-dark">{{ $astrologer->user->name ?? '-' }}</h4>
                <div class="text-muted mb-2 small">{{ $astrologer->user->email ?? '-' }}</div>
                <span class="badge mb-2 {{
                    $astrologer->status == 'approved' ? 'bg-success' :
                    ($astrologer->status == 'pending' ? 'bg-warning text-dark' :
                    ($astrologer->status == 'blocked' ? 'bg-danger' : 'bg-secondary'))
                }}">
                    <i class="bi bi-shield-check me-1"></i> {{ ucfirst($astrologer->status) }}
                </span>
                <div class="border-top w-75 my-3"></div>
                <div class="d-flex flex-column align-items-center gap-2 w-100">
                    <span class="badge bg-primary mb-1" style="font-size: 1rem;">
                        <i class="bi bi-wifi me-1"></i>
                        {{ $astrologer->is_online ? 'Online' : 'Offline' }}
                    </span>
                    @if($astrologer->is_fake)
                        <span class="badge bg-danger mb-1" style="font-size: 1rem;">
                            <i class="bi bi-person-x me-1"></i> Fake Astrologer
                        </span>
                    @endif
                    @if($astrologer->is_test)
                        <span class="badge bg-info text-dark mb-1" style="font-size: 1rem;">
                            <i class="bi bi-flask me-1"></i> Test Astrologer
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8 col-md-7 col-12">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="mb-3">Personal Information</h5>
                <form method="POST" action="{{ route('admin.astrologers.update', $astrologer->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Profile Image</label>
                        <div class="d-flex align-items-center gap-3">
                            @if($astrologer->user->profile_image)
                                <img src="{{ $astrologer->user->profile_image }}" alt="Profile Image" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
                            @endif
                            <div class="flex-grow-1">
                                <input type="file" class="form-control" name="profile_image" accept="image/*">
                                <small class="text-muted">Leave empty to keep current image. Max size: 10MB</small>
                            </div>
                        </div>
                        @if($astrologer->user->profile_image)
                            <div class="mt-2">
                                <small class="text-muted">Current: {{ $astrologer->user->profile_image }}</small>
                            </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="pending" {{ $astrologer->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $astrologer->status == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $astrologer->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="blocked" {{ $astrologer->status == 'blocked' ? 'selected' : '' }}>Blocked</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">About</label>
                        <textarea name="about_me" class="form-control">{{ $astrologer->about_me }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Experience (years)</label>
                        <input type="number" name="experience_years" class="form-control" value="{{ $astrologer->experience_years }}">
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_online" id="is_online_edit" value="1" {{ $astrologer->is_online ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_online_edit">Online</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_fake" id="is_fake_edit" value="1" {{ $astrologer->is_fake ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_fake_edit">Fake Astrologer</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_test" id="is_test" value="1" {{ $astrologer->is_test ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_test">Test Astrologer</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
</div>
