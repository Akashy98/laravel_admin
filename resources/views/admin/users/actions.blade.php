<div class="btn-group" role="group">
    <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-outline-info me-1" data-bs-toggle="tooltip" title="View Customer">
        <i class="fas fa-eye"></i>
    </a>
    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="tooltip" title="Edit Customer">
        <i class="fas fa-edit"></i>
    </a>
    @if($user->id !== Auth::id())
        @if($user->trashed())
            <form action="{{ route('admin.users.forceDestroy', $user->id) }}" method="POST" style="display:inline-block;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger me-1" data-bs-toggle="tooltip" title="Permanently Delete Customer" onclick="return confirm('Are you sure you want to permanently delete this customer? This cannot be undone.')">
                    <i class="fas fa-skull-crossbones"></i> Force Delete
                </button>
            </form>
        @else
            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger me-1" data-bs-toggle="tooltip" title="Delete Customer" onclick="return confirm('Are you sure you want to delete this customer?')">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
            @if($user->trashed())
                <form action="{{ route('admin.users.forceDestroy', $user->id) }}" method="POST" style="display:inline-block;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger me-1" data-bs-toggle="tooltip" title="Permanently Delete Customer" onclick="return confirm('Are you sure you want to permanently delete this customer? This cannot be undone.')">
                        <i class="fas fa-skull-crossbones"></i> Force Delete
                    </button>
                </form>
            @endif
        @endif
    @endif
</div>
