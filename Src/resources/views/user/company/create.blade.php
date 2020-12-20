@extends('layouts.master')
@section('title')
{{ __('label.menu.company_registration') }}
@endsection
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
                <div class="invoice p-3 mb-3">
                    <div class="card-body">
                        <form id="frmCompany" name="frmCompany" method="POST">
                            @csrf
                            <div class="row ">
                                <div class="col-sm-2">
                                    <div class="com_block">{{ __('label.company.company_information') }}</div>
                                </div>
                                <div class="col-sm-10 info_block">
                                    <div class="form-group row ">
                                        <label for="" class="col-sm-2 col-form-label com_title">{{ __('label.company.company_id') }}</label>
                                        <div class="col-sm-10">
                                            <input maxlength="255" type="text" class="form-control" value="{{ $idcompany }}" placeholder="{{ __('label.company.company_id') }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label for="name" class="col-sm-2 col-form-label com_title">{{ __('label.company.company_name') }}</label>
                                        <div class="col-sm-10">
                                            <input maxlength="255" type="text" class="form-control {{ $errors->first('name') ? 'is-invalid' : '' }}" name="name" id="name" value="{{ old('name') }}" placeholder="{{ __('label.company.company_name') }}">
                                            @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label for="country" class="col-sm-2 col-form-label com_title">{{ __('label.company.company_country') }}</label>
                                        <div class="col-sm-10">
                                            <input maxlength="255" type="text" class="form-control {{ $errors->first('country') ? 'is-invalid' : '' }}" name="country" id="country" placeholder="{{ __('label.company.company_country') }}" value="{{ old('country') }}">
                                            @error('country')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label for="phone" class="col-sm-2 col-form-label com_title">{{ __('label.company.company_tell') }}</label>
                                        <div class="col-sm-10">
                                            <input maxlength="255" type="text" class="form-control {{ $errors->first('phone') ? 'is-invalid' : '' }}" value="{{ old('phone') }}" name="phone" id="phone" placeholder="{{ __('label.company.company_tell') }}">
                                            @error('phone')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label for="address" class="col-sm-2 col-form-label com_title">{{ __('label.company.company_address') }}</label>
                                        <div class="col-sm-10">
                                            <input maxlength="255" type="text" class="form-control {{ $errors->first('address') ? 'is-invalid' : '' }}" value="{{ old('address') }}" name="address" id="address" placeholder="{{ __('label.company.company_address') }}">
                                            @error('address')
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
                                        <label for="attendants_name" class="col-sm-2 col-form-label com_title">{{ __('label.company.att_name') }}</label>
                                        <div class="col-sm-10">
                                            <input maxlength="255" type="text" class="form-control {{ $errors->first('attendants_name') ? 'is-invalid' : '' }}" value="{{ old('attendants_name') }}" name="attendants_name" id="attendants_name" placeholder="{{ __('label.company.att_name') }}">
                                            @error('attendants_name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label for="attendants_department" class="col-sm-2 col-form-label com_title">{{ __('label.company.att_department') }}</label>
                                        <div class="col-sm-10">
                                            <input maxlength="255" type="text" class="form-control {{ $errors->first('attendants_department') ? 'is-invalid' : '' }}" value="{{ old('attendants_department') }}" name="attendants_department" id="attendants_department" placeholder="{{ __('label.company.att_department') }}">
                                            @error('attendants_department')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label for="email" class="col-sm-2 col-form-label com_title">{{ __('label.company.att_email') }}</label>
                                        <div class="col-sm-10">
                                            <input maxlength="255" type="text" class="form-control {{ $errors->first('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}" name="email" id="email" placeholder="{{ __('label.company.att_email') }}">
                                            @error('email')
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
                                            <textarea maxlength="1000" id="memo" name="memo" class="form-control" rows="4">{{ old('memo') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 btn_button">
                                    <button type="submit" class="btn bg-gradient-success"><i class="far fa-check-circle" style="margin-right: 5px;"></i>{{ __('label.button.register') }}</button>
                                    <a role="button" href="{{ route('user.company.create') }}" class="btn bg-gradient-secondary"><i class="fa fa-ban" aria-hidden="true" style="margin-right: 5px;"></i>{{ __('label.button.cancel') }}</a>
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
