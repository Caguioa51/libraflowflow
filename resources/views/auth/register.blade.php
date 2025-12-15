@extends('layouts.guest')

@section('content')
<div class="card shadow-lg border-0">
    <div class="card-body p-5">
        <div class="text-center mb-4">
            <img src="https://yt3.googleusercontent.com/ytc/AIdro_lkEzByQWiP7aN8FsnOE0YDcDAAYka5o4WkmHWJgbmldw=s900-c-k-c0x00ffffff-no-rj" alt="School Logo" width="70" class="mb-2 rounded-circle shadow">
            <h2 class="h4 fw-bold mb-0">Dagupan City National Highschool</h2>
            <div class="text-primary mb-2">Library Management System</div>
        </div>
        
        <div class="alert alert-info text-center">
            <h5><i class="bi bi-shield-check"></i> Admin-Only Registration</h5>
            <p class="mb-0">User registration is restricted to administrators only. Please contact your system administrator to create an account for you.</p>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Back to Login
            </a>
        </div>
    </div>
</div>
@endsection
