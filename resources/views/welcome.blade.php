<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | Dagupan City National Highschool Library</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
            <style>
        body { background: #f8fafc; }
        .hero { background: #003366; color: #FFD700; padding: 3rem 0; }
        .hero img { background: #fff; border-radius: 50%; padding: 8px; }
        .feature-card { min-height: 180px; }
            </style>
    </head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: #003366;">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <img src="https://yt3.googleusercontent.com/ytc/AIdro_lkEzByQWiP7aN8FsnOE0YDcDAAYka5o4WkmHWJgbmldw=s900-c-k-c0x00ffffff-no-rj" alt="School Logo" width="48" height="48" class="me-2">
                <span class="fw-bold" style="color: #FFD700;">Dagupan City National Highschool Library Management System</span>
            </a>
            <div class="ms-auto">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-light me-2">Dashboard</a>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-warning">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light me-2">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-warning">Register</a>
                @endauth
            </div>
        </div>
                </nav>
    <section class="hero text-center">
        <div class="container">
            <img src="https://yt3.googleusercontent.com/ytc/AIdro_lkEzByQWiP7aN8FsnOE0YDcDAAYka5o4WkmHWJgbmldw=s900-c-k-c0x00ffffff-no-rj" alt="School Logo" width="80" class="mb-3">
            <h1 class="display-4 fw-bold">Welcome to Dagupan City National Highschool Library Management System</h1>
            <p class="lead">Empowering students and teachers through reading, discovery, and lifelong learning.</p>
            <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="btn btn-lg btn-warning mt-3">Get Started</a>
        </div>
    </section>
    @php $announcement = \App\Models\SystemSetting::get('library_announcement', null); @endphp
    @if($announcement)
    <section class="container mb-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="alert alert-info text-center mb-0">{!! nl2br(e($announcement)) !!}</div>
            </div>
        </div>
    </section>
    @endif
    <section class="container my-5">
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card feature-card text-center shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-clock-history fs-1 text-primary"></i>
                        <h5 class="card-title mt-2">Library Hours</h5>
                        <p class="card-text">Mon-Fri: 7:30am - 4:00pm<br>Sat: 8:00am - 12:00pm<br>Sun: Closed</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card text-center shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-geo-alt fs-1 text-success"></i>
                        <h5 class="card-title mt-2">Location</h5>
                        <p class="card-text">Dagupan City National Highschool<br>123 School Lane<br>Dagupan City</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card text-center shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-book-half fs-1 text-warning"></i>
                        <h5 class="card-title mt-2">Featured Books</h5>
                        <ul class="list-unstyled mb-0">
                            @foreach(\App\Models\Book::latest()->take(3)->get() as $book)
                                <li><i class="bi bi-dot"></i> {{ $book->title }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <footer class="footer mt-5 py-3 bg-dark text-white text-center">
        &copy; {{ date('Y') }} Dagupan City National Highschool Library &mdash; Powered by LibraFlow
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
