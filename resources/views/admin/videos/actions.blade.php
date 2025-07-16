<a href="{{ route('admin.videos.edit', $video->id) }}" class="btn btn-sm btn-primary me-1">
    <i class="fas fa-edit"></i>
</a>
<form action="{{ route('admin.videos.destroy', $video->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this video?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger">
        <i class="fas fa-trash-alt"></i>
    </button>
</form>
