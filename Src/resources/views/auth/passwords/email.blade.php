@extends('layouts.unauth_master')
@section('title')
{{ __('label.login_title_reset_pass') }}
@endsection
@section('content')
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-header">{{ __('label.login_title_reset_pass') }}</div>
        <div class="card-body login-card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form-group">
                    <label for="email">{{ __('label.login_email_address') }}</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                        value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div> <!-- form-group// -->
                <div class="form-group btn-login">
                    <button type="submit" class="btn btn-block bg-gradient-danger">{{ __('label.login_btn_send_pass') }}</button>
                </div> <!-- form-group// -->
            </form>
            <p class="mb-1" style="text-align: center;">
                <a href="{{ route('login') }}" class="link-underline">
                    {{ __('label.login_link_login') }}
                </a>
            </p>
        </div>
        <!-- /.login-card-body -->
    </div>
@endsection
