@extends('layouts.auth')

@section('content')
    <div class="login-form">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <img class="brand-logo mb-3" alt="igi logo" src="{{ asset('assets/img/logo.png') }}" height=150px;>
            <div class="form-group mb-3">
                <input type="email" placeholder="Email" name="email" class="@error('email') is-invalid @enderror">
                <i class="fa fa-envelope-o" aria-hidden="true"></i>
            </div>

            <div class="form-group mb-3">
                <input type="password" placeholder="Password" name="password"
                    class="@error('password') is-invalid @enderror">
                <i class="fa fa-lock" aria-hidden="true"></i>
            </div>

            <button type="submit">{{ __('Log In') }}</button>

            @if (Route::has('password.request'))
                <a class="btn btn-link" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            @endif
        </form>
        <p>
            <small>For any queries, concerns and issues in logging in, speak to the Guardians IT helpdesk</small>
        </p>
    </div>
@endsection
