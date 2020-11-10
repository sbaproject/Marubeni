@extends('layouts.master')
@section('title', 'User - Create Company')
@section('css')
    <link rel="stylesheet" href="css/user/10_company_registration.css">
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <x-alert />
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="mb-2" style="font-weight: 600;">{{ __('label.company.company_registration') }}</h4>
                    <div class="card card-company">
                        <div class="card-body">
                            <form id="frmCompany" name="frmCompany" method="POST">
                                @csrf
                                <div class="row ">
                                    <div class="col-sm-2">
                                        <div class="com_block">{{ __('label.company.company_information') }}</div>
                                    </div>
                                    <div class="col-sm-10 info_block">
                                        <div class="form-group row ">
                                            <label for="com_name"
                                                class="col-sm-2 col-form-label com_title">{{ __('label.company.company_name') }}</label>
                                            <div class="col-sm-10">
                                                <input maxlength="255" type="text"
                                                    class="form-control {{ $errors->first('com_name') ? 'is-invalid' : '' }}"
                                                    name="com_name" id="com_name" value="{{ old('com_name') }}"
                                                    placeholder="{{ __('label.company.company_name') }}">
                                                @error('com_name')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="com_country"
                                                class="col-sm-2 col-form-label com_title">{{ __('label.company.company_country') }}</label>
                                            <div class="col-sm-10">
                                                <input maxlength="255" type="text"
                                                    class="form-control {{ $errors->first('com_country') ? 'is-invalid' : '' }}"
                                                    name="com_country" id="com_country" placeholder="{{ __('label.company.company_country') }}"
                                                    value="{{ old('com_country') }}">
                                                @error('com_country')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="com_tel"
                                                class="col-sm-2 col-form-label com_title">{{ __('label.company.company_tell') }}</label>
                                            <div class="col-sm-10">
                                                <input maxlength="255" type="text"
                                                    class="form-control {{ $errors->first('com_tel') ? 'is-invalid' : '' }}"
                                                    value="{{ old('com_tel') }}" name="com_tel" id="com_tel"
                                                    placeholder="{{ __('label.company.company_tell') }}">
                                                @error('com_tel')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="com_address"
                                                class="col-sm-2 col-form-label com_title">{{ __('label.company.company_address') }}</label>
                                            <div class="col-sm-10">
                                                <input maxlength="255" type="text"
                                                    class="form-control {{ $errors->first('com_address') ? 'is-invalid' : '' }}"
                                                    value="{{ old('com_address') }}" name="com_address" id="com_address"
                                                    placeholder="{{ __('label.company.company_address') }}">
                                                @error('com_address')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- attendants-->
                                <div class="row ">
                                    <div class="col-sm-2">
                                        <div class="com_block">{{ __('label.company.att_information') }}</div>
                                    </div>
                                    <div class="col-sm-10 info_block">
                                        <div class="form-group row ">
                                            <label for="att_name"
                                                class="col-sm-2 col-form-label com_title">{{ __('label.company.att_name') }}</label>
                                            <div class="col-sm-10">
                                                <input maxlength="255" type="text"
                                                    class="form-control {{ $errors->first('att_name') ? 'is-invalid' : '' }}"
                                                    value="{{ old('att_name') }}" name="att_name" id="att_name"
                                                    placeholder="{{ __('label.company.att_name') }}">
                                                @error('att_name')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="att_department"
                                                class="col-sm-2 col-form-label com_title">{{ __('label.company.att_department') }}</label>
                                            <div class="col-sm-10">
                                                <input maxlength="255" type="text"
                                                    class="form-control {{ $errors->first('att_department') ? 'is-invalid' : '' }}"
                                                    value="{{ old('att_department') }}" name="att_department"
                                                    id="att_department" placeholder="{{ __('label.company.att_department') }}">
                                                @error('att_department')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="att_mail"
                                                class="col-sm-2 col-form-label com_title">{{ __('label.company.att_email') }}</label>
                                            <div class="col-sm-10">
                                                <input maxlength="255" type="text"
                                                    class="form-control {{ $errors->first('att_mail') ? 'is-invalid' : '' }}"
                                                    value="{{ old('att_mail') }}" name="att_mail" id="att_mail"
                                                    placeholder="{{ __('label.company.att_email') }}">
                                                @error('att_mail')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!-- text -->
                                <div class="row ">
                                    <div class="col-sm-2">
                                        <div class="com_block">{{ __('label.company.text') }}</div>
                                    </div>
                                    <div class="col-sm-10 info_block">
                                        <div class="form-group row ">
                                            <div class="col-sm-12">
                                                <textarea maxlength="1000" id="text_content" name="text_content"
                                                    class="form-control" rows="4"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 btn_button">
                                        <button type="submit"
                                            class="btn btn-register"><i class="far fa-check-circle" style="margin-right: 5px;"></i>{{ __('label.button.register') }}</button>
                                        <a role="button" href="{{ route('user.company.create') }}"
                                            class="btn btn-cancel"><i class="fa fa-ban" aria-hidden="true" style="margin-right: 5px;"></i>{{ __('label.button.cancel') }}</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
@endsection
