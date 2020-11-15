@extends('layouts.unauth_master')

@section('title')
    Login
@endsection

@section('content')
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-header">{{ __('Xác nhận ở ngoài mạng') }}</div>
        <div class="card-body login-card-body">
            <form method="POST" action="{{ route('confirm') }}">
                @csrf
                <div class="form-group">
                    <label for="code">{{ __('Mã xác nhận đã gửi qua mail') }}</label>
                    <input id="code" name="code" class="form-control @error('code') is-invalid @enderror" autofocus
                        autocomplete="off" value="">
                    @error('code')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div> <!-- form-group// -->        
                <div class="form-group btn-login">
                    <button type="submit" class="btn btn-danger btn-block pt-1 pb-1 mr-4 col-5 col-sm-2 col-md-4 col-lg-2">{{ __('Xác nhận') }}</button>
                    
                    <a  role="button" href="{{ route('logout') }}" class="btn btn-outline-dark pt-1 pb-1 col-5 col-sm-2 col-md-4 col-lg-2" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();"><i
                            class="nav-icon far fa-times-circle"
                            style="margin-right: 5px"></i>
                {{ __('label.button.cancel') }}
                    </a>                         
                </div> <!-- form-group// -->
                
            </form>
           
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
        </div>
        <!-- /.login-card-body -->
    </div>
@endsection
