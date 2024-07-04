@extends('layouts.auth')

@section('content')
    <div class="login-form">
        <h3>{{ __('Verify Your Email Address') }}</h3>
        @if (session('resent'))
            <div class="alert alert-success" role="alert">
                {{ __('A fresh verification link has been sent to your email address.') }}
            </div>
        @endif

        <form class="" method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <img class="brand-logo mb-3" alt="igi logo" src="{{ asset('assets/img/logo.png') }}" height=150px;>

            {{ __('Before proceeding, please check your email for a verification link.') }}
            {{ __('If you did not receive the email') }},

            <button type="submit">{{ __('click here to request another') }}</button>
        </form>
    </div>
@endsection
