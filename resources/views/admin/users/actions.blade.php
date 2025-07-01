<div class="btn-group" role="group">
    <a href="{{ route('admin.users.edit', $user->id) }}"
       class="btn btn-sm btn-outline-primary"
       data-bs-toggle="tooltip"
       title="Edit User">
        <i class="fas fa-edit"></i>
    </a>

    @if($user->id !== auth()->id())
        <form action="{{ route('admin.users.destroy', $user->id) }}"
              method="POST"
              style="display:inline-block;"
              onsubmit="return confirm('Are you sure you want to delete this user?');">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="btn btn-sm btn-outline-danger"
                    data-bs-toggle="tooltip"
                    title="Delete User">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    @endif

    <a href="#"
       class="btn btn-sm btn-outline-info"
       data-bs-toggle="tooltip"
       title="View Details"
       onclick="viewUserDetails({{ $user->id }})">
        <i class="fas fa-eye"></i>
    </a>
</div>
