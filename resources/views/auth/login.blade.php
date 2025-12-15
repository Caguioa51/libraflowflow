@extends('layouts.guest')

@section('content')
<div class="card shadow-lg border-0">
    <div class="card-body p-5">
        <div class="text-center mb-4">
            <img src="https://yt3.googleusercontent.com/ytc/AIdro_lkEzByQWiP7aN8FsnOE0YDcDAAYka5o4WkmHWJgbmldw=s900-c-k-c0x00ffffff-no-rj" alt="School Logo" width="70" class="mb-2 rounded-circle shadow">
            <h2 class="h4 fw-bold mb-0">Dagupan City National Highschool</h2>
            <div class="text-primary mb-2">Library Management System</div>
        </div>
        <x-auth-session-status class="mb-3" :status="session('status')" />
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div class="mb-3">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div class="mb-3 form-check">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label for="remember_me" class="form-check-label">{{ __('Remember me') }}</label>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                @if (Route::has('password.request'))
                    <a class="small text-decoration-none text-primary" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2">{{ __('Log in') }}</button>
        </form>
        <div class="mt-4 text-center">
            <small class="text-muted">
                <i class="bi bi-info-circle"></i>
                New user registration is handled by administrators only.
            </small>
        </div>
    </div>
</div>
@endsection
