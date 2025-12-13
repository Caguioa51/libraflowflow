@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>
                    <i class="fas fa-chart-bar text-primary"></i>
                    Library Inventory & Borrowing Report
                </h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Reports</li>
                    </ol>
                </nav>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-12 mb-3">
                    <h4 class="text-muted">
                        <i class="fas fa-info-circle text-info"></i> Library Summary
                    </h4>
                </div>

                <!-- Total Books Card -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
                    <div class="card h-100 border-primary">
                        <div class="card-body text-center">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <i class="fas fa-book text-primary fs-2 me-3"></i>
                                <div class="text-start flex-grow-1">
                                    <h6 class="card-title mb-1 text-muted">Total Books</h6>
                                    <p class="card-text fs-2 fw-bold mb-0 text-primary d-flex align-items-center" style="min-height: 2.5rem;">{{ $totalBooks }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Available Books Card -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
                    <div class="card h-100 border-success">
                        <div class="card-body text-center">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <i class="fas fa-check-circle text-success fs-2 me-3"></i>
                                <div class="text-start flex-grow-1">
                                    <h6 class="card-title mb-1 text-muted">Available Books</h6>
                                    <p class="card-text fs-2 fw-bold mb-0 text-success d-flex align-items-center" style="min-height: 2.5rem;">{{ $availableBooks }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Currently Borrowed Card -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
                    <div class="card h-100 border-warning">
                        <div class="card-body text-center">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <i class="fas fa-user-clock text-warning fs-2 me-3"></i>
                                <div class="text-start flex-grow-1">
                                    <h6 class="card-title mb-1 text-muted">Currently Borrowed</h6>
                                    <p class="card-text fs-2 fw-bold mb-0 text-warning d-flex align-items-center" style="min-height: 2.5rem;">{{ $currentlyBorrowed }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Overdue Books Card -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
                    <div class="card h-100 border-danger">
                        <div class="card-body text-center">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <i class="fas fa-exclamation-triangle text-danger fs-2 me-3"></i>
                                <div class="text-start flex-grow-1">
                                    <h6 class="card-title mb-1 text-muted">Overdue Books</h6>
                                    <p class="card-text fs-2 fw-bold mb-0 text-danger d-flex align-items-center" style="min-height: 2.5rem;">{{ $overdueBooks }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Borrowing Records Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-list me-2"></i>
                                Borrowing Records
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Book Title</th>
                                            <th>Borrower</th>
                                            <th>Borrowed Date</th>
                                            <th>Returned Date</th>
                                            <th>Status</th>
                                            <th>Duration</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($borrowings as $borrowing)
                                        <tr>
                                            <td>
                                                <strong>{{ $borrowing->book->title ?? 'Unknown Book' }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $borrowing->book->author->name ?? '' }}</small>
                                            </td>
                                            <td>{{ $borrowing->user->name ?? 'Unknown User' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($borrowing->borrowed_at)->format('M d, Y H:i') }}</td>
                                            <td>{{ $borrowing->returned_at ? \Carbon\Carbon::parse($borrowing->returned_at)->format('M d, Y H:i') : '-' }}</td>
                                            <td>
                                                @if($borrowing->status === 'borrowed')
                                                    <span class="badge bg-warning">Currently Borrowed</span>
                                                @else
                                                    <span class="badge bg-success">Returned</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $borrowedDate = \Carbon\Carbon::parse($borrowing->borrowed_at);
                                                    $endDate = $borrowing->returned_at ? \Carbon\Carbon::parse($borrowing->returned_at) : now();
                                                    $totalHours = $borrowedDate->diffInHours($endDate);
                                                    $days = intval($totalHours / 24);
                                                    $hours = $totalHours % 24;
                                                @endphp
                                                
                                                @if($totalHours < 1)
                                                    < 1 hour
                                                @elseif($days > 0)
                                                    {{ $days }} day{{ $days > 1 ? 's' : '' }}
                                                    @if($hours > 0)
                                                        {{ $hours }} hour{{ $hours > 1 ? 's' : '' }}
                                                    @endif
                                                @else
                                                    {{ $totalHours }} hour{{ $totalHours > 1 ? 's' : '' }}
                                                @endif
                                            </td>
                                            <td>
                                                @if(auth()->user()->isAdmin())
                                                    @if($borrowing->status === 'borrowed')
                                                    <form action="{{ route('borrowings.mark-as-returned', $borrowing) }}" method="POST" style="display:inline-block">
                                                        @csrf
                                                        <button class="btn btn-success btn-sm" onclick="return confirm('Mark this book as returned?')">
                                                            <i class="fas fa-check"></i> Mark as Returned
                                                        </button>
                                                    </form>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <i class="fas fa-inbox fs-1 mb-2 text-muted"></i>
                                                <br>
                                                <p class="text-muted">No borrowing records found</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            @if($borrowings->hasPages())
                            <div class="mt-3">
                                {{ $borrowings->links() }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
