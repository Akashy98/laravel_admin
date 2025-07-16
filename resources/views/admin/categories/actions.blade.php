<div class="btn-group" role="group">
    <a href="{{ route('admin.categories.show', $category->id) }}" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="View Category">
        <i class="fas fa-eye"></i>
    </a>
    <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit Category">
        <i class="fas fa-edit"></i>
    </a>
    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display:inline-block;">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete Category" onclick="return confirm('Are you sure you want to delete this category?')">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</div>
