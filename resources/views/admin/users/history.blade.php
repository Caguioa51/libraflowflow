@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2><i class="bi bi-clock-history me-2"></i>Borrowing History</h2>
                    <p class="text-muted mb-0">Viewing borrowing history for: <strong>{{ $user->name }}</strong></p>
                </div>
                <div>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Users
                    </a>
                    <a href="{{ route('admin.users.borrow_for_user', $user->id) }}" class="btn btn-success">
                        <i class="bi bi-book me-2"></i>Borrow Books
                    </a>
                </div>
            </div>

            <!-- User Information Card -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>User Information</h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center">
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/profile_photos/' . $user->profile_photo) }}" alt="Profile" class="rounded-circle mb-2" width="80" height="80">
                            @else
                                <div class="bg-secondary rounded-circle mb-2 d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px;">
                                    <i class="bi bi-person text-white fs-2"></i>
                                </div>
                            @endif
                            <h6 class="mb-0">{{ $user->name }}</h6>
                            <small class="text-muted">{{ ucfirst($user->role) }}</small>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="mb-1"><strong>Email:</strong></p>
                                    <p class="mb-3">{{ $user->email }}</p>
                                </div>
                                <div class="col-sm-4">
                                    <p class="mb-1"><strong>Student ID:</strong></p>
                                    <p class="mb-3">{{ $user->student_id ?? 'N/A' }}</p>
                                </div>
                                <div class="col-sm-4">
                                    <p class="mb-1"><strong>Barcode:</strong></p>
                                    <p class="mb-3">
                                        @if($user->barcode)
                                            <span class="badge bg-success">{{ $user->barcode }}</span>
                                        @else
                                            <span class="badge bg-secondary">Not Assigned</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="mb-1"><strong>Total Borrowings:</strong></p>
                                    <p class="mb-3">{{ $user->borrowings->count() }}</p>
                                </div>
                                <div class="col-sm-4">
                                    <p class="mb-1"><strong>Active Borrowings:</strong></p>
                                    <p class="mb-3">{{ $user->borrowings->where('status', 'borrowed')->count() }}</p>
                                </div>
                                <div class="col-sm-4">
                                    <p class="mb-1"><strong>Overdue Books:</strong></p>
                                    <p class="mb-3">{{ $user->borrowings->where('status', 'borrowed')->where('due_date', '<', now())->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Borrowing History Table -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-table me-2"></i>Borrowing History</h5>
                </div>
                <div class="card-body">
                    @if($borrowings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Book Title</th>
                                        <th>Author</th>
                                        <th>Borrowed Date</th>
                                        <th>Due Date</th>
                                        <th>Return Date</th>
                                        <th>Status</th>
                                        <th>Fine</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($borrowings as $borrowing)
                                        <tr>
                                            <td>{{ $borrowings->firstItem() + $loop->index }}</td>
                                            <td>
                                                <strong>{{ $borrowing->book->title }}</strong>
                                                @if($borrowing->book->location)
                                                    <br><small class="text-muted">📍 {{ $borrowing->book->location }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $borrowing->book->author->name ?? 'Unknown' }}</td>
                                            <td>{{ $borrowing->borrowed_at->format('M d, Y') }}</td>
                                            <td>{{ $borrowing->due_date->format('M d, Y') }}</td>
                                            <td>
                                                @if($borrowing->returned_at)
                                                    {{ $borrowing->returned_at->format('M d, Y') }}
                                                @else
                                                    <span class="badge bg-warning">Not Returned</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($borrowing->status === 'returned')
                                                    <span class="badge bg-success">Returned</span>
                                                @elseif($borrowing->status === 'borrowed')
                                                    @if($borrowing->due_date < now())
                                                        <span class="badge bg-danger">Overdue</span>
                                                    @else
                                                        <span class="badge bg-primary">Active</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($borrowing->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($borrowing->fine_amount > 0)
                                                    <span class="text-danger">₱{{ number_format($borrowing->fine_amount, 2) }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($borrowing->status === 'borrowed')
                                                    <div class="btn-group" role="group">
                                                        <form method="POST" action="{{ route('borrowings.renew', $borrowing) }}" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-info"
                                                                    title="Renew Book" {{ $borrowing->canRenew() ? '' : 'disabled' }}>
                                                                <i class="bi bi-arrow-repeat"></i>
                                                            </button>
                                                        </form>
                                                        @if($borrowing->fine_amount > 0)
                                                            <form method="POST" action="{{ route('borrowings.pay-fine', $borrowing) }}" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-outline-warning" title="Pay Fine">
                                                                    <i class="bi bi-cash"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $borrowings->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-book fs-1 text-muted mb-3"></i>
                            <h4 class="text-muted">No borrowing history</h4>
                            <p class="text-muted">{{ $user->name }} hasn't borrowed any books yet.</p>
                            <a href="{{ route('admin.users.borrow_for_user', $user->id) }}" class="btn btn-primary">
                                <i class="bi bi-book me-2"></i>Borrow First Book
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
