<div>
    <h5>Wallet Balance: ₹{{ number_format($astrologer->wallet->balance ?? 0, 2) }}</h5>
    <h6>Recent Transactions</h6>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                @forelse($astrologer->wallet && $astrologer->wallet->transactions ? $astrologer->wallet->transactions : [] as $txn)
                    <tr>
                        <td>{{ $txn->created_at->format('d M Y H:i') }}</td>
                        <td>{{ ucfirst($txn->type) }}</td>
                        <td>
                            @if($txn->type == 'debit')
                                -₹{{ number_format($txn->amount, 2) }}
                            @else
                                +₹{{ number_format($txn->amount, 2) }}
                            @endif
                        </td>
                        <td>{{ $txn->description }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4">No transactions found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
