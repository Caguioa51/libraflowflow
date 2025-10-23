@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="bi bi-tags text-primary me-2"></i>Categories</h2>
            <p class="text-muted mb-0">Browse books by category</p>
        </div>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('categories.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle me-2"></i>Add Category
            </a>
        @endif
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Categories Grid -->
    @if($categories->count() > 0)
        <div class="row">
            @foreach($categories as $category)
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title mb-0 text-primary">{{ $category->name }}</h5>
                                <span class="badge bg-primary">{{ $category->books_count }} books</span>
                            </div>

                            <p class="card-text text-muted mb-3">
                                {{ $category->description ?? 'No description available' }}
                            </p>

                            <!-- Quick Stats -->
                            <div class="row text-center mb-3">
                                <div class="col-4">
                                    <small class="text-success fw-bold">{{ $category->books()->where('status', 'available')->count() }}</small>
                                    <br><small class="text-muted">Available</small>
                                </div>
                                <div class="col-4">
                                    <small class="text-warning fw-bold">{{ $category->books()->where('status', 'borrowed')->count() }}</small>
                                    <br><small class="text-muted">Borrowed</small>
                                </div>
                                <div class="col-4">
                                    <small class="text-info fw-bold">{{ $category->books()->sum('available_quantity') }}</small>
                                    <br><small class="text-muted">Copies</small>
                                </div>
                            </div>

                            <div class="d-grid">
                                <a href="{{ route('categories.show', $category) }}" class="btn btn-primary">
                                    <i class="bi bi-eye me-2"></i>View Books ({{ $category->books_count }})
                                </a>
                            </div>
                        </div>

                        @if(auth()->user()->isAdmin())
                            <div class="card-footer bg-light">
                                <div class="btn-group w-100" role="group">
                                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-outline-warning btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this category? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $categories->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-tags fs-1 text-muted mb-3"></i>
            <h4 class="text-muted">No categories with books yet</h4>
            <p class="text-muted mb-4">Categories that contain books will appear here.</p>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('categories.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle me-2"></i>Create First Category
                </a>
            @endif
        </div>
    @endif
</div>
@endsection
