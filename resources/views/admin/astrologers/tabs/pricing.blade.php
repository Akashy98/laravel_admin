<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3">Pricing</h5>

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Service Type</th>
                            <th>Price/Minute</th>
                            <th>Offer Price</th>
                            <th style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($astrologer->pricing as $price)
                            <tr>
                                <td>{{ $price->service ? $price->service->name : '-' }}</td>
                                <td>{{ $price->price_per_minute }}</td>
                                <td>{{ $price->offer_price ?? '-' }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editPricingModal{{ $price->id }}">
                                        Edit
                                    </button>
                                    <form method="POST" action="{{ route('admin.astrologers.pricing.destroy', [$astrologer->id, $price->id]) }}" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this pricing?')">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal for each pricing -->
                            <div class="modal fade" id="editPricingModal{{ $price->id }}" tabindex="-1" aria-labelledby="editPricingModalLabel{{ $price->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editPricingModalLabel{{ $price->id }}">Edit Pricing - {{ $price->service ? $price->service->name : 'Service' }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form method="POST" action="{{ route('admin.astrologers.pricing.update', [$astrologer->id, $price->id]) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Service Type</label>
                                                    <input type="text" class="form-control" value="{{ $price->service ? $price->service->name : 'Unknown' }}" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Price/Minute</label>
                                                    <input type="number" step="0.01" name="price_per_minute" class="form-control" value="{{ $price->price_per_minute }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Offer Price</label>
                                                    <input type="number" step="0.01" name="offer_price" class="form-control" value="{{ $price->offer_price }}" placeholder="Offer Price (optional)">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Update Pricing</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>

                <hr>

                @if($availableServices->count() > 0)
                    <form method="POST" action="{{ route('admin.astrologers.pricing.store', $astrologer->id) }}">
                        @csrf
                        <div class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label mb-1">Service Type</label>
                                <select name="service_id" class="form-select" required>
                                    <option value="">Select Service</option>
                                    @foreach($availableServices as $service)
                                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-1">Price/Minute</label>
                                <input type="number" step="0.01" name="price_per_minute" class="form-control" placeholder="Price/Minute" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-1">Offer Price</label>
                                <input type="number" step="0.01" name="offer_price" class="form-control" placeholder="Offer Price (optional)">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100">Add Pricing</button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="alert alert-info">
                        <strong>All services have pricing configured!</strong> You can edit existing pricing using the Edit buttons above.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
