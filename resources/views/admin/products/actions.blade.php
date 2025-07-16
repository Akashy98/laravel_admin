<a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-primary me-1">
    <i class="fas fa-edit"></i>
</a>
<a href="{{ route('admin.products.gallery.index', $product->id) }}" class="btn btn-sm btn-info me-1" title="Manage Gallery">
    <i class="fas fa-images"></i>
</a>
<form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger">
        <i class="fas fa-trash-alt"></i>
    </button>
</form>
