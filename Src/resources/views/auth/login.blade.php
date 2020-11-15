@extends('layouts.unauth_master')
@section('title')
{{ __('label.login.title_login_pass') }}
@endsection
@section('content')
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="email">{{ __('label.login.email_address') }}</label>
                    <input id="email" name="email" class="form-control @error('email') is-invalid @enderror" autofocus
                        autocomplete="off" value="{{ old('email') }}">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div> <!-- form-group// -->
                <div class="form-group">
                    <label for="password">{{ __('label.login.password') }}</label>
                    <input type="password" id="password" name="password" autocomplete="off"
                        class="form-control @error('password') is-invalid @enderror">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div> <!-- form-group// -->
                <div class="form-group">
                    <div class="checkbox">
                        <label style="font-weight: normal;">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            {{ __('label.login.remember') }}
                        </label>
                    </div> <!-- checkbox .// -->
                </div> <!-- form-group// -->
                <div class="form-group btn-login">
                    <button type="submit" class="btn btn-danger btn-block">{{ __('label.login.btn_login') }}</button>
                </div> <!-- form-group// -->
                <p class="mb-1" style="text-align: center;">
                    <a href="{{ route('password.request') }}"
                        style="border-bottom: 1px solid #010ECC; color: #010ECC;">{{ __('label.login.link_forgot_pass') }}</a>
                </p>
            </form>
        </div>
        <!-- /.login-card-body -->
    </div>
@endsection
