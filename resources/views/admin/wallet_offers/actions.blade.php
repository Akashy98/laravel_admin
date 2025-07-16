<a href="{{ route('admin.wallet-offers.edit', $offer) }}" class="btn btn-sm btn-warning me-1"><i class="fas fa-edit"></i></a>
<form action="{{ route('admin.wallet-offers.destroy', $offer) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this offer?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
</form>
