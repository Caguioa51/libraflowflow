@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="card-title mb-4">{{ $book->title }}</h2>

                    <div class="row mb-4">
                        <div class="col-sm-3"><strong>Author:</strong></div>
                        <div class="col-sm-9">{{ $book->author->name ?? 'Unknown Author' }}</div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-sm-3"><strong>Category:</strong></div>
                        <div class="col-sm-9">{{ $book->category->name ?? 'Uncategorized' }}</div>
                    </div>

                    @if($book->genre)
                    <div class="row mb-4">
                        <div class="col-sm-3"><strong>Genre:</strong></div>
                        <div class="col-sm-9">{{ $book->genre }}</div>
                    </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-sm-3"><strong>Availability:</strong></div>
                        <div class="col-sm-9">
                            @if($book->available_quantity > 0)
                                <span class="badge bg-success fs-6">
                                    <i class="bi bi-check-circle me-1"></i>{{ $book->available_quantity }} Available
                                </span>
                            @else
                                <span class="badge bg-danger fs-6">
                                    <i class="bi bi-x-circle me-1"></i>Not Available
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($book->location)
                    <div class="row mb-4">
                        <div class="col-sm-3"><strong>Location:</strong></div>
                        <div class="col-sm-9">{{ $book->location }}</div>
                    </div>
                    @endif

                    @if($book->description)
                    <div class="row mb-4">
                        <div class="col-sm-3"><strong>Description:</strong></div>
                        <div class="col-sm-9">{{ $book->description }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Reservation Section -->
            @if(!auth()->user()->isAdmin())
                @php
                    $existingReservation = \App\Models\BookReservation::where('user_id', auth()->id())
                        ->where('book_id', $book->id)
                        ->where('status', 'active')
                        ->first();
                @endphp

                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-bookmark-plus me-2"></i>Book Reservation</h5>
                    </div>
                    <div class="card-body">
                        @if($existingReservation)
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                You have already reserved this book. You will be notified when it becomes available.
                            </div>
                            <form method="POST" action="{{ route('books.cancel_reservation', $book) }}" onsubmit="return confirm('Are you sure you want to cancel this reservation?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="bi bi-x-circle me-1"></i>Cancel Reservation
                                </button>
                            </form>
                        @elseif($book->available_quantity > 0)
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle me-2"></i>
                                This book is currently available!
                            </div>
                            <a href="{{ route('borrowings.self_checkout') }}" class="btn btn-success w-100">
                                <i class="bi bi-cart-check me-1"></i>Borrow Now
                            </a>
                        @else
                            <div class="alert alert-warning">
                                <i class="bi bi-clock me-2"></i>
                                This book is currently unavailable, but you can reserve it.
                            </div>
                            <form method="POST" action="{{ route('books.reserve', $book) }}">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-bookmark-plus me-1"></i>Reserve Book
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Book Statistics -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Book Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h4 class="text-primary mb-1">{{ $book->quantity }}</h4>
                            <small class="text-muted">Total Copies</small>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success mb-1">{{ $book->available_quantity }}</h4>
                            <small class="text-muted">Available</small>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <h6 class="mb-1">{{ $book->borrowings->count() }}</h6>
                        <small class="text-muted">Times Borrowed</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
