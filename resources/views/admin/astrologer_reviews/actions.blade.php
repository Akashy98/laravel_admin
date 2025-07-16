<div class="btn-group" role="group">
    <a href="{{ route('admin.astrologer_reviews.edit', $review->id) }}" class="btn btn-sm btn-info" title="Edit">
        <i class="fas fa-edit"></i>
    </a>
    <form action="{{ route('admin.astrologer_reviews.destroy', $review->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this review?')">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</div>
