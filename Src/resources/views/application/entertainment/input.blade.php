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
{{-- cleave js --}}
<script src="js/cleave/cleave.min.js"></script>
{{-- for this view --}}
<script src="js/user/application/entertainment/input.js"></script>

<script>
    // list of name companies
    const _COMPANIES = @json($companies);
    const _REASON_OTHER = @json(config('const.entertainment.reason.other'));
    const _PREVIEW_FLG = @json(isset($previewFlg) ? true : false);
</script>
@endsection

@section('content')
@php

    $infos                      = Session::exists('inputs') ? Session::get('inputs')['infos']                      : (isset($application) ? $application->entertainment->entertainmentinfos : null);
    $entertainment_dt           = Session::exists('inputs') ? Session::get('inputs')['entertainment_dt']           : (isset($application) ? $application->entertainment->entertainment_dt : null);
    $place                      = Session::exists('inputs') ? Session::get('inputs')['place']                      : (isset($application) ? $application->entertainment->place : null);
    $during_trip                = Session::exists('inputs') ? Session::get('inputs')['during_trip']                : (isset($application) ? $application->entertainment->during_trip : null);
    $check_row                  = Session::exists('inputs') ? Session::get('inputs')['check_row']                  : (isset($application) ? $application->entertainment->check_row : null);
    $has_et_times               = Session::exists('inputs') ? Session::get('inputs')['has_entertainment_times']    : (isset($application) ? $application->entertainment->has_entertainment_times : null);
    $et_times                   = Session::exists('inputs') ? Session::get('inputs')['entertainment_times']        : (isset($application) ? $application->entertainment->entertainment_times : null);
    $existence_projects         = Session::exists('inputs') ? Session::get('inputs')['existence_projects']         : (isset($application) ? $application->entertainment->existence_projects : null);
    $includes_family            = Session::exists('inputs') ? Session::get('inputs')['includes_family']            : (isset($application) ? $application->entertainment->includes_family : null);
    $project_name               = Session::exists('inputs') ? Session::get('inputs')['project_name']               : (isset($application) ? $application->entertainment->project_name : null);
    $entertainment_reason       = Session::exists('inputs') ? Session::get('inputs')['entertainment_reason']       : (isset($application) ? $application->entertainment->entertainment_reason : null);
    $entertainment_reason_other = Session::exists('inputs') ? Session::get('inputs')['entertainment_reason_other'] :(isset($application)  ? $application->entertainment->entertainment_reason_other : null);
    $entertainment_person       = Session::exists('inputs') ? Session::get('inputs')['entertainment_person']       : (isset($application) ? $application->entertainment->entertainment_person : null);
    $est_amount                 = Session::exists('inputs') ? Session::get('inputs')['est_amount']                 : (isset($application) ? $application->entertainment->est_amount : null);
    $reason_budget_over         = Session::exists('inputs') ? Session::get('inputs')['reason_budget_over']         : (isset($application) ? $application->entertainment->reason_budget_over : null);
    $budget_position            = Session::exists('inputs') ? Session::get('inputs')['budget_position']            : (isset($application) ? $application->budget_position : null);
    $subsequent                 = Session::exists('inputs') ? Session::get('inputs')['subsequent']                 : (isset($application) ? $application->subsequent : null);
    $file_path                  = Session::exists('inputs') ? Session::get('inputs')['file_path']                  : (isset($application) ? $application->file_path : null);

    // get action url
    if(isset($application)){
        if(isset($previewFlg)){
            $actionUrl = route('user.entertainment.preview.pdf', $application->id);
        } else {
            $actionUrl = route('user.entertainment.update', $application->id);
        }
    } else {
        $actionUrl = route('user.entertainment.store');
    }
