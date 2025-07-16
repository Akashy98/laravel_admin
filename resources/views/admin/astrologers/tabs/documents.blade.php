<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3">Documents</h5>
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>URL</th>
                            <th>Status</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($astrologer->documents as $doc)
                            <tr>
                                <td>{{ $doc->document_type }}</td>
                                <td><a href="{{ $doc->document_url }}" target="_blank">View</a></td>
                                <td>{{ ucfirst($doc->status) }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.astrologers.documents.destroy', [$astrologer->id, $doc->id]) }}" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr>
                <form method="POST" action="{{ route('admin.astrologers.documents.store', $astrologer->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label mb-1">Type</label>
                            <input type="text" name="document_type" class="form-control" placeholder="Type (e.g. ID)">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label mb-1">File</label>
                            <input type="file" name="document_file" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label mb-1">Status</label>
                            <select name="status" class="form-control">
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Upload</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
