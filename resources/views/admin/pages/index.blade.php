@extends('admin.layouts.app')

@section('title', 'Pages Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-file-alt me-2"></i>Pages Management
                    </h5>
                    <div class="d-flex gap-2">
                        <form action="{{ route('admin.pages.create-defaults') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-info btn-sm" onclick="return confirm('Create default pages (Terms, Privacy, Refund)?')">
                                <i class="fas fa-plus me-1"></i>Create Default Pages
                            </button>
                        </form>
                        <a href="{{ route('admin.pages.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>Add New Page
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Slug</th>
                                    <th>Status</th>
                                    <th>Sort Order</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pages as $page)
                                    <tr>
                                        <td>
                                            <strong>{{ $page->title }}</strong>
                                            @if($page->meta_description)
                                                <br><small class="text-muted">{{ Str::limit($page->meta_description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <code>{{ $page->slug }}</code>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $page->status === 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($page->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $page->sort_order }}</td>
                                        <td>{{ $page->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('page.show', $page->slug) }}" target="_blank" class="btn btn-sm btn-outline-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.pages.toggle-status', $page) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm btn-outline-{{ $page->status === 'active' ? 'warning' : 'success' }}" title="{{ $page->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                                        <i class="fas fa-{{ $page->status === 'active' ? 'pause' : 'play' }}"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this page?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-file-alt fa-3x mb-3"></i>
                                                <h5>No pages found</h5>
                                                <p>Create your first page or generate default pages.</p>
                                                <div class="mt-3">
                                                    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary me-2">
                                                        <i class="fas fa-plus me-1"></i>Create Page
                                                    </a>
                                                    <form action="{{ route('admin.pages.create-defaults') }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-info">
                                                            <i class="fas fa-magic me-1"></i>Create Default Pages
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($pages->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $pages->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
