@extends('layouts.app')

@section('content')
<div class="py-4">
    <div class="p-5 mb-4 bg-primary text-white rounded-3 shadow-sm">
        <div class="container-fluid py-2">
            <h1 class="display-5 fw-bold">Welcome, {{ auth()->user()->name }}!</h1>
            <p class="col-md-8 fs-4">This is the Dagupan City National Highschool Library Management System. Manage your books, borrowing, and more with ease.</p>
        </div>
    </div>
    <div class="row mb-4 align-items-stretch">
        <div class="col-md-8">
            <div class="card mb-4 h-100">
                <div class="card-header bg-warning text-dark"><i class="bi bi-megaphone"></i> Library Announcements</div>
                <div class="card-body py-3">
                    @php
                        $announcement = \App\Models\SystemSetting::get('library_announcement', null);
                    @endphp
                    @if($announcement)
                        <div class="alert alert-info mb-0">{!! nl2br(e($announcement)) !!}</div>
                    @else
                        <ul class="mb-0">
                            <li>📚 <b>New Arrivals:</b> Check out the latest books in our collection!</li>
                            <li>⏰ <b>Reminder:</b> Please return books on time to avoid overdue penalties.</li>
                            <li>🗓️ <b>Event:</b> Book Fair next Friday in the library hall.</li>
                        </ul>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4 h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <img src="https://yt3.googleusercontent.com/ytc/AIdro_lkEzByQWiP7aN8FsnOE0YDcDAAYka5o4WkmHWJgbmldw=s900-c-k-c0x00ffffff-no-rj" alt="School Logo" width="60" height="60" class="me-3 rounded-circle shadow">
                        <div class="text-start">
                            <h6 class="card-title mb-1 fw-bold">Dagupan City National Highschool</h6>
                            <p class="card-text small mb-0">Empowering students and teachers through reading.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->isAdmin())
    <div class="row mb-4 align-items-stretch">
        <div class="col-md-8">
            <div class="card mb-4 h-100">
                <div class="card-header bg-primary text-white"><i class="bi bi-bar-chart-line"></i> Analytics Dashboard</div>
                <div class="card-body">
                    <p class="card-text">View comprehensive analytics, trends, and insights for the library.</p>
                    <a href="{{ route('borrowings.report') }}" class="btn btn-primary btn-sm">Basic Reports</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4 h-100">
                <div class="card-header bg-success text-white"><i class="bi bi-info-circle"></i> Need Help?</div>
                <div class="card-body">
                    <p>Contact the librarian at <a href="mailto:librarian@dagupancnhs.edu.ph">librarian@dagupancnhs.edu.ph</a> or visit the library office during school hours.</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(auth()->user()->isStudent() || auth()->user()->isTeacher())
    <div class="row mb-4 align-items-stretch">
        <div class="col-md-8">
            <div class="card mb-4 h-100">
                <div class="card-header bg-info text-white"><i class="bi bi-exclamation-circle"></i> My Borrowing Status</div>
                <div class="card-body">
                    <p><b>Borrowing Limit:</b> {{ auth()->user()->isStudent() ? 3 : 5 }} books at a time.</p>
                    @php
                        $myBorrowings = \App\Models\Borrowing::where('user_id', auth()->id())->where('status', 'borrowed')->get();
                        $overdue = $myBorrowings->filter(fn($b) => $b->isOverdue());
                        $dueSoon = $myBorrowings->filter(fn($b) => $b->due_date && $b->due_date->diffInDays(now()) <= 3 && !$b->isOverdue());
                        $totalFine = $myBorrowings->sum(fn($b) => $b->calculateFine());
                    @endphp
                    <p><b>Currently Borrowed:</b> {{ $myBorrowings->count() }}</p>
                    @if($overdue->count())
                        <div class="alert alert-danger"><b>Overdue Books:</b>
                            <ul class="mb-0">
                                @foreach($overdue as $b)
                                    <li>{{ $b->book->title }} (Due: {{ $b->due_date->format('M d, Y') }}) - Fine: ₱{{ number_format($b->calculateFine(), 2) }}</li>
                                @endforeach
                            </ul>
                            <div class="mt-2"><b>Total Fine:</b> ₱{{ number_format($totalFine, 2) }}</div>
                        </div>
                    @endif
                    @if($dueSoon->count())
                        <div class="alert alert-warning"><b>Books Due Soon:</b>
                            <ul class="mb-0">
                                @foreach($dueSoon as $b)
                                    <li>{{ $b->book->title }} (Due: {{ $b->due_date->format('M d, Y') }})</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mt-3">
                        <a href="{{ route('borrowings.my_history') }}" class="btn btn-outline-primary">
                            <i class="bi bi-clock-history me-2"></i>View My Complete Borrowing History
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4 h-100">
                <div class="card-header bg-success text-white"><i class="bi bi-info-circle"></i> Need Help?</div>
                <div class="card-body">
                    <p>Contact the librarian at <a href="mailto:librarian@libraflow.com">librarian@libraflow.com</a> or visit the library office during school hours.</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card text-center h-100 border-3 border-primary shadow-lg">
                <div class="card-body p-4">
                    <div class="mb-3"><i class="bi bi-book-half fs-1 text-primary"></i></div>
                    <h5 class="card-title text-primary">Library Books</h5>
                    <p class="card-text fs-3 fw-bold text-primary">{{ \App\Models\Book::count() }}</p>
                    <a href="{{ route('books.index') }}" class="btn btn-primary btn-sm">Browse & Borrow</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center h-100 border-3 border-success shadow-lg">
                <div class="card-body p-4">
                    <div class="mb-3"><i class="bi bi-tags fs-1 text-success"></i></div>
                    <h5 class="card-title text-success">Categories</h5>
                    <p class="card-text fs-3 fw-bold text-success">{{ \App\Models\Category::count() }}</p>
                    <a href="{{ route('categories.index') }}" class="btn btn-success btn-sm">View Categories</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center h-100 border-3 border-warning shadow-lg">
                <div class="card-body p-4">
                    <div class="mb-3"><i class="bi bi-person-lines-fill fs-1 text-warning"></i></div>
                    <h5 class="card-title text-warning">Authors</h5>
                    <p class="card-text fs-3 fw-bold text-warning">{{ \App\Models\Author::count() }}</p>
                    <a href="{{ route('authors.index') }}" class="btn btn-warning btn-sm">View Authors</a>
                </div>
            </div>
        </div>
    </div>


</div>
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
@endsection
