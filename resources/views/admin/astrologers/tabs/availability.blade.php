<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3">Availability</h5>
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($astrologer->availability as $slot)
                            <tr>
                                <td>{{ ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'][$slot->day_of_week] }}</td>
                                <td>{{ $slot->start_time }}</td>
                                <td>{{ $slot->end_time }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.astrologers.availability.destroy', [$astrologer->id, $slot->id]) }}" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr>
                <form method="POST" action="{{ route('admin.astrologers.availability.store', $astrologer->id) }}">
                    @csrf
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label mb-1">Day</label>
                            <select name="day_of_week" class="form-control">
                                @foreach(['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'] as $i => $day)
                                    <option value="{{ $i }}">{{ $day }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label mb-1">Start Time</label>
                            <input type="time" name="start_time" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label mb-1">End Time</label>
                            <input type="time" name="end_time" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">Add Slot</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
