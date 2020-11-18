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
{{-- typehead css --}}
<link rel="stylesheet" href="css/typehead/typehead.css">
@endsection

@section('js')
{{-- datetimepicker --}}
<script src="js/moment/moment.min.js"></script>
<script src="js/moment/locale/{{ config('app.locale') }}.js"></script>
<script src="js/bootstrap-datetimepicker.js"></script>
{{-- typehead js --}}
<script src="js/typehead/typeahead.bundle.min.js"></script>
{{-- for this view --}}
<script src="js/user/application/entertainment/create.js"></script>

<script>
    // list of name companies
    var companies = @json($companies);
</script>
@endsection

@section('content')
@php

$infos                  = Session::has('inputs') ? Session::get('inputs')['infos']                      : (isset($model) ? $model->entertainmentinfos : null);
$entertainment_dt       = Session::has('inputs') ? Session::get('inputs')['entertainment_dt']           : (isset($model) ? $model->entertainment_dt : null);
$place                  = Session::has('inputs') ? Session::get('inputs')['place']                      : (isset($model) ? $model->place : null);
$during_trip            = Session::has('inputs') ? Session::get('inputs')['during_trip']                : (isset($model) ? $model->during_trip : null);
$check_row              = Session::has('inputs') ? Session::get('inputs')['check_row']                  : (isset($model) ? $model->check_row : null);
$has_et_times           = Session::has('inputs') ? Session::get('inputs')['has_entertainment_times']    : (isset($model) ? $model->has_entertainment_times : null);
$et_times               = Session::has('inputs') ? Session::get('inputs')['entertainment_times']        : (isset($model) ? $model->entertainment_times : null);
$existence_projects     = Session::has('inputs') ? Session::get('inputs')['existence_projects']         : (isset($model) ? $model->existence_projects : null);
$includes_family        = Session::has('inputs') ? Session::get('inputs')['includes_family']            : (isset($model) ? $model->includes_family : null);
$project_name           = Session::has('inputs') ? Session::get('inputs')['project_name']               : (isset($model) ? $model->project_name : null);
$entertainment_reason   = Session::has('inputs') ? Session::get('inputs')['entertainment_reason']       : (isset($model) ? $model->entertainment_reason : null);
$entertainment_person   = Session::has('inputs') ? Session::get('inputs')['entertainment_person']       : (isset($model) ? $model->entertainment_person : null);
$est_amount             = Session::has('inputs') ? Session::get('inputs')['est_amount']                 : (isset($model) ? $model->est_amount : null);
$reason_budget_over     = Session::has('inputs') ? Session::get('inputs')['reason_budget_over']         : (isset($model) ? $model->reason_budget_over : null);
$file_path              = Session::has('inputs') ? Session::get('inputs')['file_path']                  : (isset($model) ? $model->file_path : null);

