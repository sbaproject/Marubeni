@extends('layouts.master')
@section('title')
{{ __('label.menu_applicant_registration') }}
@endsection
@section('css')
    <link rel="stylesheet" href="css/user/10_company_registration.css">
@endsection

@section('content-header')
{{ __('label.menu_applicant_registration') }}
@endsection

@section('content-breadcrumb')
<li class="breadcrumb-item active">{{ __('label.menu_settings') }}</li>
<li class="breadcrumb-item active">{{ __('label.menu_applicant_list') }}</li>
<li class="breadcrumb-item active">{{ __('label.menu_applicant_registration') }}</li>
@endsection

@section('content')
  <div class="col-lg-9" style="padding: 0">
    <div class="invoice p-3">
        <div class="card-body">
            <div class="search-content">
                <form action="{{ route('admin.applicant.store') }}" method="POST">
                    @csrf
                    
                    {{-- Location --}}
                    <div class="form-group row">
                        <label for="location"
                            class="col-lg-3 col-form-label text-center">{{ __('validation.attributes.location') }}</label>
                        <div class="col-lg-9 text-lg-left text-center">
                            <fieldset id="location" class="@error('location') form-control is-invalid @enderror">
                                @foreach ($data['locations'] as $key => $value)
                                <label class="radio-inline com_title col-form-label">
                                    <input type="radio" name="location" value="{{ $value }}"
                                        {{ old('location') == $value ? 'checked' : '' }}>
                                    {{ __('label.'.$key) }}
                                </label>
                                @endforeach
                            </fieldset>
                            @error('location')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    {{-- Department --}}
                    <div class="form-group row">
                        <label for="department_id"
                            class="col-lg-3 col-form-label text-center">{{ __('validation.attributes.department') }}</label>
                        <div class="col-lg-9">
                            <select id="department_id" name="department_id"
                                class="form-control @error('department_id') is-invalid @enderror">
                                <option value='' selected>{{ __('label.select') }}</option>
                                @foreach ($data['departments'] as $item)
                                <option value="{{ $item->id }}" {{ old('department_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('department_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>                    
                    {{-- Role --}}
                    <div class="form-group row">
                        <label for="role"
                            class="col-lg-3 col-form-label text-center">{{ __('validation.attributes.role') }}</label>
                        <div class="col-lg-9">
                            <div class="col-md-6" style="padding-left:0">
                                <select id="role" name="role" class="form-control @error('role') is-invalid @enderror">
                                    <option value='' selected>{{ __('label.select') }}</option>
                                    @foreach ($data['roles'] as $key => $value)
                                    <option value="{{ $value }}" {{ old('role') == $value ? 'selected' : '' }}>
                                        {{ $key }}</option>
                                    @endforeach
                                </select>
                                @error('role')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="mt-5 mb-5 text-center">
                        {{-- Submit --}}
                        <button type="submit" class="btn bg-gradient-success">
                            <i class="nav-icon far fa-check-circle"></i> {{ __('label.button_register') }}</button>
                        {{-- Cancel --}}
                        <a class="btn bg-gradient-secondary"
                            href="{{ route('admin.applicant.index') }}">
                            <i class="nav-icon far fa-times-circle"></i> {{__('label.button_cancel')}}</a>
                    </div>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection
