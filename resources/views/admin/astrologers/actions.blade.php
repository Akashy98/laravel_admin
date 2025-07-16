<div class="btn-group" role="group">
    <a href="{{ route('admin.astrologers.show', $astrologer->id) }}" class="btn btn-sm btn-outline-info me-1" data-bs-toggle="tooltip" title="View Astrologer">
        <i class="fas fa-eye"></i>
    </a>
    @if($astrologer->user && $astrologer->user->trashed())
        <form action="{{ route('admin.astrologers.forceDestroy', $astrologer->id) }}" method="POST" style="display:inline-block;">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger me-1" data-bs-toggle="tooltip" title="Permanently Delete Astrologer" onclick="return confirm('Are you sure you want to permanently delete this astrologer? This cannot be undone.')">
                <i class="fas fa-skull-crossbones"></i> Force Delete
            </button>
        </form>
    @endif
    {{-- Add more actions here if needed --}}
</div>
