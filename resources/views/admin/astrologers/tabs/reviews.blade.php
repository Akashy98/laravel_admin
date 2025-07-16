<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3">Reviews</h5>
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Rating</th>
                            <th>Review</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($astrologer->reviews as $review)
                            <tr>
                                <td>{{ $review->user->name ?? '-' }}</td>
                                <td>{{ $review->rating }}</td>
                                <td>{{ $review->review }}</td>
                                <td>{{ $review->created_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
