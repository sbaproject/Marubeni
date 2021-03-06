@extends('layouts.master')

@section('title')
{{ __('label.title_user_edit') }}
@endsection

@section('css')
@endsection

@section('js')
{{-- cleave js --}}
<script src="js/cleave/cleave.min.js"></script>
{{-- private js --}}
<script src="js/admin/account_input.js"></script>
@endsection

@section('content-header')
{{ __('label.title_user_edit') }}
@endsection

@section('content-breadcrumb')
{{-- <li class="breadcrumb-item active">{{ __('label.menu_settings') }}</li> --}}
<li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">{{ __('label.menu_employee_setting') }}</a></li>
<li class="breadcrumb-item active">{{ __('label.title_user_edit') }}</li>
@endsection

@section('content')
<div class="col-lg-9" style="padding: 0">
    <div class="invoice p-3">
        <div class="card-body">
            <div class="search-content">
                {{-- <x-alert/> --}}
                <form method="POST" action="{{ Request::url() }}">
                    @csrf
                    {{-- ID --}}
                    <div class="form-group row">
                        <label for="user_no"
                            class="col-lg-3 col-form-label text-center">{{ __('validation.attributes.user_no') }}</label>
                        <div class="col-lg-9 text-lg-left text-center">
                            <input id="user_no" type="text" class="form-control" readonly
                                value="{{ $data['user']->user_no }}">
                        </div>
                    </div>
                    {{-- Location --}}
                    <div class="form-group row">
                        <label for="location"
                            class="col-lg-3 col-form-label text-center">{{ __('validation.attributes.location') }}</label>
                        <div class="col-lg-9 text-lg-left text-center">
                            <fieldset id="location" class="@error('location') form-control is-invalid @enderror">
                                @foreach ($data['locations'] as $key => $value)
                                <label class="radio-inline com_title col-form-label">
                                    <input type="radio" name="location" value="{{ $value }}"
                                        {{ $data['user']->location == $value ? 'checked' : '' }}>
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
                        <label for="department"
                            class="col-lg-3 col-form-label text-center">{{ __('validation.attributes.department') }}</label>
                        <div class="col-lg-9">
                            <select id="department" name="department"
                                class="form-control @error('department') is-invalid @enderror">
                                <option value='' selected>{{ __('label.select') }}</option>
                                @foreach ($data['departments'] as $item)
                                <option value="{{ $item->id }}"
                                    {{ $data['user']->department_id == $item->id ? 'selected' : '' }}>{{ $item->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('department')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    {{-- Name --}}
                    <div class="form-group row">
                        <label for="name"
                            class="col-lg-3 col-form-label text-center">{{ __('validation.attributes.user.name') }}</label>
                        <div class="col-lg-9">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                name="name" value="{{ $data['user']->name }}" placeholder="{{ __('validation.attributes.user.name') }}" autofocus autocomplete="off" >
                            @error('name')
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
                            <select id="role" name="role" class="form-control @error('role') is-invalid @enderror">
                                <option value='' selected @if ($data['user']->super_admin_flg == config('const.check.on')) disabled @endif>
                                    {{ __('label.select') }}
                                </option>
                                @foreach ($data['roles'] as $key => $value)
                                <option value="{{ $value }}" {{ $data['user']->role == $value ? 'selected' : '' }}
                                    @if ($data['user']->super_admin_flg == config('const.check.on')) disabled @endif>
                                    {{ $key }}
                                </option>
                                @endforeach
                            </select>
                            @error('role')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    {{-- Phone --}}
                    <div class="form-group row">
                        <label for="phone"
                            class="col-lg-3 col-form-label text-center">{{ __('validation.attributes.phone') }}</label>
                        <div class="col-lg-9">
                            <input id="phone" name="phone" type="text"
                                class="form-control @error('phone') is-invalid @enderror"
                                value="{{ $data['user']->phone }}" placeholder="{{ __('validation.attributes.phone') }}" autocomplete="off" autofocus>
                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    {{-- Email --}}
                    <div class="form-group row">
                        <label for="email"
                            class="col-lg-3 col-form-label text-center">{{ __('validation.attributes.email') }}</label>
                        <div class="col-lg-9">
                            <input id="email" type="text" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ $data['user']->email }}" placeholder="{{ __('validation.attributes.email') }}" autocomplete="off" autofocus>

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    {{-- Entitled this year --}}
                    <div class="form-group row">
                        <label for="leave_days" class="col-lg-3 col-form-label text-center">
                            {{ __('validation.attributes.leave_days') }}
                        </label>
                        <div class="col-lg-9">
                            <input id="txt_leave_days" type="text" autofocus
                                class="form-control leave_days @error('leave_days') is-invalid @enderror"
                                style="width:20%;display: inline-block; text-align: right"
                                value="{{ $data['user']->leave_days }}"
                                placeholder="{{ __('validation.attributes.leave_days') }}" autocomplete="off">
                            <span>{{ __('label.leave.caption_days') }}</span>
                            <input type="hidden" name="leave_days" value="{{ $data['user']->leave_days }}">
                            @error('leave_days')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    {{-- Remaining days --}}
                    <div class="form-group row">
                        <label for="txt_leave_remaining_days" class="col-lg-3 col-form-label text-center">
                            {{ __('validation.attributes.leave_remaining_days') }}
                        </label>
                        <div class="col-lg-9">
                            <input id="txt_leave_remaining_days" type="text" autofocus
                                class="form-control leave_remaining_days @error('leave_remaining_days') is-invalid @enderror"
                                style="width:20%;display: inline-block; text-align: right"
                                value="{{ $data['user']->leave_remaining_days }}"
                                placeholder="{{ __('validation.attributes.leave_remaining_days') }}" autocomplete="off">
                            <span>{{ __('label.leave.caption_days') }}</span>
                            <input type="hidden" name="leave_remaining_days" value="{{ $data['user']->leave_remaining_days }}">
                            @error('leave_remaining_days')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    {{-- Remaining time --}}
                    <div class="form-group row">
                        <label for="txt_leave_remaining_time" class="col-lg-3 col-form-label text-center">
                            {{ __('validation.attributes.leave_remaining_time') }}
                        </label>
                        <div class="col-lg-9">
                            <input id="txt_leave_remaining_time" type="text" autofocus
                                class="form-control leave_remaining_time @error('leave_remaining_time') is-invalid @enderror"
                                style="width:20%;display: inline-block; text-align: right"
                                value="{{ $data['user']->leave_remaining_time }}"
                                placeholder="{{ __('validation.attributes.leave_remaining_time') }}" autocomplete="off">
                            <span>{{ __('label.leave.caption_hours') }}</span>
                            <input type="hidden" name="leave_remaining_time" value="{{ $data['user']->leave_remaining_time }}">
                            @error('leave_remaining_time')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    {{-- Approval --}}
                    <div class="form-group row">
                        <label for="approval"
                            class="col-lg-3 col-form-label text-center">{{ __('validation.attributes.approval') }}</label>
                        <div class="col-lg-9 text-lg-left text-center">
                            <fieldset id="approval" class="@error('approval') form-control is-invalid @enderror">
                                @foreach ($data['approvals'] as $key => $value)
                                <label class="radio-inline com_title col-form-label">
                                    <input type="radio" name="approval" value="{{ $value }}"
                                        {{ $data['user']->approval == $value ? 'checked' : '' }}>
                                    {{ __('label.'.$key) }}
                                </label>
                                @endforeach
                            </fieldset>

                            @error('approval')
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
                            <textarea id="memo" name="memo" rows="4" placeholder="{{ __('validation.attributes.memo') }}"
                                class="form-control @error('memo') is-invalid @enderror">{{ $data['user']->memo }}</textarea>
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
                            <i class="nav-icon far fa-check-circle"></i>
                            {{ __('label.button_update') }}
                        </button>
                        {{-- Cancel --}}
                        <a href="{{ route('admin.user.index') }}"
                            class="btn bg-gradient-secondary">
                            <i class="nav-icon far fa-times-circle"></i>
                            {{__('label.button_cancel')}}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
