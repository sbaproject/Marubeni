@extends('layouts.master')
@section('title')
{{ __('label.menu_department_registration') }}
@endsection
@section('css')
    <link rel="stylesheet" href="css/user/10_company_registration.css">
@endsection

@section('content-header')
{{ __('label.menu_department_registration') }}
@endsection

@section('content-breadcrumb')
<li class="breadcrumb-item active">{{ __('label.menu_settings') }}</li>
<li class="breadcrumb-item active">{{ __('label.menu_department_list') }}</li>
<li class="breadcrumb-item active">{{ __('label.menu_department_registration') }}</li>
@endsection

@section('content')
  <div class="col-lg-9" style="padding: 0">
    <div class="invoice p-3">
        <div class="card-body">
            <div class="search-content">
                <form action="{{ route('admin.department.store') }}" method="POST">
                    @csrf
                    
                    {{-- Name --}}
                    <div class="form-group row">
                        <label for="name"
                            class="col-lg-3 col-form-label text-center">{{ __('validation.attributes.user.name') }}</label>
                        <div class="col-lg-9">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                name="name" value="{{ old('name') }}" placeholder="{{ __('validation.attributes.user.name') }}" autocomplete="off" autofocus>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    
                    {{-- Memo --}}
                    <div class="form-group row">
                        <label for="memo"
                            class="col-lg-3 col-form-label text-center d-flex align-items-center justify-content-center">{{ __('validation.attributes.memo') }}</label>
                        <div class="col-lg-9">
                            <textarea id="memo" name="memo" rows="4"
                                class="form-control @error('memo') is-invalid @enderror"
                                    placeholder="{{ __('validation.attributes.memo') }}" autocomplete="off" autofocus>{{ old('memo') }}</textarea>
                            @error('memo')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <br>
                    <div class="mt-5 mb-5 text-center">
                        {{-- Submit --}}
                        <button type="submit" class="btn bg-gradient-success">
                            <i class="nav-icon far fa-check-circle"></i> {{ __('label.button_register') }}</button>
                        {{-- Cancel --}}
                        <a class="btn bg-gradient-secondary"
                            href="{{ route('admin.department.index') }}">
                            <i class="nav-icon far fa-times-circle"></i> {{__('label.button_cancel')}}</a>
                    </div>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection
