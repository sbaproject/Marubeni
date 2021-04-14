@php
    $destinations           = Session::exists('inputs') ? Session::get('inputs')['destinations']            : (isset($flgMod) ? ($application->business2->destinations ?? null) : ($application->business->destinations ?? null));
    $itineraries            = Session::exists('inputs') ? Session::get('inputs')['itineraries']             : (isset($flgMod) ? ($application->business2->transportations ?? null) : ($application->business->transportations ?? null));
    $number_of_days         = Session::exists('inputs') ? Session::get('inputs')['number_of_days']          : ($application->business2->number_of_days ?? null);
    $total_daily_allowance  = Session::exists('inputs') ? Session::get('inputs')['total_daily_allowance']   : ($application->business2->total_daily_allowance ?? null);
    $total_daily_unit       = Session::exists('inputs') ? Session::get('inputs')['total_daily_unit']        : ($application->business2->total_daily_unit ?? null);
    $total_daily_rate       = Session::exists('inputs') ? Session::get('inputs')['total_daily_rate']        : ($application->business2->total_daily_rate ?? null);
    
    $daily_allowance        = Session::exists('inputs') ? Session::get('inputs')['daily_allowance']         : ($application->business2->daily_allowance ?? null);
    $daily_unit             = Session::exists('inputs') ? Session::get('inputs')['daily_unit']              : ($application->business2->daily_unit ?? null);
    $daily_rate             = Session::exists('inputs') ? Session::get('inputs')['daily_rate']              : ($application->business2->daily_rate ?? null);

    $other_fees             = Session::exists('inputs') ? Session::get('inputs')['other_fees']              : ($application->business2->other_fees ?? null);
    $other_fees_unit        = Session::exists('inputs') ? Session::get('inputs')['other_fees_unit']         : ($application->business2->other_fees_unit ?? null);
    $other_fees_rate        = Session::exists('inputs') ? Session::get('inputs')['other_fees_rate']         : ($application->business2->other_fees_rate ?? null);
    $other_fees_note        = Session::exists('inputs') ? Session::get('inputs')['other_fees_note']         : ($application->business2->other_fees_note ?? null);

    $charged_to                     = Session::exists('inputs') ? Session::get('inputs')['charged_to']                      : ($application->business2->charged_to ?? null);
    $under_instruction_date         = Session::exists('inputs') ? Session::get('inputs')['under_instruction_date']          : ($application->business2->under_instruction_date ?? null);
    $under_instruction_approval_no  = Session::exists('inputs') ? Session::get('inputs')['under_instruction_approval_no']   : ($application->business2->under_instruction_approval_no ?? null);
@endphp
@extends('layouts.master')

@section('title')
{{ 'SETTLEMENT FOR BUSINESS TRIP' }}
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
    .card-itinerary-itineraries{
        padding: 10px;
        padding-bottom: 0px;
    }
</style>
@endsection

@section('js')
{{-- datetimepicker --}}
<script src="js/moment/moment.min.js"></script>
<script src="js/moment/locale/{{ config('app.locale') }}.js"></script>
<script src="js/bootstrap-datetimepicker.js"></script>
{{-- for this view --}}
<script src="js/user/application/business2/input.js"></script>

{{--  --}}
<script>
    const _ITINERARIES = @json($itineraries);
</script>
@endsection

@section('content-header')
{{ 'SETTLEMENT FOR BUSINESS TRIP' }}
@endsection

