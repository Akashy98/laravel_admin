<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3">Bank Details</h5>
                <form method="POST" action="{{ route('admin.astrologers.bank-details.update', [$astrologer->id, $astrologer->bankDetails->first()->id ?? 0]) }}">
                    @csrf
                    @method('PUT')
                    <div class="row mb-2">
                        <div class="col-md-4 col-lg-3"><strong>Account Holder Name:</strong></div>
                        <div class="col-md-8 col-lg-9"><input type="text" name="account_holder_name" class="form-control" value="{{ $astrologer->bankDetails->first()->account_holder_name ?? '' }}"></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 col-lg-3"><strong>Account Number:</strong></div>
                        <div class="col-md-8 col-lg-9"><input type="text" name="account_number" class="form-control" value="{{ $astrologer->bankDetails->first()->account_number ?? '' }}"></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 col-lg-3"><strong>IFSC Code:</strong></div>
                        <div class="col-md-8 col-lg-9"><input type="text" name="ifsc_code" class="form-control" value="{{ $astrologer->bankDetails->first()->ifsc_code ?? '' }}"></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 col-lg-3"><strong>Bank Name:</strong></div>
                        <div class="col-md-8 col-lg-9"><input type="text" name="bank_name" class="form-control" value="{{ $astrologer->bankDetails->first()->bank_name ?? '' }}"></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 col-lg-3"><strong>UPI ID:</strong></div>
                        <div class="col-md-8 col-lg-9"><input type="text" name="upi_id" class="form-control" value="{{ $astrologer->bankDetails->first()->upi_id ?? '' }}"></div>
                    </div>
                    <button type="submit" class="btn btn-success">Update Bank Details</button>
                </form>
            </div>
        </div>
    </div>
</div>
