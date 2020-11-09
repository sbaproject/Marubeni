@extends('layouts.master')
@section('title', 'User - Create Company')
@section('css')
    <link rel="stylesheet" href="css/user/10_company_registration.css">
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="mb-2" style="font-weight: 600;">Company Registration</h4>
                    <div class="card card-company">
                        <div class="card-body">
                            <form id="frmCompany" name="frmCompany" method="POST">
                                @csrf
                                <input type="hidden" class="form-control" name="id" value="{{ $company->id }}">
                                <div class="row ">
                                    <div class="col-sm-2">
                                        <div class="com_block">Company Information</div>
                                    </div>
                                    <div class="col-sm-10 info_block">
                                        <div class="form-group row ">
                                            <label for="com_name" class="col-sm-2 col-form-label com_title">Company
                                                Name</label>
                                            <div class="col-sm-10">
                                                <input maxlength="255" type="text"
                                                    class="form-control {{ $errors->first('com_name') ? 'is-invalid' : '' }}"
                                                    name="com_name" id="com_name"
                                                    value="{{ old('com_name', $company->name) }}"
                                                    placeholder="Company Name">
                                                @error('com_name')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="com_country"
                                                class="col-sm-2 col-form-label com_title">Country</label>
                                            <div class="col-sm-10">
                                                <input maxlength="255" type="text"
                                                    class="form-control {{ $errors->first('com_country') ? 'is-invalid' : '' }}"
                                                    name="com_country" id="com_country" placeholder="Country"
                                                    value="{{ old('com_country', $company->country) }}">
                                                @error('com_country')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="com_tel" class="col-sm-2 col-form-label com_title">Tell</label>
                                            <div class="col-sm-10">
                                                <input maxlength="255" type="text"
                                                    class="form-control {{ $errors->first('com_tel') ? 'is-invalid' : '' }}"
                                                    value="{{ old('com_tel', $company->phone) }}" name="com_tel"
                                                    id="com_tel" placeholder="Tell">
                                                @error('com_tel')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="com_address"
                                                class="col-sm-2 col-form-label com_title">Address</label>
                                            <div class="col-sm-10">
                                                <input maxlength="255" type="text"
                                                    class="form-control {{ $errors->first('com_address') ? 'is-invalid' : '' }}"
                                                    value="{{ old('com_address', $company->address) }}" name="com_address"
                                                    id="com_address" placeholder="Address">
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
                                        <div class="com_block">Attendants Information</div>
                                    </div>
                                    <div class="col-sm-10 info_block">
                                        <div class="form-group row ">
                                            <label for="att_name" class="col-sm-2 col-form-label com_title">Name</label>
                                            <div class="col-sm-10">
                                                <input maxlength="255" type="text"
                                                    class="form-control {{ $errors->first('att_name') ? 'is-invalid' : '' }}"
                                                    value="{{ old('att_name', $company->attendants_name) }}" name="att_name"
                                                    id="att_name" placeholder="Name">
                                                @error('att_name')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="att_department"
                                                class="col-sm-2 col-form-label com_title">Department</label>
                                            <div class="col-sm-10">
                                                <input maxlength="255" type="text"
                                                    class="form-control {{ $errors->first('att_department') ? 'is-invalid' : '' }}"
                                                    value="{{ old('att_department', $company->attendants_department) }}"
                                                    name="att_department" id="att_department" placeholder="Department">
                                                @error('att_department')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="att_mail" class="col-sm-2 col-form-label com_title">E-mail</label>
                                            <div class="col-sm-10">
                                                <input maxlength="255" type="text"
                                                    class="form-control {{ $errors->first('att_mail') ? 'is-invalid' : '' }}"
                                                    value="{{ old('att_mail', $company->email) }}" name="att_mail"
                                                    id="att_mail" placeholder="E-mail">
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
                                        <div class="com_block">Text</div>
                                    </div>
                                    <div class="col-sm-10 info_block">
                                        <div class="form-group row ">
                                            <div class="col-sm-12">
                                                <textarea maxlength="1000" id="text_content" name="text_content"
                                                    class="form-control" rows="4">{{ old('text_content', $company->memo) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 btn_button">
                                        <button type="submit" class="btn btn-register">Register</button>
                                        <a role="button" href="{{ route('admin.company.index') }}"
                                            class="btn btn-cancel">Cancel</a>
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