@endphp
<section class="content">
    <x-alert />
    <form method="POST"
        action="@if (isset($id)) {{ route('user.entertainment.update', $id) }} @else {{ route('user.entertainment.store') }} @endif"
        enctype="multipart/form-data">
        @csrf
        <div class="main-top">
            <h4 class="main-header-text">{{ Str::upper(__('label.form.entertainment')) }}</h4>
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
                    <label class="col-lg-2 col-form-label text-left">{{ __('label.entertainment.entertainment_dt') }}</label>
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
                    <label class="col-lg-2 col-form-label text-left">{{ __('label.entertainment.place') }}</label>
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
                    <label class="col-lg-2 col-form-label text-left">{{ __('label.entertainment.during_trip') }}</label>
                    <div class="col-lg-10 text-lg-left text-left">
                        <fieldset class="@error('during_trip') form-control is-invalid @enderror">
                            @foreach (config('const.entertainment.during_trip') as $key => $value)
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
                        {{ __('label.entertainment.entrainment_infomation') }}
                    </label>
                    <div class="col-lg-10">
                        <div id="infos_block">
                            @if (!empty($infos))
                            @foreach ($infos as $key => $value)
                            <div class="card card-body card-company">
                                <div class="d-delete d-flex justify-content-end @if(count($infos) === 1 && $key === 0) d-none @endif">
                                    <button class="btnDelete btn btn-danger btn-sm pt-0 pb-0 pl-3 pr-3 mb-1">
                                        {{ __('label.button.delete') }}
                                    </button>
                                </div>
                                <div class="form-group row ">
                                    <label class="col-lg-2 col-form-label com_title text-left">{{ __('label.entertainment.cp_name') }}</label>
                                    <div class="col-lg-10">
                                        <div id="scrollable-dropdown-menu">
                                            <input type="text" class="form-control cp_name @error('infos.'.$key.'.cp_name') is-invalid @enderror"
                                                name="infos[{{ $key }}][cp_name]" autocomplete="off" value="{{ $infos[$key]['cp_name'] }}">
                                        </div>
                                        @error('infos.'.$key.'.cp_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label class="col-lg-2 col-form-label com_title text-left">{{ __('label.entertainment.title') }}</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control title @error('infos.'.$key.'.title') is-invalid @enderror"
                                            name="infos[{{ $key }}][title]" autocomplete="off"
                                            value="{{ $infos[$key]['title'] }}">
                                        @error('infos.'.$key.'.title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label class="col-lg-2 col-form-label com_title text-left">{{ __('label.entertainment.name_attendants') }}</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control name_attendants @error('infos.'.$key.'.name_attendants') is-invalid @enderror"
                                            name="infos[{{ $key }}][name_attendants]" autocomplete="off"
                                            value="{{ $infos[$key]['name_attendants'] }}">
                                        @error('infos.'.$key.'.name_attendants')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label class="col-lg-2 col-form-label com_title text-left">{{ __('label.entertainment.details_dutles') }}</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control details_dutles @error('infos.'.$key.'.details_dutles') is-invalid @enderror"
                                            name="infos[{{ $key }}][details_dutles]" autocomplete="off"
                                            value="{{ $infos[$key]['details_dutles'] }}">
                                        @error('infos.'.$key.'.details_dutles')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @else
                            <div class="card card-body card-company">
                                <div class="d-delete d-flex justify-content-end d-none">
                                    <button class="btnDelete btn btn-danger btn-sm pt-0 pb-0 pl-3 pr-3 mb-1">
                                        {{ __('label.button.delete') }}
                                    </button>
                                </div>
                                <div class="form-group row ">
                                    <label class="col-lg-2 col-form-label com_title text-left">{{ __('label.entertainment.cp_name') }}</label>
                                    <div class="col-lg-10">
                                        <div id="scrollable-dropdown-menu">
                                            <input type="text" class="form-control cp_name" name="infos[0][cp_name]" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label class="col-lg-2 col-form-label com_title text-left">{{ __('label.entertainment.title') }}</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control title" name="infos[0][title]" autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label class="col-lg-2 col-form-label com_title text-left">{{ __('label.entertainment.name_attendants') }}</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control name_attendants" name="infos[0][name_attendants]" autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label class="col-lg-2 col-form-label com_title text-left">{{ __('label.entertainment.details_dutles') }}</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control details_dutles" name="infos[0][details_dutles]" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <!-- form chil -->
                        <div class="card card-body card-company copy d-none">
                            <div class="d-delete d-flex justify-content-end">
                                <button class="btnDelete btn btn-danger btn-sm pt-0 pb-0 pl-3 pr-3 mb-1">
                                    {{ __('label.button.delete') }}
                                </button>
                            </div>
                            <div class="form-group row ">
                                <label class="col-lg-2 col-form-label com_title text-left">{{ __('label.entertainment.cp_name') }}</label>
                                <div class="col-lg-10">
                                    <div id="scrollable-dropdown-menu">
                                        <input type="text" class="form-control cp_name" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label class="col-lg-2 col-form-label com_title text-left">{{ __('label.entertainment.title') }}</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control title" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label class="col-lg-2 col-form-label com_title text-left">{{ __('label.entertainment.name_attendants') }}</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control name_attendants" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label class="col-lg-2 col-form-label com_title text-left">{{ __('label.entertainment.details_dutles') }}</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control details_dutles" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <button id="btnAdd" class="btn btn-outline-dark">+ {{ __('label.button.addnew') }}</button>
                        <!-- ./form chil -->
                    </div>
                </div>
                <hr>
                <div class="form-group row ">
                    <label class="col-lg-2 col-form-label text-left">
                        {{ __('label.entertainment.check_row') }}
                    </label>
                    <div class="col-lg-10 text-lg-left text-left">
                        <fieldset class="@error('check_row') form-control is-invalid @enderror">
                            @foreach (config('const.entertainment.check_row') as $key => $value)
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
                        {{ __('label.entertainment.entertainment_times') }}
                    </label>
                    <div class="col-lg-10 text-lg-left text-left">
                        <fieldset class="@error('has_entertainment_times') form-control is-invalid @enderror">
                            @foreach (config('const.entertainment.has_et_times') as $key => $value)
                            <label class="radio-inline com_title col-form-label">
                                <input type="radio" name="has_entertainment_times" value="{{ $value }}" @if($has_et_times !== null && $has_et_times == $value) checked @endif>
                                {{ __('label.'. $key) }}
                            </label>
                            @endforeach
                        </fieldset>
                        @error('has_entertainment_times')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <div id="entertainment_times" style="padding-left: 0px"
                            class="col-lg-10 @if(empty($has_et_times) || ($has_et_times !== null && $has_et_times == config('const.entertainment.has_et_times.no'))) d-none @endif">
                            <input type="number" name="entertainment_times" class="form-control @error('entertainment_times') is-invalid @enderror"
                                value="{{ $et_times }}" placeholder="{{ __('label.entertainment.entertainment_times') }}">
                            @error('entertainment_times')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label text-left">
                        {{ __('label.entertainment.existence_projects') }}
                    </label>
                    <div class="col-lg-10 text-lg-left text-left">
                        <fieldset class="@error('existence_projects') form-control is-invalid @enderror">
                            @foreach (config('const.entertainment.existence_projects') as $key => $value)
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
                        {{ __('label.entertainment.includes_family') }}
                    </label>
                    <div class="col-lg-10 text-lg-left text-left">
                        <fieldset class="@error('includes_family') form-control is-invalid @enderror">
                            @foreach (config('const.entertainment.includes_family') as $key => $value)
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
                        {{ __('label.entertainment.project_name') }}</br>
                        <i class="fa fa-asterisk" aria-hidden="true" style="font-size: small;color: #df2333f1;"></i>
                        <label style="color: #df2333f1;">{{ __('label.entertainment.if_need') }}</label>
                    </label>
                    <div class="col-lg-10">
                        <textarea id="project_name" name="project_name" class="form-control" rows="2">{{ $project_name }}</textarea>
                    </div>
                </div>
                <hr>
                <div class="form-group row ">
                    <label class="col-lg-2 col-form-label text-left d-flex align-items-left justify-content-left">
                        {{ __('label.entertainment.entertainment_reason') }}
                    </label>
                    <div class="col-lg-10">
                        <textarea id="entertainment_reason" name="entertainment_reason" class="form-control" rows="5">{{ $entertainment_reason }}</textarea>
                    </div>
                </div>
                <hr>
                <div class="form-group row ">
                    <label class="col-lg-2 col-form-label text-left">
                        {{ __('label.entertainment.entertainment_person') }}
                    </label>
                    <div class="col-lg-10">
                        <div class="form-group row ">
                            <div class="col-lg-4">
                                <input type="number" name="entertainment_person" class="form-control @error('entertainment_person') is-invalid @enderror"
                                    value="{{ $entertainment_person }}">
                            </div>
                            <label class="col-lg-8 col-form-label com_title text-lg-left text-left">{{ __('label.entertainment.persons') }}</label>
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
                    <label class="col-lg-2 col-form-label text-left">{{ __('label.entertainment.est_amount') }}</label>
                    <div class="col-lg-10 text-lg-left text-left">
                        <div class="form-group row ">
                            <div class="col-lg-4">
                                <input type="number" name="est_amount" class="form-control @error('est_amount') is-invalid @enderror"
                                    value="{{ $est_amount }}">
                            </div>
                            <label class="col-lg-8 col-form-label com_title text-lg-left text-left">
                                {{ __('label.entertainment.per_person_excluding_vnd') }}
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
                        {{ __('label.entertainment.reason_budget_over') }}
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