@section('content-breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('user.form.index') }}">{{ __('label.application_list') }}</a></li>
<li class="breadcrumb-item active">{{ 'SETTLEMENT FOR BUSINESS TRIP' }}</li>
@endsection

@section('content')
@php

    
    // $destinations           = Session::exists('inputs') ? Session::get('inputs')['destinations']            : (isset($flgMod) ? ($application->business2->destinations ?? null) : (isset($application) ? $application->business->destinations : null));
    // $itineraries            = Session::exists('inputs') ? Session::get('inputs')['destinations']            : (isset($flgMod) ? ($application->business2->transportations ?? null) : (isset($application) ? $application->business->transportations : null));
    // $number_of_days         = Session::exists('inputs') ? Session::get('inputs')['number_of_days']          : (isset($application) ? $application->business2->number_of_days : null);
    // $total_daily_allowance  = Session::exists('inputs') ? Session::get('inputs')['total_daily_allowance']   : (isset($application) ? $application->business2->total_daily_allowance : null);
    // $total_daily_unit       = Session::exists('inputs') ? Session::get('inputs')['total_daily_unit']        : (isset($application) ? $application->business2->total_daily_unit : null);
    // $total_daily_rate       = Session::exists('inputs') ? Session::get('inputs')['total_daily_rate']        : (isset($application) ? $application->business2->total_daily_rate : null);
    
    // $daily_allowance        = Session::exists('inputs') ? Session::get('inputs')['daily_allowance']         : (isset($application) ? $application->business2->daily_allowance : null);
    // $daily_unit             = Session::exists('inputs') ? Session::get('inputs')['daily_unit']              : (isset($application) ? $application->business2->daily_unit : null);
    // $daily_rate             = Session::exists('inputs') ? Session::get('inputs')['daily_rate']              : (isset($application) ? $application->business2->daily_rate : null);

    // $other_fees             = Session::exists('inputs') ? Session::get('inputs')['other_fees']              : (isset($application) ? $application->business2->other_fees : null);
    // $other_fees_unit        = Session::exists('inputs') ? Session::get('inputs')['other_fees_unit']         : (isset($application) ? $application->business2->other_fees_unit : null);
    // $other_fees_rate        = Session::exists('inputs') ? Session::get('inputs')['other_fees_rate']         : (isset($application) ? $application->business2->other_fees_rate : null);
    // $other_fees_note        = Session::exists('inputs') ? Session::get('inputs')['other_fees_note']         : (isset($application) ? $application->business2->other_fees_note : null);

    // $charged_to                     = Session::exists('inputs') ? Session::get('inputs')['charged_to'] : (isset($application) ? $application->business2->charged_to : null);
    // $under_instruction_date         = Session::exists('inputs') ? Session::get('inputs')['under_instruction_date'] : (isset($application) ? $application->business2->under_instruction_date : null);
    // $under_instruction_approval_no  = Session::exists('inputs') ? Session::get('inputs')['under_instruction_approval_no'] : (isset($application) ? $application->business2->under_instruction_approval_no : null);


    // get action url
    // if(isset($application)){
    //     if($previewFlg){
    //         $actionUrl = route('user.business.preview.pdf', $application->id);
    //     } else {
    //         $actionUrl = route('user.business.update', $application->id);
    //     }
    // } else {
    //     $actionUrl = route('user.business.store');
    // }

@endphp
<section class="content leave-application">
    {{-- <x-alert /> --}}
    <form method="POST"
        action="{{ route('user.business2.create', $application->id) }}"
        enctype="multipart/form-data">
        @csrf
        <div class="invoice mb-3">
            <div class="card-body">
                <div class="form-group row float-right">
                    <button type="submit" id="btnPdf" value="pdf" class="btn bg-gradient-danger" href="#">
                        <i class="fas fa-external-link-alt" style="margin-right: 5px; color: #fff;"></i>
                        {{ __('label.button_export') }}
                    </button>
                </div>
                <div class="clearfix"></div>

                {{-- Application No --}}
                <div class="form-group row">
                    <div class="col-md-2 text-left caption">
                        <label>{{ __('label.business_application_no') }}</label>
                    </div>
                    <div class="col-md-10">
                        <span>{{ $application->application_no }}</span>
                    </div>
                </div>
                <hr>
                {{-- Destinations --}}
                <div class="form-group row">
                    <div class="col-md-2 text-left caption">
                        <label>{{ __('label.business_trip_destination') }}<span class="text-danger required"> (*)</span></label>
                    </div>
                    <div class="col-md-10">
                        <input type="text" id="destinations" name="destinations" class="form-control @error('destinations') is-invalid @enderror"
                            autocomplete="off" value="{{ $destinations }}">
                        @error('destinations')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <hr>
                {{-- Number of days --}}
                <div class="form-group row">
                    <div class="col-md-2 text-left caption">
                        <label>{{ __('label.business_number_of_days') }}<span class="text-danger required"> (*)</span></label>
                    </div>
                    <div class="col-md-2">
                        <input type="text" id="accommnumber_of_daysodation" name="number_of_days"
                            class="form-control @error('number_of_days') is-invalid @enderror" autocomplete="off"
                            value="{{ $number_of_days }}">
                        @error('number_of_days')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <hr>
                {{-- Itineraries --}}
                <div class="form-group row">
                    <div class="col-md-2 text-left">
                        <label>{{ __('label.business_itinerary') }}<span class="text-danger required"> (*)</span></label>
                    </div>
                    <div class="col-md-10">
                        <div id="itineraries_block">
                            @if (!empty($itineraries))
                                @foreach ($itineraries as $key => $value)
                                <div class="card card-body card-itinerary-itineraries">
                                    @if(!$previewFlg)
                                    <div class="d-delete d-flex justify-content-end @if(count($itineraries) === 1 && $key === 0) d-none @endif">
                                        <button class="btnDelete btn bg-gradient-danger btn-sm pt-0 pb-0 pl-3 pr-3 mb-1">
                                            {{ __('label.button_delete') }}
                                        </button>
                                    </div>
                                    @endif
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <span for="">
                                                {{ __('label.date') }}<span class="text-danger required"> (*)</span>
                                            </span>
                                            <div id="trans_date_picker_{{ $key }}" data-target-input="nearest" class="input-group date trans_date_picker">
                                                <input type="text"
                                                    class="form-control datetimepicker-input txt_trans_date_picker @error('itineraries.'.$key.'.trans_date') is-invalid @enderror"
                                                    data-target="#trans_date_picker_{{ $key }}" @if($previewFlg) readonly @endif/>
                                                <div class="input-group-addon input-group-append group_trans_date_picker" data-target="#trans_date_picker_{{ $key }}"
                                                    data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar-alt"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" id="hid_trans_date_{{ $key }}" name="itineraries[{{ $key }}][trans_date]" class="hid_trans_date"
                                                value="{{ $itineraries[$key]['trans_date'] }}">
                                            @error('itineraries.'.$key.'.trans_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-4">
                                            <span for="">
                                                {{ __('label.business_departure') }}<span class="text-danger required"> (*)</span>
                                            </span>
                                            <input type="text" class="form-control departure @error('itineraries.'.$key.'.departure') is-invalid @enderror"
                                                name="itineraries[{{ $key }}][departure]" value="{{ $itineraries[$key]['departure'] }}" autocomplete="off"
                                                @if($previewFlg) readonly @endif>
                                            @error('itineraries.'.$key.'.departure')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-4">
                                            <span for="">
                                                {{ __('label.business_arrival') }}<span class="text-danger required"> (*)</span>
                                            </span>
                                            <input type="text" class="form-control arrive @error('itineraries.'.$key.'.arrive') is-invalid @enderror"
                                                name="itineraries[{{ $key }}][arrive]" value="{{ $itineraries[$key]['arrive'] }}" autocomplete="off"
                                                @if($previewFlg) readonly @endif>
                                            @error('itineraries.'.$key.'.arrive')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                            <div class="card card-body card-itinerary-itineraries">
                                <div class="d-delete d-flex justify-content-end d-none">
                                    <button class="btnDelete btn bg-gradient-danger btn-sm pt-0 pb-0 pl-3 pr-3 mb-1">
                                        {{ __('label.button_delete') }}
                                    </button>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <span for="">
                                            {{ __('label.date') }}<span class="text-danger required"> (*)</span>
                                        </span>
                                        <div id="trans_date_picker_0" data-target-input="nearest" class="input-group date trans_date_picker">
                                            <input type="text" class="form-control datetimepicker-input txt_trans_date_picker" data-target="#trans_date_picker_0" @if($previewFlg)
                                                readonly @endif />
                                            <div class="input-group-addon input-group-append group_trans_date_picker" data-target="#trans_date_picker_0" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                            </div>
                                            <input type="hidden" id="hid_trans_date_0" name="itineraries[0][trans_date]" class="hid_trans_date">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <span>{{ __('label.business_departure') }}<span class="text-danger required"> (*)</span></span>
                                        <input type="text" class="form-control departure" name="itineraries[0][departure]" autocomplete="off" @if($previewFlg) readonly @endif>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <span>{{ __('label.business_arrival') }}<span class="text-danger required"> (*)</span></span>
                                        <input type="text" class="form-control arrive" name="itineraries[0][arrive]" autocomplete="off" @if($previewFlg) readonly @endif>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="card card-body card-itinerary-itineraries copy d-none">
                                <div class="d-delete d-flex justify-content-end">
                                    <button class="btnDelete btn bg-gradient-danger btn-sm pt-0 pb-0 pl-3 pr-3 mb-1">
                                        {{ __('label.button_delete') }}
                                    </button>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <span for="">
                                            {{ __('label.date') }}<span class="text-danger required"> (*)</span>
                                        </span>
                                        <div data-target-input="nearest" class="input-group date trans_date_picker">
                                            <input type="text" class="form-control datetimepicker-input txt_trans_date_picker" @if($previewFlg) readonly @endif />
                                            <div class="input-group-addon input-group-append group_trans_date_picker" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                            </div>
                                            <input type="hidden" class="hid_trans_date">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <span class="col-md-3">{{ __('label.business_departure') }}<span class="text-danger required"> (*)</span></span>
                                        <input type="text" class="form-control departure" autocomplete="off" @if($previewFlg) readonly @endif>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <span class="col-md-3">{{ __('label.business_arrival') }}<span class="text-danger required"> (*)</span></span>
                                        <input type="text" class="form-control arrive" autocomplete="off" @if($previewFlg) readonly @endif>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(!$previewFlg)
                        <button id="btnAdd" class="btn bg-gradient-danger @if(!empty($itineraries) && count($itineraries) >= 4) d-none @endif">
                            + {{ __('label.button_addnew') }}
                        </button>
                        @endif
                    </div>
                </div>
                <hr>
                {{-- Total daily allowances --}}
                <div class="form-group row">
                    <div class="col-md-2 text-left caption">
                        <label>{{ __('label.business_total_daily_allowance') }}<span class="text-danger required"> (*)</span></label>
                    </div>
                    <div class="col-md-10">
                        <div class="form-row">
                            {{-- Amount --}}
                            <div class="form-group col-md-4 mb-1">
                                <span class="mb-0 mr-1">{{__('label.amount')}}<span class="text-danger required"> (*)</span></span>
                                <input type="text" id="total_daily_allowance" name="total_daily_allowance"
                                    class="form-control @error('total_daily_allowance') is-invalid @enderror" autocomplete="off" value="{{ $total_daily_allowance }}">
                                @error('total_daily_allowance')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            {{-- Unit --}}
                            <div class="form-group col-md-4 mb-1">
                                <span class="mb-0 mr-1">{{__('label.unit')}}<span class="text-danger required"> (*)</span></span>
                                <select name="total_daily_unit" style="width: 100%;"
                                    class="form-control @error('total_daily_unit') is-invalid @enderror">
                                    <option value="">
                                        {{ __('label.select') }}
                                    </option>
                                    @foreach (config('const.units') as $value)
                                    <option value="{{ $value }}" @if($total_daily_unit == $value) selected @endif>
                                        {{ $value }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('total_daily_unit')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            {{-- Rate --}}
                            <div class="form-group col-md-4 mb-1">
                                <span class="mb-0 mr-1">{{__('label.rate')}}<span class="text-danger required"> (*)</span></span>
                                <input type="text" id="total_daily_rate" name="total_daily_rate"
                                    class="form-control @error('total_daily_rate') is-invalid @enderror" autocomplete="off" value="{{ $total_daily_rate }}">
                                @error('total_daily_rate')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                {{-- Daily allowances --}}
                <div class="form-group row">
                    <div class="col-md-2 text-left caption">
                        <label>{{ __('label.business_daily_allowance') }}<span class="text-danger required"> (*)</span></label>
                    </div>
                    <div class="col-md-10">
                        <div class="form-row">
                            {{-- Amount --}}
                            <div class="form-group col-md-4 mb-1">
                                <span class="mb-0 mr-1">{{__('label.amount')}}<span class="text-danger required"> (*)</span></span>
                                <input type="text" id="daily_allowance" name="daily_allowance"
                                    class="form-control @error('daily_allowance') is-invalid @enderror" autocomplete="off"
                                    value="{{ $daily_allowance }}">
                                @error('daily_allowance')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            {{-- Unit --}}
                            <div class="form-group col-md-4 mb-1">
                                <span class="mb-0 mr-1">{{__('label.unit')}}<span class="text-danger required"> (*)</span></span>
                                <select name="daily_unit" style="width: 100%;"
                                    class="form-control @error('daily_unit') is-invalid @enderror">
                                    <option value="">
                                        {{ __('label.select') }}
                                    </option>
                                    @foreach (config('const.units') as $value)
                                    <option value="{{ $value }}" @if($daily_unit == $value) selected @endif>
                                        {{ $value }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('daily_unit')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            {{-- Rate --}}
                            <div class="form-group col-md-4 mb-1">
                                <span class="mb-0 mr-1">{{__('label.rate')}}<span class="text-danger required"> (*)</span></span>
                                <input type="text" id="daily_rate" name="daily_rate"
                                    class="form-control @error('daily_rate') is-invalid @enderror" autocomplete="off"
                                    value="{{ $daily_rate }}">
                                @error('daily_rate')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                {{-- Other Fees --}}
                <div class="form-group row">
                    <div class="col-md-2 text-left caption">
                        <label>{{__('label.business_other_fees')}}</label>
                    </div>
                    <div class="col-md-10">
                        <div class="form-row">
                            {{-- Amount --}}
                            <div class="form-group col-md-4 mb-1">
                                <span class="mb-0 mr-1">{{__('label.amount')}}</span>
                                <input type="text" id="other_fees" name="other_fees" class="form-control @error('other_fees') is-invalid @enderror"
                                    autocomplete="off" value="{{ $other_fees }}">
                                @error('other_fees')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            {{-- Unit --}}
                            <div class="form-group col-md-4 mb-1">
                                <span class="mb-0 mr-1">{{__('label.unit')}}</span>
                                <select name="other_fees_unit" style="width: 100%;"
                                    class="form-control @error('other_fees_unit') is-invalid @enderror">
                                    <option value="">
                                        {{ __('label.select') }}
                                    </option>
                                    @foreach (config('const.units') as $value)
                                    <option value="{{ $value }}" @if($other_fees_unit == $value) selected @endif>
                                        {{ $value }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('other_fees_unit')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            {{-- Rate --}}
                            <div class="form-group col-md-4 mb-1">
                                <span class="mb-0 mr-1">{{__('label.rate')}}</span>
                                <input type="text" id="other_fees_rate" name="other_fees_rate"
                                    class="form-control @error('other_fees_rate') is-invalid @enderror" autocomplete="off"
                                    value="{{ $other_fees_rate }}">
                                @error('other_fees_rate')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                {{-- Note --}}
                                <span class="mb-0 mr-1">{{ __('label.remarks') }}</span>
                                <textarea id="other_fees_note" name="other_fees_note" autocomplete="off" rows="3"
                                    class="form-control @error('other_fees_note') is-invalid @enderror">{{ $other_fees_note }}</textarea>
                                @error('other_fees_note')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                </div>
                {{-- Cost to be charged to (Sec Code) --}}
                <hr>
                <div class="form-group row">
                    <div class="col-md-2 text-left caption">
                        <label>{{ __('label.business_charged_to') }}<span class="text-danger required"> (*)</span></label>
                    </div>
                    <div class="col-md-10">
                        <select name="charged_to" style="width: auto;" class="form-control @error('charged_to') is-invalid @enderror">
                            <option value="">
                                {{ __('label.select') }}
                            </option>
                            @foreach ($departments as $item)
                            <option value="{{ $item->id }}" @if($charged_to == $item->id) selected @endif>
                               {{ $item->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('charged_to')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                {{-- Total Expenses --}}
                <hr>
                <div class="form-group row">
                    <div class="col-md-2 text-left caption">
                        <label>{{ __('label.business_total_expenses') }}</label>
                    </div>
                    <div class="col-md-10">
                        <span id="total_expenses">980,000,000</span> VND
                    </div>
                </div>
                <hr>
                {{-- Under Instruction by --}}
                <div class="form-group row">
                    <div class="col-md-2 text-left caption">
                        <label>{{ __('label.business_instruction_by') }}</label>
                    </div>
                    <div class="col-md-10">
                        <div class="form-row">
                            <div class="form-group col-md-6 mb-1">
                                <span>{{ __('label.date') }}</span>
                                <div id="instruction_date_picker" data-target-input="nearest" class="input-group date">
                                    <input type="text" class="form-control datetimepicker-input @error('under_instruction_date') is-invalid @enderror"
                                        data-target="#instruction_date_picker" />
                                    <div class="input-group-addon input-group-append" data-target="#instruction_date_picker"
                                        data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="under_instruction_date" name="under_instruction_date" value="{{ $under_instruction_date }}">
                                @error('under_instruction_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 mb-1">
                                <span>{{ __('label.business_approval_no') }}</span>
                                <input type="text" id="under_instruction_approval_no" name="under_instruction_approval_no"
                                    class="form-control @error('under_instruction_approval_no') is-invalid @enderror" autocomplete="off"
                                    value="{{ $under_instruction_approval_no }}">
                                @error('under_instruction_approval_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
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
            <button type="button" name="apply" value="apply" class="btn bg-gradient-success btn-form" data-toggle="modal"
                data-target="#popup-confirm" @if(Common::detectMobile()) disabled @endif>
                <i class="far fa-check-circle" style="margin-right: 5px;"></i>
                {{ __('label.button_apply') }}
            </button>
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