<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3">Languages</h5>
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Language</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($astrologer->languages as $astrologerLanguage)
                            <tr>
                                <td>{{ $astrologerLanguage->language->name }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.astrologers.languages.destroy', [$astrologer->id, $astrologerLanguage->id]) }}" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr>
                <form method="POST" action="{{ route('admin.astrologers.languages.store', $astrologer->id) }}">
                    @csrf
                    <div class="row g-2 align-items-end">
                        <div class="col-md-8">
                            <label class="form-label mb-1">Add Language</label>
                            <select name="language_id" class="form-control" required>
                                <option value="">Select Language</option>
                                @foreach(\App\Models\Language::where('is_active', true)->get() as $language)
                                    <option value="{{ $language->id }}">{{ $language->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">Add Language</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
