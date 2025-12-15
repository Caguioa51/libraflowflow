<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LibraFlow') }}</title>

        <!-- PWA Meta Tags -->
        <meta name="description" content="Dagupan City National High School Library Management System">
        <meta name="theme-color" content="#4f46e5">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="LibraFlow">

        <!-- PWA Manifest -->
        <link rel="manifest" href="/manifest.json">

        <!-- Apple Touch Icons -->
        <link rel="apple-touch-icon" href="/favicon.ico">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Fallback CSS if Vite fails -->
        <style>
            /* Fallback styles if Vite assets don't load */
            .btn { display: inline-block; padding: 0.375rem 0.75rem; margin-bottom: 0; font-size: 1rem; font-weight: 400; line-height: 1.5; text-align: center; text-decoration: none; vertical-align: middle; cursor: pointer; border: 1px solid transparent; border-radius: 0.375rem; }
            .btn-primary { color: #fff; background-color: #0d6efd; border-color: #0d6efd; }
            .btn-success { color: #fff; background-color: #198754; border-color: #198754; }
            .btn-warning { color: #fff; background-color: #ffc107; border-color: #ffc107; }
            .btn-danger { color: #fff; background-color: #dc3545; border-color: #dc3545; }
            .btn-info { color: #fff; background-color: #0dcaf0; border-color: #0dcaf0; }
            .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.875rem; border-radius: 0.25rem; }
            .table { width: 100%; margin-bottom: 1rem; color: #212529; border-collapse: collapse; }
            .table th, .table td { padding: 0.75rem; vertical-align: top; border-top: 1px solid #dee2e6; }
            .table thead th { vertical-align: bottom; border-bottom: 2px solid #dee2e6; }
            .table-bordered { border: 1px solid #dee2e6; }
            .table-bordered th, .table-bordered td { border: 1px solid #dee2e6; }
            .table-striped tbody tr:nth-of-type(odd) { background-color: rgba(0,0,0,.05); }
        </style>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
        <style>
            body {
                background: #ced4da !important;
            }
            .navbar-brand { font-weight: bold; letter-spacing: 1px; }
            .footer { background: #222; color: #fff; padding: 1rem 0; text-align: center; margin-top: 3rem; }
            .card {
                box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
                border: 1px solid #e9ecef !important;
                background-color: #ffffff !important;
                border-radius: 8px !important;
            }
            .nav-link.active, .nav-link:focus { font-weight: bold; color: #0d6efd !important; }

            /* Fix pagination styling */
            .pagination { display: flex; justify-content: center; align-items: center; margin: 1rem 0; }
            .pagination .page-link { display: inline-block; padding: 0.5rem 1rem; margin: 0 0.25rem; background-color: #fff; border: 1px solid #dee2e6; color: #0d6efd; text-decoration: none; border-radius: 0.375rem; }
            .pagination .page-link:hover { background-color: #e9ecef; border-color: #dee2e6; color: #0a58ca; }
            .pagination .page-item.active .page-link { background-color: #0d6efd; border-color: #0d6efd; color: #fff; }
            .pagination .page-item.disabled .page-link { color: #6c757d; background-color: #fff; border-color: #dee2e6; cursor: not-allowed; }

            /* Hide any problematic arrows or loading indicators */
            .loading-arrow, .loading-spinner, .vite-loading {
                display: none !important;
            }

            /* Ensure proper layout */
            .container { position: relative; z-index: 1; }
            main { position: relative; z-index: 1; }

            /* Ensure navbar is always visible */
            .navbar-collapse {
                display: flex !important;
            }

            /* Remove blue focus/active outline */
            .nav-link:focus, .nav-link:active,
            .btn:focus, .btn:active {
                outline: none !important;
                box-shadow: none !important;
            }

            /* Custom active state for nav links */
            .nav-link.bg-primary {
                background-color: #0d6efd !important;
                color: white !important;
            }

            /* User Account Dropdown Styles */
            .dropdown-menu {
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                border: 1px solid #dee2e6;
                background-color: #ffffff;
            }

            .dropdown-item {
                padding: 0.5rem 1rem;
                color: #212529;
                text-decoration: none;
                display: block;
                width: 100%;
                border: none;
                background: none;
                text-align: left;
                cursor: pointer;
            }

            .dropdown-item:hover {
                background-color: #f8f9fa;
                color: #0d6efd;
            }

            .dropdown-item.text-danger:hover {
                background-color: #f8d7da;
                color: #721c24;
            }

            .dropdown-arrow.rotated {
                transform: rotate(180deg);
                transition: transform 0.2s ease;
            }

            @media (max-width: 991.98px) {
                .navbar-collapse {
                    flex-direction: column;
                    position: absolute;
                    top: 100%;
                    left: 0;
                    right: 0;
                    background: white;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                    z-index: 1000;
                }
            }
        </style>
    </head>
    <body>
        @include('layouts.navigation')

        @if(!request()->routeIs('dashboard') && !request()->routeIs('welcome'))
        <div class="container-fluid py-2 bg-light border-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-2"></i>Back to Previous Page
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="container py-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <main>
                @yield('content')
            </main>
        </div>
        <!-- Bootstrap JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <!-- PWA Service Worker Registration -->
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', function() {
                    navigator.serviceWorker.register('/sw.js')
                        .then(function(registration) {
                            console.log('ServiceWorker registration successful');
                        })
                        .catch(function(err) {
                            console.log('ServiceWorker registration failed');
                        });
                });
            }
        </script>

        <!-- Ensure Bootstrap dropdown works properly -->
        <script>
            // Initialize Bootstrap dropdowns
            document.addEventListener('DOMContentLoaded', function() {
                // Make sure all dropdowns work with Bootstrap
                var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
                dropdownElementList.map(function (dropdownToggleEl) {
                    return new bootstrap.Dropdown(dropdownToggleEl);
                });

                // Set up CSRF token for all forms
                var csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (csrfToken) {
                    // Set CSRF token for axios if available
                    if (window.axios) {
                        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken.content;
                    }

                    // Ensure all forms have CSRF token
                    var forms = document.querySelectorAll('form');
                    forms.forEach(function(form) {
                        if (!form.querySelector('input[name="_token"]')) {
                            var csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = csrfToken.content;
                            form.appendChild(csrfInput);
                        }
                    });
                }
            });
        </script>
    </body>
</html>
