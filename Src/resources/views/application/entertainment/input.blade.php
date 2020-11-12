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

$trans = Session::has('inputs') ? Session::get('inputs')['trans'] : (isset($model) ? $model->transportations : null);
$destinations = Session::has('inputs') ? Session::get('inputs')['destinations'] : (isset($model) ? $model->destinations
: null);
$trip_dt_from = Session::has('inputs') ? Session::get('inputs')['trip_dt_from'] : (isset($model) ? $model->trip_dt_from
: null);
$trip_dt_to = Session::has('inputs') ? Session::get('inputs')['trip_dt_to'] : (isset($model) ? $model->trip_dt_to :
null);
$accommodation = Session::has('inputs') ? Session::get('inputs')['accommodation'] : (isset($model) ?
$model->accommodation : null);
$accompany = Session::has('inputs') ? Session::get('inputs')['accompany'] : (isset($model) ? $model->accompany : null);
$borne_by = Session::has('inputs') ? Session::get('inputs')['borne_by'] : (isset($model) ? $model->borne_by : null);
$comment = Session::has('inputs') ? Session::get('inputs')['comment'] : (isset($model) ? $model->comment : null);
$file_path = Session::has('inputs') ? Session::get('inputs')['file_path'] : (isset($model) ? $model->file_path : null);

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
                            <input type="hidden" id="entertainment_dt" name="entertainment_dt">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group row ">
                    <label class="col-lg-2 col-form-label text-left">Place</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" name="place">
                    </div>
                </div>
                <hr>
                <div class="form-group row ">
                    <label class="col-lg-2 col-form-label text-left">During biz trip</label>
                    <div class="col-lg-10 text-lg-left text-left">
                        <label class="radio-inline com_title col-form-label">
                            <input type="radio" name="during_trip" checked>
                            Yes
                        </label>
                        <label class="radio-inline com_title col-form-label">
                            <input type="radio" name="during_trip">
                            No
                        </label>
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
                        <label class="radio-inline com_title col-form-label">
                            <input type="radio" name="check_row" checked>
                            Yes
                        </label>
                        <label class="radio-inline com_title col-form-label">
                            <input type="radio" name="check_row">
                            No
                        </label>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label text-left">
                        No. of Entertainment for past 1 year
                    </label>
                    <div class="col-lg-10 text-lg-left text-left">
                        <label class="radio-inline com_title col-form-label">
                            <input type="radio" name="optradio3" checked>
                            Yes
                        </label>
                        <label class="radio-inline com_title col-form-label">
                            <input type="radio" name="optradio3">
                            No
                        </label>
                    </div>
                </div>
                <hr>
                <div class="form-group row ">
                    <label class="col-lg-2 col-form-label text-left">
                        Existence of projects
                    </label>
                    <div class="col-lg-10 text-lg-left text-left">
                        <label class="radio-inline com_title col-form-label">
                            <input type="radio" name="existence_projects" checked>
                            Yes
                        </label>
                        <label class="radio-inline com_title col-form-label">
                            <input type="radio" name="existence_projects">
                            No
                        </label>
                    </div>
                </div>
                <hr>
                <div class="form-group row ">
                    <label class="col-lg-2 col-form-label text-left">
                        Includes its Family/Friend</label>
                    <div class="col-lg-10 text-lg-left text-left">
                        <label class="radio-inline com_title col-form-label">
                            <input type="radio" name="includes_family" checked>
                            Yes
                        </label>
                        <label class="radio-inline com_title col-form-label">
                            <input type="radio" name="includes_family">
                            No
                        </label>
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
                        <textarea id="text_content" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <hr>
                <div class="form-group row ">
                    <label class="col-lg-2 col-form-label text-left d-flex align-items-left justify-content-left">
                        Reason for the Entertainment
                    </label>
                    <div class="col-lg-10">
                        <textarea id="text_content" class="form-control" rows="5"></textarea>
                    </div>
                </div>
                <hr>
                <div class="form-group row ">
                    <label class="col-lg-2 col-form-label text-left ">
                        Total Number of Person
                    </label>
                    <div class="col-lg-10">
                        <div class="form-group row ">
                            <div class="col-lg-4"><input type="text" class="form-control"></div>
                            <label class="col-lg-8 col-form-label com_title text-lg-left text-left">Persons</label>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group row ">
                    <label class="col-lg-2 col-form-label text-left">Estimated Amount</label>
                    <div class="col-lg-10 text-lg-left text-left">
                        <div class="form-group row ">
                            <div class="col-lg-4"><input type="text" class="form-control"></div>
                            <label class="col-lg-8 col-form-label com_title text-lg-left text-left">
                                Per Person(Excluding VND)
                            </label>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group row ">
                    <label
                        class="col-lg-2 col-form-label text-left text-danger d-flex align-items-left justify-content-left">
                        Describe if the amount per person exceeds 4mil VND (PO:2mil VND)
                    </label>
                    <div class="col-lg-10">
                        <textarea id="text_content" class="form-control" rows="3"></textarea>
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