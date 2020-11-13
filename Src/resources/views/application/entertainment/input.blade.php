@extends('layouts.master')

@section('title')
{{ __('label.entertaiment_application') }}
@endsection

@section('css')
{{-- for this view --}}
<link rel="stylesheet" href="css/user/04_leave_application.css">
<style type="text/css">
    .invalid-feedback {
        display: block;
    }
    .d-none {
        display: none !important;
    }
    .form-group .radio-inline:nth-child(1){
        margin-right: 20px;
    }
</style>
@endsection

@section('js')
{{-- datetimepicker --}}
<script src="js/moment/moment.min.js"></script>
<script src="js/moment/locale/{{ config('app.locale') }}.js"></script>
<script src="js/bootstrap-datetimepicker.js"></script>
{{-- for this view --}}
<script src="js/user/application/entertainment/create.js"></script>
@endsection

@section('content')
@php

$entertainment_dt       = Session::has('inputs') ? Session::get('inputs')['entertainment_dt']       : (isset($model) ? $model->entertainment_dt : null);
$place                  = Session::has('inputs') ? Session::get('inputs')['place']                  : (isset($model) ? $model->place : null);
$during_trip            = Session::has('inputs') ? Session::get('inputs')['during_trip']            : (isset($model) ? $model->during_trip : null);
$check_row              = Session::has('inputs') ? Session::get('inputs')['check_row']              : (isset($model) ? $model->check_row : null);
$entertainment_times    = Session::has('inputs') ? Session::get('inputs')['entertainment_times']    : (isset($model) ? $model->entertainment_times : null);
$existence_projects     = Session::has('inputs') ? Session::get('inputs')['existence_projects']     : (isset($model) ? $model->existence_projects : null);
$includes_family        = Session::has('inputs') ? Session::get('inputs')['includes_family']        : (isset($model) ? $model->includes_family : null);
$project_name           = Session::has('inputs') ? Session::get('inputs')['project_name']           : (isset($model) ? $model->project_name : null);
$entertainment_reason   = Session::has('inputs') ? Session::get('inputs')['entertainment_reason']   : (isset($model) ? $model->entertainment_reason : null);
$entertainment_person   = Session::has('inputs') ? Session::get('inputs')['entertainment_person']   : (isset($model) ? $model->entertainment_person : null);
$est_amount             = Session::has('inputs') ? Session::get('inputs')['est_amount']             : (isset($model) ? $model->est_amount : null);
$reason_budget_over     = Session::has('inputs') ? Session::get('inputs')['reason_budget_over']     : (isset($model) ? $model->reason_budget_over : null);
$file_path              = Session::has('inputs') ? Session::get('inputs')['file_path']              : (isset($model) ? $model->file_path : null);

