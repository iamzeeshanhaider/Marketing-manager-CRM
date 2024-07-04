@extends('layouts.auth')

@section('content')
    <div class="login-form">
        <h3>{{ __('Confirm Password') }}</h3>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf
            <img class="brand-logo mb-3" alt="igi logo" src="{{ asset('assets/img/logo.png') }}" height=150px;>

            {{ __('Please confirm your password before continuing.') }}

            <div class="form-group mb-3">
                <input type="password" placeholder="Password" name="password" class="@error('password') is-invalid @enderror">
                <i class="fa fa-lock" aria-hidden="true"></i>
            </div>

            <button type="submit">{{ __('Confirm Password') }}</button>

            @if (Route::has('password.request'))
                <a class="btn btn-link" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            @endif
        </form>
    </div>
@endsection
