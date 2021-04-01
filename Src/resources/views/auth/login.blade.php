@extends('layouts.unauth_master')
@section('title')
{{ __('label.login_title_login_pass') }}
@endsection
@section('content')
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="email">{{ __('label.login_email_address') }}</label>
                    <input id="email" name="email" autofocus autocomplete="off" value="{{ old('email') }}"
                        class="form-control  @if($errors->has('email') || $errors->has('username')) is-invalid @endif">
                    @if($errors->has('email') || $errors->has('username'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{$errors->first('email') }} {{ $errors->first('username')}}</strong>
                        </span>
                    @endif
                </div> <!-- form-group// -->
                <div class="form-group">
                    <label for="password">{{ __('label.login_password') }}</label>
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
                            {{ __('label.login_remember') }}
                        </label>
                    </div> <!-- checkbox .// -->
                </div> <!-- form-group// -->
                <div class="form-group btn-login">
                    <button type="submit" class="btn btn-block bg-gradient-danger">{{ __('label.login_btn_login') }}</button>
                </div> <!-- form-group// -->
                <p class="mb-1" style="text-align: center;">
                    <a href="{{ route('password.request') }}" class="link-underline">
                        {{ __('label.login_link_forgot_pass') }}
                    </a>
                </p>
            </form>
        </div>
        <!-- /.login-card-body -->
    </div>
@endsection
