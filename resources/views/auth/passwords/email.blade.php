@extends('layouts.auth')

@section('content')
    <div class="login-form">
        <h3>{{ __('Reset Password') }}</h3>
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <img class="brand-logo mb-3" alt="igi logo" src="{{ asset('assets/img/logo.png') }}" height=150px;>

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="form-group mb-3">
                <input type="email" placeholder="Email" name="email" class="@error('email') is-invalid @enderror">
                <i class="fa fa-envelope-o" aria-hidden="true"></i>
            </div>

            <button type="submit">{{ __('Send Reset Link') }}</button>
        </form>
    </div>
@endsection