@endphp
<section class="content">
    <x-alert />
    <form method="POST"
        action="@if (isset($id)) {{ route('user.entertainment.update', $id) }} @else {{ route('user.entertainment.store') }} @endif"
        enctype="multipart/form-data">
        @csrf
        <div class="main-top">
            <h4 class="main-header-text">{{ Str::upper(__('label.form.entertaiment')) }}</h4>
            <button type="submit" name="pdf" value="pdf" class="btn btn-outline-dark" href="#">
                <i class="fas fa-external-link-alt" style="margin-right: 5px; color: #fff;"></i>
                {{ __('label.button.export') }}
            </button>
        </div>
        <div class="card">
            <div class="card-body">
                @if (isset($id))
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>{{ __('label.entertainment.application_no') }}</label>
                    </div>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" readonly value="{{ $application->application_no }}">
                    </div>
                </div>
                <hr>
                @endif
                <div class="form-group row ">
                    <label class="col-lg-2 col-form-label text-left">Date & Time</label>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <div id="datetime" data-target-input="nearest" class="input-group date">
                                <input type="text"
                                    class="form-control datetimepicker-input @error('entertainment_dt') is-invalid @enderror"
                                    data-target="#datetime" />
                                <div class="input-group-addon input-group-append" data-target="#datetime"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar-alt"></i>
                                    </div>
                                </div>
                            </div>
                            @error('entertainment_dt')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <input type="hidden" id="entertainment_dt" name="entertainment_dt" value="{{ $entertainment_dt }}">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group row ">
                    <label class="col-lg-2 col-form-label text-left">Place</label>
                    <div class="col-lg-10">
                        <input type="text" name="place" class="form-control @error('place') is-invalid @enderror" value="{{ $place }}">
                        @error('place')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <hr>
                <div class="form-group row ">
                    <label class="col-lg-2 col-form-label text-left">During biz trip</label>
                    <div class="col-lg-10 text-lg-left text-left">
                        <fieldset class="@error('during_trip') form-control is-invalid @enderror">
                            @foreach (config('const.business.during_trip') as $key => $value)
                                <label class="radio-inline com_title col-form-label">
                                    <input type="radio" name="during_trip" value="{{ $value }}" @if($during_trip !== null && $during_trip == $value) checked @endif>
                                    {{ __('label.'. $key) }}
                                </label>
                            @endforeach
                        </fieldset>
                        @error('during_trip')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label text-left d-flex align-items-left justify-content-left">
                        Entrainment Infomation
                    </label>
                    <div class="col-lg-10">
                        <!-- form chil -->
                        <div class="card card-body card-company">
                            <div class="form-group row ">
                                <label class="col-lg-2 col-form-label com_title text-left">Company Name</label>
                                <div class="col-lg-10"><input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label class="col-lg-2 col-form-label com_title text-left">Title</label>
                                <div class="col-lg-10"><input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label class="col-lg-2 col-form-label com_title text-left">Name of Attendants</label>
                                <div class="col-lg-10"><input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label class="col-lg-2 col-form-label com_title text-left">Details of dutles</label>
                                <div class="col-lg-10"><input type="text" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="text-lg-left text-left">
                            <a class="btn btn-outline-dark" role="button" href="#">
                                + Add
                            </a>
                        </div>
                        <!-- ./form chil -->
                    </div>
                </div>
                <hr>
                <div class="form-group row ">
                    <label class="col-lg-2 col-form-label text-left">
                        Confirmation of Compliance with Laws</label>
                    <div class="col-lg-10 text-lg-left text-left">
                        <fieldset class="@error('check_row') form-control is-invalid @enderror">
                            @foreach (config('const.business.check_row') as $key => $value)
                            <label class="radio-inline com_title col-form-label">
                                <input type="radio" name="check_row" value="{{ $value }}" @if($check_row !== null && $check_row == $value) checked @endif>
                                {{ __('label.'. $key) }}
                            </label>
                            @endforeach
                        </fieldset>
                        @error('check_row')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label text-left">
                        No. of Entertainment for past 1 year
                    </label>
                    <div class="col-lg-10 text-lg-left text-left">
                        <fieldset class="@error('entertainment_times') form-control is-invalid @enderror">
                            @foreach (config('const.business.entertainment_times') as $key => $value)
                            <label class="radio-inline com_title col-form-label">
                                <input type="radio" name="entertainment_times" value="{{ $value }}" @if($entertainment_times !== null && $entertainment_times == $value) checked @endif>
                                {{ __('label.'. $key) }}
                            </label>
                            @endforeach
                        </fieldset>
                        @error('entertainment_times')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <hr>
                <div class="form-group row ">
                    <label class="col-lg-2 col-form-label text-left">
                        Existence of projects
                    </label>
                    <div class="col-lg-10 text-lg-left text-left">
                        <fieldset class="@error('existence_projects') form-control is-invalid @enderror">
                            @foreach (config('const.business.existence_projects') as $key => $value)
                            <label class="radio-inline com_title col-form-label">
                                <input type="radio" name="existence_projects" value="{{ $value }}" @if($existence_projects !==null &&
                                    $existence_projects==$value) checked @endif>
                                {{ __('label.'. $key) }}
                            </label>
                            @endforeach
                        </fieldset>
                        @error('existence_projects')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <hr>
                <div class="form-group row ">
                    <label class="col-lg-2 col-form-label text-left">
                        Includes its Family/Friend</label>
                    <div class="col-lg-10 text-lg-left text-left">
                        <fieldset class="@error('includes_family') form-control is-invalid @enderror">
                            @foreach (config('const.business.includes_family') as $key => $value)
                            <label class="radio-inline com_title col-form-label">
                                <input type="radio" name="includes_family" value="{{ $value }}" @if($includes_family !==null &&
                                    $includes_family==$value) checked @endif>
                                {{ __('label.'. $key) }}
                            </label>
                            @endforeach
                        </fieldset>
                        @error('includes_family')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label text-left">
                        Project Name</br>
                        <i class="fa fa-asterisk" aria-hidden="true" style="font-size: small;color: #df2333f1;"></i>
                        <label style="color: #df2333f1;">If need</label>
                    </label>
                    <div class="col-lg-10">
                        <textarea id="project_name" name="project_name" class="form-control" rows="2">{{ $project_name }}</textarea>
                    </div>
                </div>
                <hr>
                <div class="form-group row ">
                    <label class="col-lg-2 col-form-label text-left d-flex align-items-left justify-content-left">
                        Reason for the Entertainment
                    </label>
                    <div class="col-lg-10">
                        <textarea id="entertainment_reason" name="entertainment_reason" class="form-control" rows="5">{{ $entertainment_reason }}</textarea>
                    </div>
                </div>
                <hr>
                <div class="form-group row ">
                    <label class="col-lg-2 col-form-label text-left">
                        Total Number of Person
                    </label>
                    <div class="col-lg-10">
                        <div class="form-group row ">
                            <div class="col-lg-4">
                                <input type="number" name="entertainment_person" class="form-control @error('entertainment_person') is-invalid @enderror"
                                    value="{{ $entertainment_person }}">
                            </div>
                            <label class="col-lg-8 col-form-label com_title text-lg-left text-left">Persons</label>
                        </div>
                        @error('entertainment_person')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <hr>
                <div class="form-group row ">
                    <label class="col-lg-2 col-form-label text-left">Estimated Amount</label>
                    <div class="col-lg-10 text-lg-left text-left">
                        <div class="form-group row ">
                            <div class="col-lg-4">
                                <input type="number" name="est_amount" class="form-control @error('est_amount') is-invalid @enderror"
                                    value="{{ $est_amount }}">
                            </div>
                            <label class="col-lg-8 col-form-label com_title text-lg-left text-left">
                                Per Person(Excluding VND)
                            </label>
                        </div>
                        @error('est_amount')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <hr>
                <div class="form-group row ">
                    <label
                        class="col-lg-2 col-form-label text-left text-danger d-flex align-items-left justify-content-left">
                        Describe if the amount per person exceeds 4mil VND (PO:2mil VND)
                    </label>
                    <div class="col-lg-10">
                        <textarea id="reason_budget_over" name="reason_budget_over" class="form-control" rows="3">{{ $reason_budget_over }}</textarea>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label for="myfile">{{ __('label.leave.caption.file_path') }}</label>
                    </div>
                    <div class="col-sm-5">
                        {{-- for edit --}}
                        @if(isset($id) && !empty($file_path))
                        @if ($model->file_path === $file_path)
                        <div class="file-show input-group mb-3">
                            <label class="form-control file-link">
                                <a id="file_link" href="{{ Storage::url($file_path) }}" target="_blank">
                                    {{ basename($file_path) }}
                                </a>
                            </label>
                            <label class="custom-file-upload file-remove">
                                <i class="fas fa-trash"></i>
                            </label>
                        </div>
                        @endif
                        @endif
                        <div class="file-block input-group mb-3
                                        @php
                                            if((isset($id) && !empty($file_path))){
                                                if($model->file_path === $file_path){
                                                    echo 'd-none';
                                                }
                                            }
                                        @endphp">
                            <label for="input_file"
                                class="form-control file-name @error('input_file') is-invalid @enderror"
                                place-holder="{{ __('label.choose_file') }}">
                                @if (old('input_file') != null)
                                {{ old('input_file') }}
                                @else
                                {{ __('label.choose_file') }}
                                @endif
                            </label>
                            <label for="input_file" class="custom-file-upload">
                                <i class="fas fa-paperclip"></i>
                            </label>
                            <input type="file" id="input_file" name="input_file" />
                            <label class="custom-file-upload file-remove">
                                <i class="fas fa-trash"></i>
                            </label>
                        </div>
                        @error('input_file')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <input type="hidden" id="file_path" name="file_path" value="{{ $file_path }}">
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label style="color: red;">{{ __('label.leave.caption.subsequent') }}</label>
                    </div>
                    <div class="col-sm-10">
                        <div class="form-check">
                            <input type="checkbox" id="subsequent" name="subsequent" class="form-check-input" @if(old('subsequent') !=null) checked @endif>
                            <label class="form-check-label" for="subsequent">{{ __('label.on') }}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
        <div>
            <button type="submit" name="apply" value="apply" class="btn btn-apply btn-custom">
                <i class="far fa-check-circle" style="margin-right: 5px;"></i>
                {{ __('label.button.apply') }}
            </button>
            <button type="submit" name="draft" value="draft" class="btn btn-draft btn-custom">
                <i class="nav-icon fas fa-edit" style="margin-right: 5px;"></i>
                {{ __('label.button.draft') }}
            </button>
            <a href="{{ route('user.form.index') }}" class="btn btn-cancel btn-custom">
                <i class="fa fa-ban" aria-hidden="true" style="margin-right: 5px;"></i>
                {{ __('label.button.cancel') }}
            </a>
        </div>
        <br>
        <br>
    </form>
</section>
@endsection