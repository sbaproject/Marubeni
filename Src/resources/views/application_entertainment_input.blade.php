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
{{-- numeral --}}
<script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
{{-- for this view --}}
<script src="js/user/application/entertainment/input.js"></script>

<script>
    const _COMPANIES    = @json($companies);
    const _REASON_OTHER = @json(config('const.entertainment.reason.other'));
    const _PREVIEW_FLG  = @json($previewFlg);
    const _BUDGET_TYPE  = @json(config('const.budget.position'));
</script>
@endsection

@section('content-header')
{{ __('label.form_entertainment') }}
@endsection

@section('content-breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('user.form.index') }}">{{ __('label.application_list') }}</a></li>
<li class="breadcrumb-item active">{{ __('label.form_entertainment') }}</li>
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
    $subsequent_reason          = Session::exists('inputs') ? Session::get('inputs')['subsequent_reason']          : (isset($application) ? $application->subsequent_reason : null);
    $file_path                  = Session::exists('inputs') ? Session::get('inputs')['file_path']                  : (isset($application) ? $application->file_path : null);

    // get action url
    if(isset($application)){
        if($previewFlg){
            $actionUrl = route('user.entertainment.preview.pdf', $application->id);
        } else {
            $actionUrl = route('user.entertainment.update', $application->id);
        }
    } else {
        $actionUrl = route('user.entertainment.store');
    }
@endphp
<section class="content">
    {{-- <x-alert /> --}}
    <form method="POST"
        action="{{ $actionUrl }}"
        enctype="multipart/form-data">
        @csrf
        <div class="invoice mb-3">
            <div class="card-body">
                <div class="form-group row float-right">
                    @if (isset($application))
                        @if ($application->status != config('const.application.status.draft'))
                            <a class="btn bg-gradient-success @if($showButtonSettlementFlg) isDisabled @endif" href="{{ route('user.entertainment2.show', $application->id) }}"
                                style="margin-right: 10px">
                                <i class="fas fa-dollar-sign" style="margin-right: 5px; color: #fff;"></i>
                                {{ __('label.settlement') }}
                            </a>
                        @endif
                    @endif
                    <button type="submit" id="btnPdf" value="pdf" class="btn bg-gradient-danger" href="#">
                        <i class="fas fa-external-link-alt" style="margin-right: 5px; color: #fff;"></i>
                        {{ __('label.button_export') }}
                    </button>
                </div>
                <div class="clearfix"></div>
                @if (isset($application))
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>{{ __('label.status_caption') }}</label>
                    </div>
                    <div class="col-sm-10">
                        {!! Common::generateStatusApplicationBadgeStyle($application->status, $application->current_step) !!}
                    </div>
                </div>
                <hr>
                @endif
                @if (isset($application))
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>{{ __('label.entertainment_application_no') }}</label>
                    </div>
                    <div class="col-sm-10">
                        {{ $application->application_no }}
                    </div>
                </div>
                <hr>
                @endif
                <div class="form-group row ">
                    <label class="col-lg-2 col-form-label text-left">
                        {{ __('label.entertainment_entertainment_dt') }}<span class="text-danger required"> (*)</span>
                    </label>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <div id="datetime" data-target-input="nearest" class="input-group date">
                                <input type="text"
                                    class="form-control datetimepicker-input @error('entertainment_dt') is-invalid @enderror"
                                    data-target="#datetime" @if($previewFlg) readonly @endif/>
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
                    <label class="col-lg-2 col-form-label text-left">
                        {{ __('label.entertainment_place') }}<span class="text-danger required"> (*)</span>
                    </label>
                    <div class="col-lg-10">
                        <input type="text" name="place" class="form-control @error('place') is-invalid @enderror" value="{{ $place }}"
                            @if($previewFlg) readonly @endif>
                        @error('place')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <hr>
                <div class="form-group row ">
                    <label class="col-lg-2 col-form-label text-left">
                        {{ __('label.entertainment_during_trip') }}<span class="text-danger required"> (*)</span>
                    </label>
                    <div class="col-lg-10 text-lg-left text-left">
                        <fieldset class="@error('during_trip') form-control is-invalid @enderror">
                            @foreach (config('const.entertainment.during_trip') as $key => $value)
                                <label class="radio-inline com_title col-form-label">
                                    <input type="radio" name="rd_during_trip" value="{{ $value }}" @if($during_trip !== null && $during_trip == $value) checked @endif
                                        @if($previewFlg) disabled @endif>
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
                    <label class="col-lg-2 col-form-label text-left">
                        {{ __('label.entertainment_entrainment_infomation') }}<span class="text-danger required"> (*)</span>
                    </label>
                    <div class="col-lg-10">
                        <div id="infos_block">
                            @if (!empty($infos))
                            @foreach ($infos as $key => $value)
                            <div class="card card-body card-company">
                                @if(!$previewFlg)
                                <div class="d-delete d-flex justify-content-end @if(count($infos) === 1 && $key === 0) d-none @endif">
                                    <button class="btnDelete btn bg-gradient-danger btn-sm pt-0 pb-0 pl-3 pr-3 mb-1">
                                        {{ __('label.button_delete') }}
                                    </button>
                                </div>
                                @endif
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label com_title text-left">
                                        {{ __('label.entertainment_cp_name') }}<span class="text-danger required"> (*)</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <div id="scrollable-dropdown-menu">
                                            <input type="text" class="form-control cp_name @error('infos.'.$key.'.cp_name') is-invalid @enderror"
                                                name="infos[{{ $key }}][cp_name]" autocomplete="off" value="{{ $infos[$key]['cp_name'] }}"
                                                @if($previewFlg) readonly @endif>
                                        </div>
                                        @error('infos.'.$key.'.cp_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label class="col-lg-3 col-form-label com_title text-left">
                                        {{ __('label.entertainment_title') }}<span class="text-danger required"> (*)</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <input type="text" class="form-control title @error('infos.'.$key.'.title') is-invalid @enderror"
                                            name="infos[{{ $key }}][title]" autocomplete="off"
                                            value="{{ $infos[$key]['title'] }}"
                                            @if($previewFlg) readonly @endif>
                                        @error('infos.'.$key.'.title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label class="col-lg-3 col-form-label com_title text-left">
                                        {{ __('label.entertainment_name_attendants') }}<span class="text-danger required"> (*)</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <input type="text" class="form-control name_attendants @error('infos.'.$key.'.name_attendants') is-invalid @enderror"
                                            name="infos[{{ $key }}][name_attendants]" autocomplete="off"
                                            value="{{ $infos[$key]['name_attendants'] }}"
                                            @if($previewFlg) readonly @endif>
                                        @error('infos.'.$key.'.name_attendants')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label class="col-lg-3 col-form-label com_title text-left">
                                        {{ __('label.entertainment_details_dutles') }}<span class="text-danger required"> (*)</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <input type="text" class="form-control details_dutles @error('infos.'.$key.'.details_dutles') is-invalid @enderror"
                                            name="infos[{{ $key }}][details_dutles]" autocomplete="off"
                                            value="{{ $infos[$key]['details_dutles'] }}"
                                            @if($previewFlg) readonly @endif>
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
                                    <button class="btnDelete btn bg-gradient-danger btn-sm pt-0 pb-0 pl-3 pr-3 mb-1">
                                        {{ __('label.button_delete') }}
                                    </button>
                                </div>
                                <div class="form-group row ">
                                    <label class="col-lg-3 col-form-label com_title text-left">
                                        {{ __('label.entertainment_cp_name') }}<span class="text-danger required"> (*)</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <div id="scrollable-dropdown-menu">
                                            <input type="text" class="form-control cp_name" name="infos[0][cp_name]" autocomplete="off"
                                                @if($previewFlg) readonly @endif>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label class="col-lg-3 col-form-label com_title text-left">
                                        {{ __('label.entertainment_title') }}<span class="text-danger required"> (*)</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <input type="text" class="form-control title" name="infos[0][title]" autocomplete="off"
                                            @if($previewFlg) readonly @endif>
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label class="col-lg-3 col-form-label com_title text-left">
                                        {{ __('label.entertainment_name_attendants') }}<span class="text-danger required"> (*)</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <input type="text" class="form-control name_attendants" name="infos[0][name_attendants]" autocomplete="off"
                                            @if($previewFlg) readonly @endif>
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label class="col-lg-3 col-form-label com_title text-left">
                                        {{ __('label.entertainment_details_dutles') }}<span class="text-danger required"> (*)</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <input type="text" class="form-control details_dutles" name="infos[0][details_dutles]" autocomplete="off"
                                            @if($previewFlg) readonly @endif>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <!-- form chil -->
                        <div class="card card-body card-company copy d-none">
                            <div class="d-delete d-flex justify-content-end">
                                <button class="btnDelete btn bg-gradient-danger btn-sm pt-0 pb-0 pl-3 pr-3 mb-1">
                                    {{ __('label.button_delete') }}
                                </button>
                            </div>
                            <div class="form-group row ">
                                <label class="col-lg-3 col-form-label com_title text-left">
                                    {{ __('label.entertainment_cp_name') }}<span class="text-danger required"> (*)</span>
                                </label>
                                <div class="col-lg-9">
                                    <div id="scrollable-dropdown-menu">
                                        <input type="text" class="form-control cp_name" autocomplete="off" @if($previewFlg) readonly @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label class="col-lg-3 col-form-label com_title text-left">
                                    {{ __('label.entertainment_title') }}<span class="text-danger required"> (*)</span>
                                </label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control title" autocomplete="off" @if($previewFlg) readonly @endif>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label class="col-lg-3 col-form-label com_title text-left">
                                    {{ __('label.entertainment_name_attendants') }}<span class="text-danger required"> (*)</span>
                                </label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control name_attendants" autocomplete="off" @if($previewFlg) readonly @endif>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label class="col-lg-3 col-form-label com_title text-left">
                                    {{ __('label.entertainment_details_dutles') }}<span class="text-danger required"> (*)</span>
                                </label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control details_dutles" autocomplete="off" @if($previewFlg) readonly @endif>
                                </div>
                            </div>
                        </div>
                        @if(!$previewFlg)
                        <button id="btnAdd" class="btn bg-gradient-danger @if(!empty($infos) && count($infos) >= 4) d-none @endif">
                            + {{ __('label.button_addnew') }}
                        </button>
                        @endif
                        <!-- ./form chil -->
                    </div>
                </div>
                <hr>
                <div class="form-group row ">
                    <label class="col-lg-2 col-form-label text-left">
                        {{ __('label.entertainment_budget_position') }}<span class="text-danger required"> (*)</span>
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
                                    @if($previewFlg) disabled @endif>
                                {{ __('label.budget_'. $key) }}
                            </label>
                            <label class="radio-inline com_title col-form-label">
                                @php
                                $val = config('const.budget.position.not_po');
                                $key = array_search($val, config('const.budget.position'));
                                @endphp
                                <input type="radio" name="rd_budget_position" value="{{ $val }}"
                                    @if($budget_position !==null && $budget_position==$val) checked @endif
                                    @if($previewFlg) disabled @endif>
                                {{ __('label.budget_'. $key) }}
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
                        {{ __('label.entertainment_check_row') }}<span id="rq-check-row" class="text-danger required"> (*)</span>
                    </label>
                    <div class="col-lg-10 text-lg-left text-left">
                        <fieldset id="fs_check_row" class="@error('check_row') form-control is-invalid @enderror">
                            @foreach (config('const.entertainment.check_row') as $key => $value)
                            <label class="radio-inline com_title col-form-label">
                                <input type="radio" name="rd_check_row" value="{{ $value }}" @if($check_row !== null && $check_row == $value) checked @endif
                                    @if($previewFlg) disabled @endif>
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
                        {{ __('label.entertainment_entertainment_times') }}<span id="rq-has-et_times" class="text-danger required"> (*)</span>
                    </label>
                    <div class="col-lg-10 text-lg-left text-left">
                        <fieldset id="fs_has_entertainment_times" class="@error('has_entertainment_times') form-control is-invalid @enderror">
                            @foreach (config('const.entertainment.has_et_times') as $key => $value)
                            <label class="radio-inline com_title col-form-label">
                                <input type="radio" name="rd_has_entertainment_times" value="{{ $value }}" @if($has_et_times !== null && $has_et_times == $value) checked @endif
                                    @if($previewFlg) disabled @endif>
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
                        <div class="form-group row mt-1">
                            <div style="padding-left: 0px;margin-left:7.5px;" class="col-md-3">
                                <input type="text" id="txt_entertainment_times"
                                    class="form-control entertainment_times @error('entertainment_times') is-invalid @enderror"
                                    value="{{ $et_times }}" placeholder="{{ __('label.entertainment_entertainment_times') }}" @if($previewFlg
                                    || (empty($has_et_times) || ($has_et_times !==null &&
                                    $has_et_times==config('const.entertainment.has_et_times.no')))) readonly @endif>
                                <input type="hidden" name="entertainment_times" value="{{ $et_times }}">
                                @error('entertainment_times')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <label class="col-md-8 col-form-label com_title text-lg-left text-left">
                                {{ __('label.times') }}<span id="rq_et_times" class="text-danger required"> (*)</span>
                            </label>
                        </div>
                        
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label text-left">
                        {{ __('label.entertainment_existence_projects') }}<span id="rq-exist-project" class="text-danger required"> (*)</span>
                    </label>
                    <div class="col-lg-10 text-lg-left text-left">
                        <fieldset id="fs_existence_projects" class="@error('existence_projects') form-control is-invalid @enderror">
                            @foreach (config('const.entertainment.existence_projects') as $key => $value)
                            <label class="radio-inline com_title col-form-label">
                                <input type="radio" name="rd_existence_projects" value="{{ $value }}" @if($existence_projects !==null &&
                                    $existence_projects==$value) checked @endif
                                    @if($previewFlg) disabled @endif>
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
                        {{ __('label.entertainment_includes_family') }}<span id="rq-include-family" class="text-danger required"> (*)</span>
                    </label>
                    <div class="col-lg-10 text-lg-left text-left">
                        <fieldset id="fs_includes_family" class="@error('includes_family') form-control is-invalid @enderror">
                            @foreach (config('const.entertainment.includes_family') as $key => $value)
                            <label class="radio-inline com_title col-form-label">
                                <input type="radio" name="rd_includes_family" value="{{ $value }}" @if($includes_family !==null &&
                                    $includes_family==$value) checked @endif
                                    @if($previewFlg) disabled @endif>
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
                        {{ __('label.entertainment_project_name') }}
                        {{-- <i class="fa fa-asterisk" aria-hidden="true" style="font-size: small;color: #df2333f1;"></i> --}}
                        {{-- <label style="color: #df2333f1;">{{ __('label.entertainment_if_need') }}</label> --}}
                        <span id="rq-project-name" class="text-danger required"> (*)</span>
                    </label>
                    <div class="col-lg-10">
                        <textarea id="project_name" name="project_name" class="form-control @error('project_name') is-invalid @enderror" rows="2"
                            @if($previewFlg) readonly @endif>{{ $project_name }}</textarea>
                        @error('project_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <hr>
                <div class="form-group row ">
                    <label class="col-lg-2 col-form-label text-left">
                        {{ __('label.entertainment_entertainment_reason') }}<span class="text-danger required"> (*)</span>
                    </label>
                    <div class="col-lg-10">
                        <select name="rd_entertainment_reason" style="width: auto;" class="form-control @error('entertainment_reason') is-invalid @enderror"
                            @if($previewFlg) disabled @endif>
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
                            <textarea id="entertainment_reason_other" name="entertainment_reason_other"
                                class="form-control @error('entertainment_reason_other') is-invalid @enderror"
                                rows="2" placeholder="{{ __('label.entertainment_entertainment_reason_other') }}"
                                @if($previewFlg) readonly @endif>{{ $entertainment_reason_other }}</textarea>
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
                        {{ __('label.entertainment_entertainment_person') }}<span class="text-danger required"> (*)</span>
                    </label>
                    <div class="col-lg-10">
                        <div class="form-group row ">
                            <div class="col-lg-4">
                                <input type="text" class="form-control entertainment_person @error('entertainment_person') is-invalid @enderror"
                                    value="{{ $entertainment_person }}" @if($previewFlg) readonly @endif>
                                    <input type="hidden" name="entertainment_person" value="{{ $entertainment_person }}">
                            </div>
                            <label class="col-lg-8 col-form-label com_title text-lg-left text-left">{{ __('label.entertainment_persons') }}</label>
                        </div>
                        @error('entertainment_person')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row ">
                    <label class="col-lg-2 col-form-label text-left">
                        {{ __('label.entertainment_est_amount') }}<span class="text-danger required"> (*)</span>
                    </label>
                    <div class="col-lg-10 text-lg-left text-left">
                        <div class="form-group row ">
                            <div class="col-lg-4">
                                <input type="text" class="form-control est_amount @error('est_amount') is-invalid @enderror" max-number="9"
                                    value="{{ $est_amount }}" @if($previewFlg) readonly @endif>
                                <input type="hidden" name="est_amount" value="{{ $est_amount }}">
                            </div>
                            <label class="col-lg-8 col-form-label com_title text-lg-left text-left">
                                {{ __('label.entertainment_per_person_excluding_vnd') }}
                            </label>
                        </div>
                        @error('est_amount')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <hr style="border: 1px dashed rgba(0,0,0,.1)">
                        <div style="margin-top: 10px">
                            <b>{{ __('label.entertainment_total_estimated') }} : <span id="total"></span> VND</b>
                        </div>
                    </div>
                </div>
                <div class="form-group row ">
                    <label
                        class="col-lg-2 col-form-label text-left text-danger d-flex align-items-left justify-content-left">
                        {{ __('label.entertainment_reason_budget_over') }}
                    </label>
                    <div class="col-lg-10">
                        <textarea id="reason_budget_over" name="reason_budget_over" class="form-control @error('reason_budget_over') is-invalid @enderror" rows="2"
                            @if($previewFlg) readonly @endif>{{ $reason_budget_over }}</textarea>
                        @error('reason_budget_over')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label for="myfile">{{ __('label.leave.caption_file_path') }}</label>
                    </div>
                    <div class="col-sm-5">
                        @if($previewFlg)
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
                        <label style="color: red;">{{ __('label.leave.caption_subsequent') }}</label>
                    </div>
                    <div class="col-sm-10">
                        <div class="form-check">
                            <input type="checkbox" id="cb_subsequent" name="cb_subsequent" class="form-check-input"
                                    @if($subsequent !== null && $subsequent == config('const.check.on')) checked @endif
                                    @if($previewFlg) disabled @endif>
                                <input type="hidden" id="subsequent" name="subsequent" value="{{ $subsequent }}">
                            <label class="form-check-label" for="cb_subsequent">{{ __('label.on') }}</label>
                        </div>
                        @error('cb_subsequent')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ __('validation.subsequence_required') }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label text-left">
                        {{ __('label.application_subsequent_reason') }}
                    </label>
                    <div class="col-lg-10">
                        <textarea id="subsequent_reason" name="subsequent_reason" class="form-control @error('subsequent_reason') is-invalid @enderror"
                            rows="2" @if($previewFlg) readonly @endif>{{ $subsequent_reason }}</textarea>
                        @error('subsequent_reason')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
        <div class="text-center">
            @if (!$previewFlg)
                <button type="button" name="apply" value="apply" class="btn bg-gradient-success btn-form" data-toggle="modal"
                    data-target="#popup-confirm" @if(Common::detectMobile()) disabled @endif>
                    <i class="far fa-check-circle" style="margin-right: 5px;"></i>
                    {{ __('label.button_apply') }}
                </button>
                @if (!$inProgressFlg)
                <button type="button" name="draft" value="draft" class="btn btn bg-gradient-info btn-form" data-toggle="modal"
                    data-target="#popup-confirm" @if(Common::detectMobile()) disabled @endif>
                    <i class="nav-icon fas fa-edit" style="margin-right: 5px;"></i>
                    {{ __('label.button_draft') }}
                </button>
                @endif
            @endif
            <a href="{{ Common::getHomeUrl() }}" class="btn btn bg-gradient-secondary btn-form btn-cancel">
                <i class="fa fa-ban" aria-hidden="true" style="margin-right: 5px;"></i>
                {{ __('label.button_cancel') }}
            </a>
        </div>
        <br>
        <br>
    </form>
</section>
@endsection