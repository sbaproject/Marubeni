@php
    $entertainment_dt       = Session::exists('inputs') ? Session::get('inputs')['entertainment_dt']            : ($modFlg ? ($application->entertainment2->entertainment_dt ?? null) : ($application->entertainment->entertainment_dt ?? null));
    $entertainmentinfos     = Session::exists('inputs') ? (Session::get('inputs')['entertainmentinfos'] ?? [])  : ($modFlg ? ($application->entertainment2->entertainmentinfos ?? null) : ($application->entertainment->entertainmentinfos ?? null));
    $est_amount             = Session::exists('inputs') ? Session::get('inputs')['est_amount']                  : ($modFlg ? ($application->entertainment2->est_amount ?? null) : ($application->entertainment->est_amount ?? null));
    $entertainment_person   = Session::exists('inputs') ? Session::get('inputs')['entertainment_person']        : ($modFlg ? ($application->entertainment2->entertainment_person ?? null) : ($application->entertainment->entertainment_person ?? null));
    $pay_info               = Session::exists('inputs') ? Session::get('inputs')['pay_info']                    : ($application->entertainment2->pay_info ?? null);
    $chargedbys             = Session::exists('inputs') ? (Session::get('inputs')['chargedbys'] ?? [])          : ($application->entertainment2->chargedbys ?? null);
    $reason_budget_over     = Session::exists('inputs') ? Session::get('inputs')['reason_budget_over']          : ($modFlg ? ($application->entertainment2->reason_budget_over ?? $application->entertainment->reason_budget_over) : ($application->entertainment->reason_budget_over ?? null));
    
    // get action url
    if(isset($modFlg)){
        if($previewFlg){
            $actionUrl = route('user.entertainment2.pdf', $application->id);
        } else {
            $actionUrl = route('user.entertainment2.update', $application->id);
        }
        // $actionUrl = route('user.entertainment2.update', $application->id);
    } else {
        $actionUrl = route('user.entertainment2.store', $application->id);
    }
@endphp
@extends('layouts.master')

@section('title')
{{ __('label.entertainment_settlement') }}
@endsection

