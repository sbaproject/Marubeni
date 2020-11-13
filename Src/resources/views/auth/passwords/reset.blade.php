@extends('layouts.unauth_master')
@section('title')
    Create New Password
@endsection
@section('content')
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-header">{{ __('label.login.title_create_pass') }}</div>
        <div class="card-body login-card-body">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="form-group">
                    <label for="email">{{ __('label.login.email_address') }}</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                        value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus readonly>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div> <!-- form-group// -->
                <div class="form-group">
                    <label for="password">{{ __('label.login.password') }}</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" required autocomplete="new-password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div> <!-- form-group// -->
                <div class="form-group">
                    <label for="password">{{ __('label.login.confirm_password') }}</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required
                        autocomplete="new-password">
                </div> <!-- form-group// -->
                <div class="form-group btn-login">
                    <button type="submit" class="btn btn-danger btn-block">{{ __('label.login.btn_create_pass') }}</button>
                </div> <!-- form-group// -->

            </form>
        </div>
        <!-- /.login-card-body -->
    </div>
@endsection
