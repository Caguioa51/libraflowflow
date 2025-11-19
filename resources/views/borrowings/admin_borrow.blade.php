@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <!-- Header -->
            <div class="mb-4">
                <h2 class="mb-1"><i class="bi bi-person-plus text-primary me-2"></i>Borrow for Student</h2>
                <p class="text-muted mb-0">Find a student to borrow books for them</p>
            </div>

            <!-- Student Search Section -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-search me-2"></i>Find Student</h5>
                </div>
                <div class="card-body">
                    <!-- Manual Search -->
                    <div class="mb-4">
                        <h6 class="text-muted">Manual Search</h6>
                        <form method="POST" action="{{ route('borrowings.admin_borrow.post') }}" class="d-inline">
                            @csrf
                            <div class="input-group">
                                <input type="text" class="form-control" name="search_query"
                                       placeholder="Enter student name, email, or ID..." required>
                                <button class="btn btn-info" type="submit">
                                    <i class="bi bi-search me-2"></i>Find Student
                                </button>
                            </div>
                        </form>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                        </div>
                    @endif

                    @if($selectedUser)
                        <!-- Student Found - Show Borrowing Interface -->
                        <div class="card mt-4 border-success">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="bi bi-person-check me-2"></i>Student Selected</h5>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-3 text-center">
                                        <img src="{{ $selectedUser->profile_photo_url }}" alt="Profile" class="rounded-circle mb-2" width="80" height="80">
                                        <h6 class="mb-0">{{ $selectedUser->name }}</h6>
                                        <small class="text-muted">{{ ucfirst($selectedUser->role) }}</small>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <p class="mb-1"><strong>Email:</strong></p>
                                                <p class="mb-3">{{ $selectedUser->email }}</p>
                                            </div>
                                            <div class="col-sm-6">
                                                <p class="mb-1"><strong>Student ID:</strong></p>
                                                <p class="mb-3">{{ $selectedUser->student_id ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-success" id="borrow-books-btn">
                                                <i class="bi bi-book me-2"></i>Borrow Books for {{ $selectedUser->name }}
                                            </button>
                                            <a href="{{ route('borrowings.admin_borrow') }}" class="btn btn-outline-secondary">
                                                <i class="bi bi-arrow-repeat me-2"></i>Find Different Student
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Book Selection Form (Initially Hidden) -->
                        <div id="book-selection-section" class="card mt-4" style="display: none;">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="bi bi-books me-2"></i>Select Books to Borrow</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('borrowings.store') }}" id="borrow-book-form">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $selectedUser->id }}" id="borrow_user_id">

                                    <div class="row">
                                        <div class="col-md-8">
                                            <label for="book_select" class="form-label">Select Book</label>
                                            <select class="form-select" name="book_id" id="book_select" required>
                                                <option value="">Choose a book...</option>
                                                @foreach($books as $book)
                                                    <option value="{{ $book->id }}">
                                                        {{ $book->title }} by {{ $book->author->name ?? 'Unknown' }}
                                                        (Available: {{ $book->available_quantity ?? ($book->quantity ?? 1) }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="submit" class="btn btn-success d-block w-100">
                                                <i class="bi bi-check-circle me-2"></i>Borrow Book
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Instructions -->
                    <div class="alert alert-info mt-4">
                        <h6><i class="bi bi-info-circle me-2"></i>How to use:</h6>
                        <ol class="mb-0">
                            <li>Enter the student's name, email, or student ID in the search field</li>
                            <li>Click "Find Student" to search</li>
                            <li>If student is found, click "Borrow Books" to proceed</li>
                            <li>Select a book from the dropdown and click "Borrow Book"</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Borrow Books button click handler
    const borrowBtn = document.getElementById('borrow-books-btn');
    if (borrowBtn) {
        borrowBtn.addEventListener('click', function() {
            const bookSelectionSection = document.getElementById('book-selection-section');
            if (bookSelectionSection) {
                bookSelectionSection.style.display = 'block';
                bookSelectionSection.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }
});
</script>
