<a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-sm btn-warning me-1"><i class="fas fa-edit"></i></a>
<form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this banner?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
</form>