@endphp
<section class="content">
    <x-alert />
    <form method="POST"
        action="{{ $actionUrl }}"
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
                @if (isset($application))
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
                                    data-target="#datetime" @if(isset($previewFlg)) readonly @endif/>
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
                        <input type="text" name="place" class="form-control @error('place') is-invalid @enderror" value="{{ $place }}"
                            @if(isset($previewFlg)) readonly @endif>
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
                                    <input type="radio" name="rd_during_trip" value="{{ $value }}" @if($during_trip !== null && $during_trip == $value) checked @endif
                                        @if(isset($previewFlg)) disabled @endif>
                                    {{ __('label.'. $key) }}
                                </label>
                            @endforeach
                        </fieldset>
                        <input type="hidden" id="during_trip" name="during_trip" value="{{ $during_trip }}">
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
                                @if(!isset($previewFlg))
                                <div class="d-delete d-flex justify-content-end @if(count($infos) === 1 && $key === 0) d-none @endif">
                                    <button class="btnDelete btn btn-danger btn-sm pt-0 pb-0 pl-3 pr-3 mb-1">
                                        {{ __('label.button.delete') }}
                                    </button>
                                </div>
                                @endif
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label com_title text-left">{{ __('label.entertainment.cp_name') }}</label>
                                    <div class="col-lg-10">
                                        <div id="scrollable-dropdown-menu">
                                            <input type="text" class="form-control cp_name @error('infos.'.$key.'.cp_name') is-invalid @enderror"
                                                name="infos[{{ $key }}][cp_name]" autocomplete="off" value="{{ $infos[$key]['cp_name'] }}"
                                                @if(isset($previewFlg)) readonly @endif>
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
                                            value="{{ $infos[$key]['title'] }}"
                                            @if(isset($previewFlg)) readonly @endif>
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
                                            value="{{ $infos[$key]['name_attendants'] }}"
                                            @if(isset($previewFlg)) readonly @endif>
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
                                            value="{{ $infos[$key]['details_dutles'] }}"
                                            @if(isset($previewFlg)) readonly @endif>
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
                                            <input type="text" class="form-control cp_name" name="infos[0][cp_name]" autocomplete="off"
                                                @if(isset($previewFlg)) readonly @endif>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label class="col-lg-2 col-form-label com_title text-left">{{ __('label.entertainment.title') }}</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control title" name="infos[0][title]" autocomplete="off"
                                            @if(isset($previewFlg)) readonly @endif>
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label class="col-lg-2 col-form-label com_title text-left">{{ __('label.entertainment.name_attendants') }}</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control name_attendants" name="infos[0][name_attendants]" autocomplete="off"
                                            @if(isset($previewFlg)) readonly @endif>
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label class="col-lg-2 col-form-label com_title text-left">{{ __('label.entertainment.details_dutles') }}</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control details_dutles" name="infos[0][details_dutles]" autocomplete="off"
                                            @if(isset($previewFlg)) readonly @endif>
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
                                        <input type="text" class="form-control cp_name" autocomplete="off" @if(isset($previewFlg)) readonly @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label class="col-lg-2 col-form-label com_title text-left">{{ __('label.entertainment.title') }}</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control title" autocomplete="off" @if(isset($previewFlg)) readonly @endif>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label class="col-lg-2 col-form-label com_title text-left">{{ __('label.entertainment.name_attendants') }}</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control name_attendants" autocomplete="off" @if(isset($previewFlg)) readonly @endif>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label class="col-lg-2 col-form-label com_title text-left">{{ __('label.entertainment.details_dutles') }}</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control details_dutles" autocomplete="off" @if(isset($previewFlg)) readonly @endif>
                                </div>
                            </div>
                        </div>
                        @if(!isset($previewFlg))
                        <button id="btnAdd" class="btn btn-outline-dark">+ {{ __('label.button.addnew') }}</button>
                        @endif
                        <!-- ./form chil -->
                    </div>
                </div>
                <hr>
                <div class="form-group row ">
                    <label class="col-lg-2 col-form-label text-left">
                        {{ __('label.entertainment.budget_position') }}
                    </label>
                    <div class="col-lg-10 text-lg-left text-left">
                        <fieldset class="@error('budget_position') form-control is-invalid @enderror">
                            <label class="radio-inline com_title col-form-label">
                                @php
                                $val = config('const.budget.position.po');
                                $key = array_search($val, config('const.budget.position'));
                                @endphp
                                <input type="radio" name="rd_budget_position" value="{{ $val }}"
                                    @if($budget_position !==null && $budget_position==$val) checked @endif
                                    @if(isset($previewFlg)) disabled @endif>
                                {{ __('label.budget.'. $key) }}
                            </label>
                            <label class="radio-inline com_title col-form-label">
                                @php
                                $val = config('const.budget.position.not_po');
                                $key = array_search($val, config('const.budget.position'));
                                @endphp
                                <input type="radio" name="rd_budget_position" value="{{ $val }}"
                                    @if($budget_position !==null && $budget_position==$val) checked @endif
                                    @if(isset($previewFlg)) disabled @endif>
                                {{ __('label.budget.'. $key) }}
                            </label>
                        </fieldset>
                        <input type="hidden" id="budget_position" name="budget_position" value="{{ $budget_position }}">
                        @error('budget_position')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
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
                                <input type="radio" name="rd_check_row" value="{{ $value }}" @if($check_row !== null && $check_row == $value) checked @endif
                                    @if(isset($previewFlg)) disabled @endif>
                                {{ __('label.'. $key) }}
                            </label>
                            @endforeach
                        </fieldset>
                        <input type="hidden" id="check_row" name="check_row" value="{{ $check_row }}">
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
                                <input type="radio" name="rd_has_entertainment_times" value="{{ $value }}" @if($has_et_times !== null && $has_et_times == $value) checked @endif
                                    @if(isset($previewFlg)) disabled @endif>
                                {{ __('label.'. $key) }}
                            </label>
                            @endforeach
                        </fieldset>
                        <input type="hidden" id="has_entertainment_times" name="has_entertainment_times" value="{{ $has_et_times }}">
                        @error('has_entertainment_times')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <div style="padding-left: 0px" class="col-lg-12">
                            <input type="text" id="txt_entertainment_times" class="form-control entertainment_times @error('entertainment_times') is-invalid @enderror"
                                value="{{ $et_times }}" placeholder="{{ __('label.entertainment.entertainment_times') }}"
                                @if(isset($previewFlg) || (empty($has_et_times) || ($has_et_times !== null && $has_et_times == config('const.entertainment.has_et_times.no')))) readonly @endif>
                                <input type="hidden" name="entertainment_times" value="{{ $et_times }}">
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
                                <input type="radio" name="rd_existence_projects" value="{{ $value }}" @if($existence_projects !==null &&
                                    $existence_projects==$value) checked @endif
                                    @if(isset($previewFlg)) disabled @endif>
                                {{ __('label.'. $key) }}
                            </label>
                            @endforeach
                        </fieldset>
                        <input type="hidden" id="existence_projects" name="existence_projects" value="{{ $existence_projects }}">
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
                                <input type="radio" name="rd_includes_family" value="{{ $value }}" @if($includes_family !==null &&
                                    $includes_family==$value) checked @endif
                                    @if(isset($previewFlg)) disabled @endif>
                                {{ __('label.'. $key) }}
                            </label>
                            @endforeach
                        </fieldset>
                        <input type="hidden" id="includes_family" name="includes_family" value="{{ $includes_family }}">
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
                        <textarea id="project_name" name="project_name" class="form-control" rows="2"
                            @if(isset($previewFlg)) readonly @endif>{{ $project_name }}</textarea>
                    </div>
                </div>
                <hr>
                <div class="form-group row ">
                    <label class="col-lg-2 col-form-label text-left d-flex align-items-left justify-content-left">
                        {{ __('label.entertainment.entertainment_reason') }}
                    </label>
                    <div class="col-lg-10">
                        <select name="rd_entertainment_reason" style="width: auto;" class="form-control @error('entertainment_reason') is-invalid @enderror"
                            @if(isset($previewFlg)) disabled @endif>
                            <option value="empty" @if($entertainment_reason == 'empty' ) selected @endif>
                                {{ __('label.select') }}
                            </option>
                            @foreach (config('const.entertainment.reason') as $key => $value)
                            <option value="{{ $value }}"
                                @if($entertainment_reason !==null && strval($entertainment_reason)===strval($value)) selected @endif>
                                {{ __('label.entertainment.reason.'.$value) }}
                            </option>
                            @endforeach
                        </select>
                        <input type="hidden" id="entertainment_reason" name="entertainment_reason" value="{{ $entertainment_reason }}">
                        @error('entertainment_reason')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <div style="margin-top: 10px">
                            <textarea id="entertainment_reason_other" name="entertainment_reason_other" class="form-control @error('entertainment_reason_other') is-invalid @enderror"
                                rows="3" placeholder="{{ __('label.entertainment.entertainment_reason_other') }}"
                                @if(isset($previewFlg)) readonly @endif>{{ $entertainment_reason_other }}</textarea>
                            @error('entertainment_reason_other')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
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
                                <input type="text" class="form-control entertainment_person @error('entertainment_person') is-invalid @enderror"
                                    value="{{ $entertainment_person }}" @if(isset($previewFlg)) readonly @endif>
                                    <input type="hidden" name="entertainment_person" value="{{ $entertainment_person }}">
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
                                <input type="text" class="form-control est_amount @error('est_amount') is-invalid @enderror" max-number="9"
                                    value="{{ $est_amount }}" @if(isset($previewFlg)) readonly @endif>
                                <input type="hidden" name="est_amount" value="{{ $est_amount }}">
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
                        <textarea id="reason_budget_over" name="reason_budget_over" class="form-control" rows="3"
                            @if(isset($previewFlg)) readonly @endif>{{ $reason_budget_over }}</textarea>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label for="myfile">{{ __('label.leave.caption.file_path') }}</label>
                    </div>
                    <div class="col-sm-5">
                        @if(isset($previewFlg))
                            @if(isset($application) && !empty($file_path))
                            <div class="file-show input-group mb-3">
                                <label class="form-control file-link">
                                    {{ basename($file_path) }}
                                    <a href="{{ Storage::url($file_path) }}" class="d-none" target="_blank">
                                        {{ basename($file_path) }}
                                    </a>
                                </label>
                            </div>
                            @else
                            <div>{{ __('label.no_attached_file') }}</div>
                            @endif
                        @else
                            {{-- for edit --}}
                            @if(isset($application) && !empty($file_path))
                            @if ($application->file_path === $file_path)
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
                                                if((isset($application) && !empty($file_path))){
                                                    if($application->file_path === $file_path){
                                                        echo 'd-none';
                                                    }
                                                }
                                            @endphp">
                                <label for="input_file" class="form-control file-name @error('input_file') is-invalid @enderror"
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
                        @endif
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label style="color: red;">{{ __('label.leave.caption.subsequent') }}</label>
                    </div>
                    <div class="col-sm-10">
                        <div class="form-check">
                            <input type="checkbox" id="cb_subsequent" name="cb_subsequent" class="form-check-input"
                                    @if($subsequent !== null && $subsequent == config('const.check.on')) checked @endif
                                    @if(isset($previewFlg)) disabled @endif>
                                <input type="hidden" id="subsequent" name="subsequent" value="{{ $subsequent }}">
                            <label class="form-check-label" for="cb_subsequent">{{ __('label.on') }}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
        @if (!isset($previewFlg))
        <div>
            <button type="button" name="apply" value="apply" class="btn btn-apply btn-custom"
                data-toggle="modal" data-target="#popup-confirm">
                <i class="far fa-check-circle" style="margin-right: 5px;"></i>
                {{ __('label.button.apply') }}
            </button>
            <button type="button" name="draft" value="draft" class="btn btn-draft btn-custom"
                data-toggle="modal" data-target="#popup-confirm">
                <i class="nav-icon fas fa-edit" style="margin-right: 5px;"></i>
                {{ __('label.button.draft') }}
            </button>
            <a href="{{ route('user.form.index') }}" class="btn btn-cancel btn-custom">
                <i class="fa fa-ban" aria-hidden="true" style="margin-right: 5px;"></i>
                {{ __('label.button.cancel') }}
            </a>
        </div>
        @endif
        <br>
        <br>
    </form>
</section>
@endsection