@section('css')
{{-- for this view --}}
<link rel="stylesheet" href="css/user/04_leave_application.css">
<style type="text/css">
    .invalid-feedback{
        display: block;
    }
    .d-none{
        display: none !important;
    }
    .caption{
        word-break: break-word;
    }
    .card-entertainment-infos,.card-chargedbys{
        padding: 10px;
        padding-bottom: 0px;
    }
    .card-transportations .badge,.card-communications .badge,.card-accomodations .badge,.card-otherfees .badge {
        line-height: inherit;
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
<script src="js/user/application/entertainment2/input.js"></script>

<script>
    const _COMPANIES = @json($companies);
</script>

@endsection

@section('content-header')
{{ Str::upper(__('label.entertainment_settlement')) }}
@endsection

@section('content-breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('user.form.index') }}">{{ __('label.application_list') }}</a></li>
<li class="breadcrumb-item active">{{ __('label.entertainment_settlement') }}</li>
@endsection

@section('content')
<section class="content leave-application">
    <form
        id="post-form"
        method="POST"
        action="{{ $actionUrl }}"
        enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="pdf_url" value="{{ route('user.entertainment2.pdf', $application->id).'?m=true' }}">
        <div class="invoice mb-3">
            <div class="card-body">
                <div class="form-group row float-right">
                    <button type="submit" id="btnPdf" value="pdf" class="btn bg-gradient-danger" href="#">
                        <i class="fas fa-external-link-alt" style="margin-right: 5px; color: #fff;"></i>
                        {{ __('label.button_export') }}
                    </button>
                </div>
                <div class="clearfix"></div>
                <h3>{{ __('label.application_information') }}</h3>
                <div class="invoice card-body">
                    {{-- Application No --}}
                    <div class="form-group row">
                        <div class="col-md-2 text-right caption">
                            <label>{{ __('label.business_application_no') }}</label>
                        </div>
                        <div class="col-md-10">
                            <span>{{ $application->application_no }}</span>
                        </div>
                    </div>
                    <hr>
                    {{-- Apply Date --}}
                    <div class="form-group row">
                        <div class="col-md-2 text-right caption">
                            <label>{{ __('label.entertainment_applydate') }}</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" readonly
                                value="{{ \Carbon\Carbon::parse($application->created_at)->format('d/m/Y H:i') }}">
                        </div>
                    </div>
                    <hr>
                    {{-- Approver / Date --}}
                    <div class="form-group row">
                        <div class="col-md-2 text-right caption">
                            <label>{{ __('label.entertainment_approver_date') }}</label>
                        </div>
                        <div class="col-md-10">
                            @isset($application->lastapprovalstep1)
                            {{ $application->lastapprovalstep1->approver_name }}
                            /
                            {{ \Carbon\Carbon::parse($application->lastapprovalstep1->created_at)->format('d/m/Y H:i') }}
                            @endisset
                        </div>
                    </div>
                    <hr>
                    {{-- Expected Date --}}
                    <div class="form-group row">
                        <div class="col-md-2 text-right caption">
                            <label>{{ __('label.entertainment_expected_date') }}</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" readonly
                                value="{{ \Carbon\Carbon::parse($application->entertainment->entertainment_dt)->format('d/m/Y H:i') }}">
                        </div>
                    </div>
                    <hr>
                    {{-- Entertainment Infos --}}
                    <div class="form-group row">
                        <div class="col-md-2 text-right caption">
                            <label>{{ __('label.entertainment_entrainment_infomation') }}</label>
                        </div>
                        <div class="col-md-10">
                            @if (!empty($application->entertainment->entertainmentinfos))
                            @foreach ($application->entertainment->entertainmentinfos as $key => $item)
                            <div class="card card-body card-entertainment-infos">
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-left">
                                        {{ __('label.entertainment_cp_name') }}
                                    </label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" readonly value="{{ $item['cp_name'] }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-left">
                                        {{ __('label.entertainment_title') }}
                                    </label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" readonly value="{{ $item['title'] }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-left">
                                        {{ __('label.entertainment_name_attendants') }}
                                    </label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" readonly value="{{ $item['name_attendants'] }}">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
                    <hr>
                    {{-- Type of Entertainment --}}
                    <div class="form-group row">
                        <div class="col-md-2 text-right caption">
                            <label>{{ __('label.entertainment_entertainment_reason') }}</label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" readonly
                                value="{{ __('label.entertainment.reason.'.$application->entertainment->entertainment_reason) }}">
                        </div>
                    </div>
                    {{-- Estimated Amount --}}
                    <div class="form-group row">
                        <div class="col-md-2 text-right caption">
                            <label>{{ __('label.entertainment_est_amount') }}</label>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" readonly
                                    value="{{ number_format($application->entertainment->est_amount) }}">
                                </div>
                                <label class="col-md-9 col-form-label com_title text-lg-left text-right">
                                    {{ __('label.entertainment_per_person_excluding_vnd') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    {{-- Applicant / Date --}}
                    <div class="form-group row">
                        <div class="col-md-2 text-right caption">
                            <label>{{ __('label.entertainment_applicant_date') }}</label>
                        </div>
                        <div class="col-md-4">
                            {{ $application->applicant->name }}
                        </div>
                    </div>
                </div>

                <h3 class="mt-3">Settlement Information</h3>
                <div class="invoice card-body">
                    {{-- Entertainment Date --}}
                    <div class="form-group row">
                        <div class="col-md-2 text-right caption">
                            <label>
                                {{ __('label.entertainment_date') }}
                                <span class="text-danger required"> (*)</span>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <div id="datetime" data-target-input="nearest" class="input-group date">
                                <input type="text" name="entertainment_dt"
                                    class="form-control datetimepicker-input @error('entertainment_dt') is-invalid @enderror"
                                    data-target="#datetime" @if($previewFlg) readonly @endif />
                                <div class="input-group-addon input-group-append" data-target="#datetime" data-toggle="datetimepicker">
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
                    {{-- Entertainment Information --}}
                    <hr>
                    <div class="form-group row">
                        <div class="col-md-2 text-right caption">
                            <label>
                                {{ __('label.entertainment_entrainment_infomation') }}
                                <span class="text-danger required"> (*)</span>
                            </label>
                        </div>
                        <div class="col-md-10">
                            <div id="infos_block">
                                @if (!empty($entertainmentinfos))
                                @foreach ($entertainmentinfos as $key => $value)
                                <div class="card card-body card-company">
                                    @if(!$previewFlg)
                                    <div class="d-delete d-flex justify-content-end @if(count($entertainmentinfos) === 1 && $key === 0) d-none @endif">
                                        <button class="btnDelete btn bg-gradient-danger btn-sm pt-0 pb-0 pl-3 pr-3 mb-1">
                                            {{ __('label.button_delete') }}
                                        </button>
                                    </div>
                                    @endif
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label com_title text-left">
                                            {{ __('label.entertainment_cp_name') }}<span class="text-danger required"> (*)</span>
                                        </label>
                                        <div class="col-md-9">
                                            <div id="scrollable-dropdown-menu">
                                                <input type="text"
                                                    class="form-control cp_name @error('entertainmentinfos.'.$key.'.cp_name') is-invalid @enderror"
                                                    name="entertainmentinfos[{{ $key }}][cp_name]" autocomplete="off" value="{{ $entertainmentinfos[$key]['cp_name'] }}"
                                                    @if($previewFlg) readonly @endif>
                                            </div>
                                            @error('entertainmentinfos.'.$key.'.cp_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label com_title text-left">
                                            {{ __('label.entertainment_title') }}<span class="text-danger required"> (*)</span>
                                        </label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control title @error('entertainmentinfos.'.$key.'.title') is-invalid @enderror"
                                                name="entertainmentinfos[{{ $key }}][title]" autocomplete="off" value="{{ $entertainmentinfos[$key]['title'] }}"
                                                @if($previewFlg) readonly @endif>
                                            @error('entertainmentinfos.'.$key.'.title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label com_title text-left">
                                            {{ __('label.entertainment_name_attendants') }}<span class="text-danger required"> (*)</span>
                                        </label>
                                        <div class="col-md-9">
                                            <input type="text"
                                                class="form-control name_attendants @error('entertainmentinfos.'.$key.'.name_attendants') is-invalid @enderror"
                                                name="entertainmentinfos[{{ $key }}][name_attendants]" autocomplete="off"
                                                value="{{ $entertainmentinfos[$key]['name_attendants'] }}" @if($previewFlg) readonly @endif>
                                            @error('entertainmentinfos.'.$key.'.name_attendants')
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
                                        <label class="col-md-3 col-form-label com_title text-left">
                                            {{ __('label.entertainment_cp_name') }}<span class="text-danger required"> (*)</span>
                                        </label>
                                        <div class="col-md-9">
                                            <div id="scrollable-dropdown-menu">
                                                <input type="text" class="form-control cp_name" name="entertainmentinfos[0][cp_name]" autocomplete="off"
                                                    @if($previewFlg) readonly @endif>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label com_title text-left">
                                            {{ __('label.entertainment_title') }}<span class="text-danger required"> (*)</span>
                                        </label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control title" name="entertainmentinfos[0][title]" autocomplete="off" @if($previewFlg)
                                                readonly @endif>
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label com_title text-left">
                                            {{ __('label.entertainment_name_attendants') }}<span class="text-danger required"> (*)</span>
                                        </label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control name_attendants" name="entertainmentinfos[0][name_attendants]"
                                                autocomplete="off" @if($previewFlg) readonly @endif>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <!-- form chil -->
                                <div class="card card-body card-company copy d-none">
                                    <div class="d-delete d-flex justify-content-end">
                                        <button class="btnDelete btn bg-gradient-danger btn-sm pt-0 pb-0 pl-3 pr-3 mb-1">
                                            {{ __('label.button_delete') }}
                                        </button>
                                    </div>
                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label com_title text-left">
                                            {{ __('label.entertainment_cp_name') }}<span class="text-danger required"> (*)</span>
                                        </label>
                                        <div class="col-md-9">
                                            <div id="scrollable-dropdown-menu">
                                                <input type="text" class="form-control cp_name" autocomplete="off" @if($previewFlg) readonly @endif>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label com_title text-left">
                                            {{ __('label.entertainment_title') }}<span class="text-danger required"> (*)</span>
                                        </label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control title" autocomplete="off" @if($previewFlg) readonly @endif>
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label class="col-md-3 col-form-label com_title text-left">
                                            {{ __('label.entertainment_name_attendants') }}<span class="text-danger required"> (*)</span>
                                        </label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control name_attendants" autocomplete="off" @if($previewFlg) readonly @endif>
                                        </div>
                                    </div>
                                </div>
                                <!-- ./form chil -->
                            </div>
                            @if(!$previewFlg)
                            <button id="btnAdd"
                                class="btn bg-gradient-danger @if(!empty($entertainmentinfos) && count($entertainmentinfos) >= 4) d-none @endif">
                                + {{ __('label.button_addnew') }}
                            </button>
                            @endif
                        </div>
                    </div>
                    <hr>
                    {{-- Num of persons --}}
                    <div class="form-group row ">
                        <label class="col-md-2 col-form-label text-right">
                            {{ __('label.entertainment_entertainment_person') }}<span class="text-danger required"> (*)</span>
                        </label>
                        <div class="col-md-10">
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <input type="text" name="entertainment_person"
                                        class="form-control entertainment_person @error('entertainment_person') is-invalid @enderror"
                                        value="{{ $entertainment_person }}" @if($previewFlg) readonly @endif>
                                </div>
                                <label class="col-md-8 col-form-label com_title text-lg-left text-left">
                                    {{ __('label.entertainment_persons') }}
                                </label>
                            </div>
                            @error('entertainment_person')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    {{-- Estimated Amount --}}
                    <div class="form-group row">
                        <div class="col-md-2 text-right caption">
                            <label>
                                {{ __('label.entertainment_est_amount') }}
                                <span class="text-danger required"> (*)</span>
                            </label>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <input type="text" class="form-control est_amount @error('est_amount') is-invalid @enderror"
                                        value="{{ $est_amount }}" @if($previewFlg) readonly @endif>
                                    <input type="hidden" name="est_amount" value="{{ $est_amount }}">
                                </div>
                                <label class="col-md-8 col-form-label com_title text-lg-left text-left">
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
                    {{-- Cost to be charged to (Sec Code) --}}
                    <hr>
                    <div class="form-group row">
                        <div class="col-md-2 text-right">
                            <label>
                                {{ __('label.entertainment_cost_charged') }}
                                <span class="text-danger required"> (*)</span>
                            </label>
                        </div>
                        <div class="col-md-10">
                            <div id="chargedbys_block">
                                @if (!empty($chargedbys))
                                @foreach ($chargedbys as $key => $chargedby)
                                <div class="card card-body card-chargedbys">
                                    @if(!$previewFlg)
                                    <div
                                        class="d-delete d-flex justify-content-end @if(count($chargedbys) === 1 && $key === 0) d-none @endif">
                                        <button class="chargedbys-btnDelete btn bg-gradient-danger btn-sm pt-0 pb-0 pl-3 pr-3"
                                            title="{{ __('label.button_delete') }}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    @endif
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <span for="">
                                                {{ __('label.business_department') }}<span class="text-danger required"> (*)</span>
                                            </span>
                                            <select name="chargedbys[{{ $key }}][department]" style=""
                                                class="form-control chargedbys_department @error('chargedbys.'.$key.'.department') is-invalid @enderror" @if($previewFlg) readonly @endif>
                                                <option value="">
                                                    {{ __('label.select') }}
                                                </option>
                                                @foreach ($departments as $item)
                                                <option value="{{ $item->id }}" @if($chargedby['department']==$item->id) selected @endif>
                                                    {{ $item->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('chargedbys.'.$key.'.department')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-3">
                                            <span for="">
                                                {{ __('label.percent') }}<span class="text-danger required"> (*)</span>
                                            </span>
                                            <div class="input-group">
                                                <input type="text" name="chargedbys[{{ $key }}][percent]" style="width: 80px"
                                                    class="form-control chargedbys_percent @error('chargedbys.'.$key.'.percent') is-invalid @enderror @error('total_percent') is-invalid @enderror"
                                                    value="{{ $chargedbys[$key]['percent'] }}" autocomplete="off" @if($previewFlg) readonly
                                                    @endif>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                            @error('chargedbys.'.$key.'.percent')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                @else
                                <div class="card card-body card-chargedbys">
                                    <div class="d-delete d-flex justify-content-end d-none">
                                        <button class="chargedbys-btnDelete btn bg-gradient-danger btn-sm pt-0 pb-0 pl-3 pr-3"
                                            title="{{ __('label.button_delete') }}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <span for="">
                                                {{ __('label.business_department') }}<span class="text-danger required"> (*)</span>
                                            </span>
                                            <select name="chargedbys[0][department]" style="" class="form-control chargedbys_department">
                                                <option value="">
                                                    {{ __('label.select') }}
                                                </option>
                                                @foreach ($departments as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <span for="">
                                                {{ __('label.percent') }}<span class="text-danger required"> (*)</span>
                                            </span>
                                            <div class="input-group">
                                                <input type="text" name="chargedbys[0][percent]" style="width: 100px"
                                                    class="form-control chargedbys_percent" autocomplete="off">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="card card-body card-chargedbys copy d-none">
                                    <div class="d-delete d-flex justify-content-end">
                                        <button class="chargedbys-btnDelete btn bg-gradient-danger btn-sm pt-0 pb-0 pl-3 pr-3"
                                            title="{{ __('label.button_delete') }}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <span for="">
                                                {{ __('label.business_department') }}<span class="text-danger required"> (*)</span>
                                            </span>
                                            <select class="form-control chargedbys_department">
                                                <option value="">
                                                    {{ __('label.select') }}
                                                </option>
                                                @foreach ($departments as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <span for="">
                                                {{ __('label.percent') }}<span class="text-danger required"> (*)</span>
                                            </span>
                                            <div class="input-group">
                                                <input type="text" class="form-control chargedbys_percent" style="width: 80px"
                                                    autocomplete="off">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('total_percent')
                            <div class="mb-1">
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            </div>
                            @enderror
                            @if(!$previewFlg)
                            <button id="chargedbys-btnAdd" title="{{ __('label.button_addnew') }}"
                                class="btn bg-gradient-danger @if(!empty($chargedbys) && count($chargedbys) >= 3) d-none @endif">
                                <i class="fas fa-plus"></i>
                            </button>
                            @endif
                        </div>
                    </div>
                    {{-- Payment Information --}}
                    <hr>
                    <div class="form-group row">
                        <div class="col-md-2 text-right caption">
                            <label>
                                {{ __('label.entertainment_payment_info') }}
                                <span class="text-danger required"> (*)</span>
                            </label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" name="pay_info" class="form-control pay_info @error('pay_info') is-invalid @enderror"
                                value="{{ $pay_info }}" @if($previewFlg) readonly @endif>
                            @error('pay_info')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
        <div class="text-center">
            {{-- @if (!$previewFlg)
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
            @endif --}}
            @if (!$previewFlg)
            <button type="button" name="apply" value="apply" class="btn bg-gradient-success btn-form" data-toggle="modal"
                data-target="#popup-confirm" @if(Common::detectMobile()) disabled @endif>
                <i class="far fa-check-circle" style="margin-right: 5px;"></i>
                {{ __('label.button_apply') }}
            </button>
            @endif
            <a href="{{ Common::getHomeUrl() }}" class="btn btn bg-gradient-secondary btn-form btn-cancel">
                <i class="fa fa-ban" aria-hidden="true" style="margin-right: 5px;"></i>
                {{ __('label.button_cancel') }}
            </a>
        </div>
        <br>
        <br>
    </form>

    {{-- open PDF in new tab after saved --}}
    @if(session()->has('pdf_url'))
    <div class="modal fade" id="popup-pdf" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">
                        {{ __('msg.save_success') }}
                        {{ __('msg.sure_open_pdf') }}
                    </h5>
                    <button type="button" id="popup_btn_close" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" id="popup_btn_cancel" class="btn bg-gradient-secondary"
                        data-dismiss="modal">{{ __('label.button_cancel') }}</button>
                    <button type="button" id="open_pdf" pdf-url="{{ session()->get('pdf_url') }}"
                        class="btn bg-gradient-success">{{ __('label.button_open_pdf') }}</button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        $("#popup-pdf").modal('show');
        $("#open_pdf").on('click', function(){
            $("#popup-pdf").modal('hide');
            window.open($(this).attr('pdf-url'));
        });
    </script>
    @endif

</section>
@endsection