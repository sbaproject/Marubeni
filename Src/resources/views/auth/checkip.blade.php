@extends('layouts.unauth_master')
@section('title')
{{ __('label.checkip_title') }}
@endsection
@section('content')
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-header">{{ __('label.checkip_title') }}</div>
        <div class="card-body login-card-body">
            <form method="POST" action="{{ route('confirm') }}">
                @csrf
                <div class="form-group">
                    <label for="code">{{ __('label.checkip_content') }}{{ Auth::user()->email }}</label>
                    <input id="code" name="code" class="form-control @error('code') is-invalid @enderror" autofocus
                        autocomplete="off" value="" maxlength="20" placeholder="{{ __('label.checkip_enter_code') }}">
                    @error('code')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div> <!-- form-group// -->         
                <div class="text-center">
                    <button type="submit" class="btn btn-danger pt-1 pb-1 mr-4 col-12 col-lg-5"><i class="nav-icon far fa-check-circle" style="margin-right: 5px"></i>{{ __('label.checkip_btn_confirm') }}</button>
                    <a role="button" href="{{ route('logout') }}" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();" class="btn btn-outline-dark pt-1 pb-1 col-12 col-lg-5"><i class="nav-icon far fa-times-circle" style="margin-right: 5px"></i>{{ __('label.checkip_btn_back') }}</a>
                </div>  
                        
            </form>           
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
        </div>
        <!-- /.login-card-body -->
    </div>
@endsection
