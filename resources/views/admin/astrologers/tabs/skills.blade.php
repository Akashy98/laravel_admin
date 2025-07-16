<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3">Skills</h5>
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Skill Category</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($astrologer->skills as $skill)
                            <tr>
                                <td>{{ $skill->category->name ?? '-' }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.astrologers.skills.destroy', [$astrologer->id, $skill->id]) }}" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr>
                <form method="POST" action="{{ route('admin.astrologers.skills.store', $astrologer->id) }}">
                    @csrf
                    <div class="row g-2 align-items-end">
                        <div class="col-md-8">
                            <label class="form-label mb-1">Add Skill Category</label>
                            <select name="category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                @foreach($categories->where('is_active', true) as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">Add Skill</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
