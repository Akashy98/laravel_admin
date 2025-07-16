<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3">Manage Services</h5>
                <form method="POST" action="{{ route('admin.astrologers.services.update', $astrologer->id) }}">
                    @csrf
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($services as $service)
                                @php
                                    $pivot = $astrologer->services->firstWhere('id', $service->id);
                                    $isEnabled = $pivot ? $pivot->pivot->is_enabled : false;
                                @endphp
                                <tr>
                                    <td>{{ $service->name }}</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="services[{{ $service->id }}]" value="1" id="service_{{ $service->id }}" {{ $isEnabled ? 'checked' : '' }}>
                                            <label class="form-check-label" for="service_{{ $service->id }}">
                                                {{ $isEnabled ? 'Enabled' : 'Disabled' }}
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Update Services</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
