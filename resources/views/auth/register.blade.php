@extends('layouts.auth')

@section('content')
    <div class="login-form">
        <form method="POST" action="{{ route('register') }}">
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

            <div class="form-group mb-3">
                <input type="password" placeholder="Password Confirmation" name="password_confirmation"
                    class="@error('password_confirmation') is-invalid @enderror">
                <i class="fa fa-lock" aria-hidden="true"></i>
            </div>
            <p>
                {{ __('Already have an account?') }}
                <a class="" href="{{ route('login') }}">
                    {{ __('Login') }}
                </a>
            </p>
            <button type="submit">{{ __('Register') }}</button>
        </form>
    </div>
@endsection
