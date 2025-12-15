@extends('layouts.app')

@section('content')

{{-- Prevent caching issues --}}
@php
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
@endphp
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="display-5 fw-bold text-primary mb-2">
                        <i class="bi bi-book me-3"></i>Library Books
                    </h1>
                    <p class="text-muted lead">Browse and borrow from our collection</p>
                </div>
                <div class="d-flex gap-2">
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('books.create') }}" class="btn btn-success btn-lg">
                            <i class="bi bi-plus-circle me-2"></i>Add New Book
                        </a>
                    @endif
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-lg">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Error!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    @endif

    <!-- Enhanced Book Deletion Notification -->
    @if(session('book_deleted'))
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="bi bi-trash-fill me-2"></i>
                    <strong>Book Deleted!</strong> {{ session('book_deleted') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    @endif

    <!-- Enhanced Search and Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('books.index') }}" id="filterForm">
                        <div class="row g-3 align-items-end">
                            <div class="col-lg-6">
                                <label for="searchInput" class="form-label fw-semibold">
                                    <i class="bi bi-search me-2 text-primary"></i>Search Books
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-search text-muted"></i>
                                    </span>
                                    <input type="text" id="searchInput" name="search" class="form-control border-start-0"
                                           placeholder="Search by title, author, or category..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label for="categoryFilter" class="form-label fw-semibold">
                                    <i class="bi bi-filter me-2 text-primary"></i>Category
                                </label>
                                <select id="categoryFilter" name="category_id" class="form-select form-select-lg">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }} ({{ $category->books_count }} books)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="bi bi-search me-2"></i>Search
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body text-center">
                    <i class="bi bi-book display-4 mb-2"></i>
                    <h3 class="mb-1">{{ $books->total() }}</h3>
                    <p class="mb-0 opacity-75">Total Books</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle display-4 mb-2"></i>
                    <h3 class="mb-1">{{ $books->where('status', 'available')->count() }}</h3>
                    <p class="mb-0 opacity-75">Available</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-white">
                <div class="card-body text-center">
                    <i class="bi bi-clock display-4 mb-2"></i>
                    <h3 class="mb-1">{{ $books->where('status', 'borrowed')->count() }}</h3>
                    <p class="mb-0 opacity-75">Borrowed</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body text-center">
                    <i class="bi bi-grid display-4 mb-2"></i>
                    <h3 class="mb-1">{{ $categories->count() }}</h3>
                    <p class="mb-0 opacity-75">Categories</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Books Grid -->
    <div id="booksGrid" class="row">
        @forelse($books as $book)
            @if($book->author && $book->category)
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4 book-card"
                     data-title="{{ strtolower($book->title) }}"
                     data-author="{{ strtolower($book->author->name) }}"
                     data-category="{{ strtolower($book->category->name) }}">
                    <div class="card h-100 border-0 shadow-sm hover-lift">
                        <!-- Book Cover -->
                        <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                            <div class="text-center">
                                <i class="bi bi-book text-primary" style="font-size: 4rem;"></i>
                                <p class="text-muted small mt-2 mb-0">No Cover</p>
                            </div>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <!-- Book Title -->
                            <h5 class="card-title text-truncate mb-2" title="{{ $book->title }}">
                                {{ $book->title }}
                            </h5>

                            <!-- Book Info -->
                            <div class="book-info mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-person text-muted me-2"></i>
                                    <span class="text-truncate">{{ $book->author->name }}</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-tag text-muted me-2"></i>
                                    <span class="badge bg-light text-dark">{{ $book->category->name }}</span>
                                </div>
                                @if($book->location)
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-geo-alt text-muted me-2"></i>
                                        <small class="text-muted">{{ $book->location }}</small>
                                    </div>
                                @endif
                            </div>

                            <!-- Availability Status -->
                            <div class="availability-status mb-3">
                                @if($book->status === 'available')
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Available
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="bi bi-x-circle me-1"></i>Borrowed
                                    </span>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-auto">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('books.show', $book) }}" class="btn btn-outline-info flex-fill">
                                        <i class="bi bi-eye me-1"></i>View
                                    </a>
                                    @if($book->status === 'available' && !auth()->user()->isAdmin())
                                        <form method="POST" action="{{ route('borrowings.store') }}" class="borrow-form">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                                            <button type="button" class="btn btn-primary w-100" onclick="handleBorrowClick(this.form, '{{ addslashes($book->title) }}', '{{ addslashes($book->author->name) }}')">
                                                <i class="bi bi-cart-plus me-1"></i>Borrow
                                            </button>
                                        </form>
                                    @elseif($book->status === 'borrowed' && !auth()->user()->isAdmin())
                                        <button class="btn btn-outline-secondary flex-fill" disabled>
                                            <i class="bi bi-dash-circle me-1"></i>Borrowed
                                        </button>
                                    @endif
                                </div>

                                @if(auth()->user()->isAdmin())
                                    <div class="d-flex gap-2 mt-2">
                                        <a href="{{ route('books.edit', $book) }}" class="btn btn-outline-warning btn-sm flex-fill">
                                            <i class="bi bi-pencil me-1"></i>Edit
                                        </a>
                                        <form action="{{ route('books.destroy', $book) }}" method="POST" class="flex-fill" onsubmit="return confirmBookDeletion(this, '{{ addslashes($book->title) }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                                <i class="bi bi-trash me-1"></i>Delete
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="empty-state">
                        <i class="bi bi-book display-1 text-muted mb-4"></i>
                        <h3 class="text-muted">No Books Found</h3>
                        <p class="text-muted mb-4">No books match your search criteria or no books are available.</p>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('books.create') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-plus-circle me-2"></i>Add First Book
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($books->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $books->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    @endif

    <!-- Loading Indicator -->
    <div id="loadingIndicator" class="text-center d-none">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2 text-muted">Searching books...</p>
    </div>
</div>



<script>
// Handle borrow button click - shows confirmation modal
function handleBorrowClick(form, bookTitle, bookAuthor) {
    // Create a custom modal-like confirmation
    const modal = createCustomModal(bookTitle, bookAuthor);

    // Show the modal
    modal.style.display = 'flex';

    // Handle confirm button
    const confirmBtn = modal.querySelector('.confirm-borrow-btn');
    const cancelBtn = modal.querySelector('.cancel-borrow-btn');

    confirmBtn.onclick = () => {
        modal.remove();
        showLoadingState(form);
        form.submit();
    };

    cancelBtn.onclick = () => {
        modal.remove();
    };

    // Close modal when clicking outside
    modal.onclick = (e) => {
        if (e.target === modal) {
            modal.remove();
        }
    };
}

// Create a custom modal for better UX
function createCustomModal(bookTitle, bookAuthor) {
    const modal = document.createElement('div');
    modal.className = 'custom-borrow-modal';
    modal.innerHTML = `
        <div class="modal-backdrop-custom">
            <div class="modal-content-custom">
                <div class="modal-header-custom">
                    <i class="bi bi-question-circle text-warning me-2"></i>
                    <h5>Confirm Book Borrowing</h5>
                </div>
                <div class="modal-body-custom">
                    <div class="text-center mb-3">
                        <i class="bi bi-book text-primary modal-book-icon"></i>
                    </div>
                    <h6 class="book-title-confirm">"${bookTitle}"</h6>
                    <p class="book-author-confirm">by ${bookAuthor}</p>
                    <div class="alert alert-info-custom">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Important:</strong><br>
                        <small>Please ensure you can return this book by the due date. Late returns may incur fines.</small>
                    </div>
                </div>
                <div class="modal-footer-custom">
                    <button class="btn btn-secondary-custom cancel-borrow-btn">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </button>
                    <button class="btn btn-primary-custom confirm-borrow-btn">
                        <i class="bi bi-check-circle me-2"></i>Confirm Borrow
                    </button>
                </div>
            </div>
        </div>
    `;

    // Add styles for the custom modal
    addModalStyles();
    document.body.appendChild(modal);
    return modal;
}

// Show loading state on button
function showLoadingState(form) {
    const button = form.querySelector('button[type="button"]');
    if (button) {
        button.innerHTML = '<i class="bi bi-hourglass-split me-2"></i><span class="spinner-border spinner-border-sm me-2"></span>Borrowing...';
        button.disabled = true;
        button.classList.add('borrowing');
    }
}

// Enhanced confirmation for book deletion
function confirmBookDeletion(form, bookTitle) {
    // Create a custom confirmation modal
    const modal = document.createElement('div');
    modal.className = 'custom-confirm-modal';
    modal.innerHTML = `
        <div class="modal-overlay">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirm Book Deletion</h5>
                    <button type="button" class="btn-close btn-close-white" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="bi bi-exclamation-triangle display-4 text-danger mb-3"></i>
                        <h4 class="text-danger">Delete Book?</h4>
                        <p class="lead">"${bookTitle}"</p>
                        <p class="text-muted mt-3">
                            <strong>Warning:</strong> This action cannot be undone. All borrowing records and reservations for this book will also be deleted.
                        </p>
                    </div>
                    <div class="alert alert-warning">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Important:</strong> Please ensure this book is no longer needed before deleting.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary cancel-btn">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-danger confirm-btn">
                        <i class="bi bi-trash me-2"></i>Delete Permanently
                    </button>
                </div>
            </div>
        </div>
    `;

    // Add modal styles
    const style = document.createElement('style');
    style.textContent = `
        .custom-confirm-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1050;
            animation: fadeIn 0.2s ease;
        }

        .custom-confirm-modal .modal-overlay {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .custom-confirm-modal .modal-content {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 100%;
            animation: slideUp 0.3s ease;
            overflow: hidden;
        }

        .custom-confirm-modal .modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid #eee;
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .custom-confirm-modal .modal-title {
            margin: 0;
            font-weight: 600;
        }

        .custom-confirm-modal .btn-close-white {
            filter: invert(1);
            opacity: 0.8;
        }

        .custom-confirm-modal .modal-body {
            padding: 24px;
            text-align: center;
        }

        .custom-confirm-modal .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid #eee;
            display: flex;
            gap: 12px;
            justify-content: center;
            border-bottom-left-radius: 16px;
            border-bottom-right-radius: 16px;
        }

        .custom-confirm-modal .cancel-btn {
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .custom-confirm-modal .confirm-btn {
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .custom-confirm-modal .confirm-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
    `;

    document.head.appendChild(style);
    document.body.appendChild(modal);

    // Get modal elements
    const closeBtn = modal.querySelector('.btn-close-white');
    const cancelBtn = modal.querySelector('.cancel-btn');
    const confirmBtn = modal.querySelector('.confirm-btn');

    // Handle close button
    closeBtn.onclick = () => {
        modal.remove();
    };

    // Handle cancel button
    cancelBtn.onclick = () => {
        modal.remove();
    };

    // Handle confirm button
    confirmBtn.onclick = () => {
        // Show loading state on the delete button
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Deleting...';
            submitBtn.disabled = true;
        }

        // Submit the form
        form.submit();
    };

    // Close modal when clicking outside
    modal.onclick = (e) => {
        if (e.target === modal) {
            modal.remove();
        }
    };

    // Prevent form submission (we'll handle it in the confirm button)
    return false;
}

// Initialize page functionality
document.addEventListener('DOMContentLoaded', function() {
    initializeBookCards();
    addStyles();
});

// Initialize book card hover effects
function initializeBookCards() {
    const bookCards = document.querySelectorAll('.book-card');
    bookCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.classList.add('shadow-lg');
        });

        card.addEventListener('mouseleave', function() {
            this.classList.remove('shadow-lg');
        });
    });
}

// Add custom styles and modal styles
function addStyles() {
    const style = document.createElement('style');
    style.textContent = `
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .card {
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Custom Modal Styles */
        .custom-borrow-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            z-index: 1055;
            animation: fadeIn 0.3s ease;
        }

        .modal-backdrop-custom {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-content-custom {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 100%;
            animation: slideIn 0.3s ease;
            overflow: hidden;
        }

        .modal-header-custom {
            padding: 24px 24px 0;
            text-align: center;
            color: #2c3e50;
            font-weight: 600;
        }

        .modal-body-custom {
            padding: 24px;
            text-align: center;
        }

        .modal-book-icon {
            font-size: 3rem;
            color: #007bff;
            margin-bottom: 16px;
        }

        .book-title-confirm {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .book-author-confirm {
            font-size: 1rem;
            color: #6c757d;
            margin-bottom: 16px;
        }

        .alert-info-custom {
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            border: none;
            border-radius: 12px;
            padding: 16px;
            color: #0d47a1;
            text-align: left;
        }

        .modal-footer-custom {
            padding: 16px 24px 24px;
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 123, 255, 0.4);
        }

        .btn-secondary-custom {
            background: #6c757d;
            border: none;
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary-custom:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        /* Enhanced Card Styles */
        .card {
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .card-img-top {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            transition: all 0.3s ease;
        }

        .card:hover .card-img-top {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }

        .card:hover .card-img-top i {
            color: white !important;
            transform: scale(1.1);
        }

        .card-body {
            padding: 24px;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 12px;
        }

        .badge {
            font-size: 0.75rem;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
        }

        /* Button Enhancements */
        .btn {
            border-radius: 10px;
            font-weight: 600;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }

        .btn-outline-info {
            border: 2px solid #17a2b8;
            color: #17a2b8;
        }

        .btn-outline-info:hover {
            background: linear-gradient(135deg, #17a2b8, #138496);
            border-color: #138496;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(23, 162, 184, 0.3);
        }

        /* Loading Animation */
        .borrowing {
            position: relative;
            color: transparent !important;
        }

        .borrowing .spinner-border {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-50px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Statistics Cards Enhancement */
        .bg-primary, .bg-success, .bg-warning, .bg-info {
            background: linear-gradient(135deg, var(--bs-primary), #0056b3) !important;
            transition: all 0.3s ease;
        }

        .bg-primary:hover, .bg-success:hover, .bg-warning:hover, .bg-info:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        }

        /* Search Enhancement */
        .input-group-text {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef) !important;
            border: 2px solid #e9ecef;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        /* Empty State Enhancement */
        .empty-state {
            padding: 4rem 2rem;
        }

        .empty-state i {
            opacity: 0.5;
            transition: all 0.3s ease;
        }

        .empty-state:hover i {
            opacity: 1;
            transform: scale(1.1);
        }
    `;
    document.head.appendChild(style);
}

// Add modal styles function
function addModalStyles() {
    // Modal styles are now included in addStyles()
    return true;
}
</script>
@endsection
