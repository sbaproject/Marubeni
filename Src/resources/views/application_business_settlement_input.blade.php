@php
    $destinations                   = Session::exists('inputs') ? Session::get('inputs')['destinations']                    : ($modFlg ? ($application->business2->destinations ?? null) : ($application->business->destinations ?? null));
    $itineraries                    = Session::exists('inputs') ? Session::get('inputs')['itineraries']                     : ($modFlg ? ($application->business2->itineraries ?? null) : ($application->business->itineraries ?? null));
    $number_of_days                 = Session::exists('inputs') ? Session::get('inputs')['number_of_days']                  : ($application->business2->number_of_days ?? null);
    
    $daily1_amount                  = Session::exists('inputs') ? Session::get('inputs')['daily1_amount']                   : ($application->business2->daily1_amount ?? null);
    $daily1_days                    = Session::exists('inputs') ? Session::get('inputs')['daily1_days']                     : ($application->business2->daily1_days ?? null);

    $daily2_amount                  = Session::exists('inputs') ? Session::get('inputs')['daily2_amount']                   : ($application->business2->daily2_amount ?? null);
    $daily2_rate                    = Session::exists('inputs') ? Session::get('inputs')['daily2_rate']                     : ($application->business2->daily2_rate ?? null);
    $daily2_days                    = Session::exists('inputs') ? Session::get('inputs')['daily2_days']                     : ($application->business2->daily2_days ?? null);

    $transportations                = Session::exists('inputs') ? (Session::get('inputs')['transportations'] ?? [])         : ($application->business2->transportations ?? null);
    $communications                 = Session::exists('inputs') ? (Session::get('inputs')['communications'] ?? [])          : ($application->business2->communications ?? null);
    $accomodations                  = Session::exists('inputs') ? (Session::get('inputs')['accomodations'] ?? [])           : ($application->business2->accomodations ?? null);
    $otherfees                      = Session::exists('inputs') ? (Session::get('inputs')['otherfees'] ?? [])               : ($application->business2->otherfees ?? null);
    $chargedbys                     = Session::exists('inputs') ? (Session::get('inputs')['chargedbys'] ?? [])              : ($application->business2->chargedbys ?? null);

    // get action url
    if(isset($modFlg)){
        if($previewFlg){
            $actionUrl = route('user.business2.preview.pdf', $application->id);
        } else {
            $actionUrl = route('user.business2.update', $application->id);
        }
        // $actionUrl = route('user.business2.update', $application->id);
    } else {
        $actionUrl = route('user.business2.store', $application->id);
    }
@endphp
@extends('layouts.master')

@section('title')
{{ __('label.business_settlement') }}
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
    .card-itinerary-itineraries, .card-transportations, .card-communications, .card-accomodations,.card-otherfees,.card-chargedbys{
        padding: 10px;
        padding-bottom: 0px;
    }
    .card-transportations .badge,.card-communications .badge,.card-accomodations .badge,.card-otherfees .badge {
        line-height: inherit;
    }
</style>
@endsection

@section('js')
{{-- datetimepicker --}}
<script src="js/moment/moment.min.js"></script>
<script src="js/moment/locale/{{ config('app.locale') }}.js"></script>
<script src="js/bootstrap-datetimepicker.js"></script>
{{-- cleave js --}}
<script src="js/cleave/cleave.min.js"></script>
{{-- numeral --}}
<script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
{{-- for this view --}}
<script src="js/user/application/business2/input.js"></script>

{{--  --}}
<script>
    const _ITINERARIES = @json($itineraries);
</script>
@endsection

@section('content-header')
{{ Str::upper(__('label.business_settlement')) }}
@endsection

@section('content-breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('user.form.index') }}">{{ __('label.application_list') }}</a></li>
<li class="breadcrumb-item active">{{ __('label.business_settlement') }}</li>
@endsection

@section('content')
<section class="content leave-application">

    {{-- auto open pdf in new tab --}}
    <script type="text/javascript">
        // var win = null;
        // @if(session()->has('pdf_url'))
        //     $(function(){
        //         // var link = $("<a>");
        //         //     link.attr("href", "{{ session()->get('pdf_url') }}{{ session()->has('inputs') ? '?m=true' : '' }}");
        //         //     link.attr("target", "_blank");
        //         // link[0].click();
        //         // $("#link_pdf").attr('href',"{{ session()->get('pdf_url') }}{{ session()->has('inputs') ? '?m=true' : '' }}");
        //         // $("#link_pdf")[0].click();
        //     });
        // @endif
    </script>
    {{-- <button id="link_pdf">asd</button> --}}
    <form method="POST"
        action="{{ $actionUrl }}"
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
                {{-- Under Instruction by --}}
                <div class="form-group row">
                    <div class="col-md-2 text-left caption">
                        <label>{{ __('label.business_instruction_by') }}</label>
                    </div>
                    <div class="col-md-10">
                        <div>
                            {{ __('label.date') }} :
                            @isset($application->lastapprovalstep1)
                            {{ \Carbon\Carbon::parse($application->lastapprovalstep1->created_at)->format('d/m/Y') }}
                            @endisset
                        </div>
                        <div>
                            {{ __('label.business_approval_no') }} : {{ $application->application_no }}
                        </div>
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
                        <input type="text" id="number_of_days" name="number_of_days"
                            class="form-control number_of_days @error('number_of_days') is-invalid @enderror" autocomplete="off"
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
                            @if (!empty($itineraries) && count($itineraries) > 0)
                                @foreach ($itineraries as $key => $value)
                                <div class="card card-body card-itinerary-itineraries">
                                    @if(!$previewFlg)
                                    <div class="d-delete d-flex justify-content-end @if(count($itineraries) === 1 && $key === 0) d-none @endif">
                                        <button class="itinerary-btnDelete btn bg-gradient-danger btn-sm pt-0 pb-0 pl-3 pr-3 mb-1"
                                            title="{{ __('label.button_delete') }}">
                                            <i class="fas fa-times"></i>
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
                                    <button class="itinerary-btnDelete btn bg-gradient-danger btn-sm pt-0 pb-0 pl-3 pr-3 mb-1"
                                        title="{{ __('label.button_delete') }}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <span for="">
                                            {{ __('label.date') }}<span class="text-danger required"> (*)</span>
                                        </span>
                                        <div id="trans_date_picker_0" data-target-input="nearest" class="input-group date trans_date_picker">
                                            <input type="text" class="form-control datetimepicker-input txt_trans_date_picker" data-target="#trans_date_picker_0" @if($previewFlg) readonly @endif />
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
                                    <button class="itinerary-btnDelete btn bg-gradient-danger btn-sm pt-0 pb-0 pl-3 pr-3 mb-1"
                                       title="{{ __('label.button_delete') }}">
                                        <i class="fas fa-times"></i>
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
                        <button id="itinerary-btnAdd" title="{{ __('label.button_addnew') }}"
                            class="btn bg-gradient-danger @if(!empty($itineraries) && count($itineraries) >= 4) d-none @endif">
                            <i class="fas fa-plus"></i>
                        </button>
                        @endif
                    </div>
                </div>
                <hr>
                {{-- TripFees - Transportations --}}
                <div class="form-group row">
                    <div class="col-md-2 text-left">
                        <span class="sp_trans_no badge badge-success">{{ config('const.trip_fee_type.transportation') }}</span>
                        <label>{{ __('label.business_trans') }}</label>
                    </div>
                    <div class="col-md-10">
                        <div id="transportations_block">
                            @if (!empty($transportations))
                            @foreach ($transportations as $trans_key => $transportation)
                            <div class="card card-body card-transportations">
                                @if(!$previewFlg)
                                <div class="d-flex justify-content-between">
                                    <span class="sp_trans_no badge badge-success">{{ config('const.trip_fee_type.transportation').($trans_key + 1) }}</span>
                                    <button class="d-delete transportations-btnDelete btn bg-gradient-success btn-sm pt-0 pb-0 pl-3 pr-3"
                                         title="{{ __('label.button_delete') }}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                @endif
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <span for="">
                                            {{ __('label.type') }}<span class="text-danger required"> (*)</span>
                                        </span>
                                        <select name="transportations[{{ $trans_key }}][method]" style="width: 100%;"
                                            class="form-control transportations_method @error('transportations.'.$trans_key.'.method') is-invalid @enderror">
                                            <option value="">
                                                {{ __('label.select') }}
                                            </option>
                                            @foreach (config('const.transportations') as $key => $value)
                                            <option value="{{ $key }}" @if($transportation['method'] == $key) selected @endif>
                                                {{ __('label.business_transportations.'.$key) }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('transportations.'.$trans_key.'.method')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3">
                                        <span for="">
                                            {{ __('label.amount') }}<span class="text-danger required"> (*)</span>
                                        </span>
                                        <input type="text" name="transportations[{{ $trans_key }}][amount]"
                                            class="form-control transportations_amount sync_total amount @error('transportations.'.$trans_key.'.amount') is-invalid @enderror"
                                            value="{{ $transportation['amount'] }}"
                                            autocomplete="off" @if($previewFlg) readonly @endif
                                            data-target="transportations[{{ $trans_key }}][exchange_rate]">
                                        @error('transportations.'.$trans_key.'.amount')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3">
                                        <span for="">
                                            {{ __('label.unit') }}<span class="text-danger required"> (*)</span>
                                        </span>
                                        <select name="transportations[{{ $trans_key }}][unit]" style="width: 100%;"
                                            class="form-control transportations_unit select_unit @error('transportations.'.$trans_key.'.unit') is-invalid @enderror"
                                            data-target="transportations[{{ $trans_key }}][exchange_rate]">
                                            @foreach (config('const.units') as $key => $value)
                                            <option value="{{ $value }}" @if($transportation['unit'] == $value) selected @endif>
                                                {{ $value }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('transportations.'.$trans_key.'.unit')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3">
                                        <span for="">
                                            {{ __('label.rate') }}<span class="text-danger required d-none"> (*)</span>
                                        </span>
                                        <input type="text"
                                            class="form-control transportations_rate sync_total rate @error('transportations.'.$trans_key.'.exchange_rate') is-invalid @enderror"
                                            name="transportations[{{ $trans_key }}][exchange_rate]" value="{{ $transportation['exchange_rate'] }}"
                                            autocomplete="off" @if($previewFlg) readonly @endif
                                            data-target="transportations[{{ $trans_key }}][amount]">
                                        @error('transportations.'.$trans_key.'.exchange_rate')
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
                                        <textarea name="transportations[{{ $trans_key }}][note]" autocomplete="off" rows="1"
                                            class="form-control transportations_note @error('transportations.'.$trans_key.'.note') is-invalid @enderror">{{ $transportation['note'] }}</textarea>
                                        @error('transportations.'.$trans_key.'.note')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
                            <div class="card card-body card-transportations copy d-none">
                                <div class="d-flex justify-content-between ">
                                    <span class="sp_trans_no badge badge-success"></span>
                                    <button class="d-delete transportations-btnDelete btn bg-gradient-success btn-sm pt-0 pb-0 pl-3 pr-3"
                                        title="{{ __('label.button_delete') }}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <span for="">{{ __('label.type') }}<span class="text-danger required"> (*)</span></span>
                                        <select style="width: 100%;" class="form-control transportations_method">
                                            <option value="">
                                                {{ __('label.select') }}
                                            </option>
                                            @foreach (config('const.transportations') as $key => $value)
                                            <option value="{{ $key }}">
                                                {{ __('label.business_transportations.'.$key) }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <span for="">{{ __('label.amount') }}<span class="text-danger required"> (*)</span></span>
                                        <input type="text" class="form-control transportations_amount" autocomplete="off" @if($previewFlg) readonly @endif>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <span for="">{{ __('label.unit') }}<span class="text-danger required"> (*)</span></span>
                                        <select style="width: 100%;" class="form-control transportations_unit select_unit">
                                            @foreach (config('const.units') as $key => $value)
                                            <option value="{{ $value }}">
                                                {{ $value }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <span for="">{{ __('label.rate') }}<span class="text-danger required d-none"> (*)</span></span>
                                        <input type="text" class="form-control transportations_rate" autocomplete="off" @if($previewFlg) readonly @endif>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        {{-- Note --}}
                                        <span class="mb-0 mr-1">{{ __('label.remarks') }}</span>
                                        <textarea class="form-control transportations_note" autocomplete="off" rows="1"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(!$previewFlg)
                        <button id="transportations-btnAdd" title="{{ __('label.button_addnew') }}"
                            class="btn bg-gradient-success @if(!empty($tranportations) && count($tranportations) >= 10) d-none @endif">
                            <i class="fas fa-plus"></i>
                        </button>
                        @endif
                    </div>
                </div>
                <hr>
                {{-- TripFees - Accomodation --}}
                <div class="form-group row">
                    <div class="col-md-2 text-left">
                        <label>
                            <span class="sp_trans_no badge badge-primary">{{ config('const.trip_fee_type.accomodation') }}</span>
                            {{ __('label.business_accommodation_fee') }}
                        </label>
                    </div>
                    <div class="col-md-10">
                        <div id="accomodations_block">
                            @if (!empty($accomodations))
                            @foreach ($accomodations as $ac_key => $accomodation)
                            <div class="card card-body card-accomodations">
                                @if(!$previewFlg)
                                <div class="d-flex justify-content-between">
                                    <span class="sp_acom_no badge badge-primary">{{ config('const.trip_fee_type.accomodation').($ac_key + 1) }}</span>
                                    <button
                                        class="d-delete accomodations-btnDelete btn bg-gradient-primary btn-sm pt-0 pb-0 pl-3 pr-3"
                                        title="{{ __('label.button_delete') }}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                @endif
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <span for="">
                                            {{ __('label.amount') }}<span class="text-danger required"> (*)</span>
                                        </span>
                                        <input type="text" name="accomodations[{{ $ac_key }}][amount]"
                                            class="form-control accomodations_amount sync_total amount @error('accomodations.'.$ac_key.'.amount') is-invalid @enderror"
                                            value="{{ $accomodation['amount'] }}" autocomplete="off" @if($previewFlg) readonly @endif
                                            data-target="accomodations[{{ $ac_key }}][exchange_rate]">
                                        @error('accomodations.'.$ac_key.'.amount')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <span for="">
                                            {{ __('label.unit') }}<span class="text-danger required"> (*)</span>
                                        </span>
                                        <select name="accomodations[{{ $ac_key }}][unit]" style="width: 100%;"
                                            class="form-control accomodations_unit select_unit @error('accomodations.'.$ac_key.'.unit') is-invalid @enderror"
                                            data-target="accomodations[{{ $ac_key }}][exchange_rate]">
                                            @foreach (config('const.units') as $key => $value)
                                            <option value="{{ $value }}" @if($accomodation['unit']==$value) selected @endif>
                                                {{ $value }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('accomodations.'.$ac_key.'.unit')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <span for="">
                                            {{ __('label.rate') }}<span class="text-danger required d-none"> (*)</span>
                                        </span>
                                        <input type="text"
                                            class="form-control accomodations_rate sync_total rate @error('accomodations.'.$ac_key.'.exchange_rate') is-invalid @enderror"
                                            name="accomodations[{{ $ac_key }}][exchange_rate]" value="{{ $accomodation['exchange_rate'] }}"
                                            autocomplete="off" @if($previewFlg) readonly @endif
                                            data-target="accomodations[{{ $ac_key }}][amount]">
                                        @error('accomodations.'.$ac_key.'.exchange_rate')
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
                                        <textarea name="accomodations[{{ $ac_key }}][note]" autocomplete="off" rows="1"
                                            class="form-control accomodations_note @error('accomodations.'.$ac_key.'.note') is-invalid @enderror">{{ $accomodation['note'] }}</textarea>
                                        @error('accomodations.'.$ac_key.'.note')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
                            <div class="card card-body card-accomodations copy d-none">
                                <div class="d-flex justify-content-between ">
                                    <span class="sp_acom_no badge badge-primary"></span>
                                    <button class="d-delete accomodations-btnDelete btn bg-gradient-primary btn-sm pt-0 pb-0 pl-3 pr-3"
                                        title="{{ __('label.button_delete') }}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <span for="">{{ __('label.amount') }}<span class="text-danger required"> (*)</span></span>
                                        <input type="text" class="form-control accomodations_amount" autocomplete="off" @if($previewFlg)
                                            readonly @endif>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <span for="">{{ __('label.unit') }}<span class="text-danger required"> (*)</span></span>
                                        <select style="width: 100%;" class="form-control accomodations_unit select_unit">
                                            @foreach (config('const.units') as $key => $value)
                                            <option value="{{ $value }}">
                                                {{ $value }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <span for="">{{ __('label.rate') }}<span class="text-danger required d-none"> (*)</span></span>
                                        <input type="text" class="form-control accomodations_rate" autocomplete="off" @if($previewFlg)
                                            readonly @endif>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        {{-- Note --}}
                                        <span class="mb-0 mr-1">{{ __('label.remarks') }}</span>
                                        <textarea class="form-control accomodations_note" autocomplete="off" rows="1"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(!$previewFlg)
                        <button id="accomodations-btnAdd" title="{{ __('label.button_addnew') }}"
                            class="btn bg-gradient-primary @if(!empty($accomodations) && count($accomodations) >= 10) d-none @endif">
                            <i class="fas fa-plus"></i>
                        </button>
                        @endif
                    </div>
                </div>
                <hr>
                {{-- TripFees - Communications --}}
                <div class="form-group row">
                    <div class="col-md-2 text-left">
                        <label>
                            <span class="sp_trans_no badge badge-warning">{{ config('const.trip_fee_type.communication') }}</span>
                            {{ __('label.business_communication') }}
                        </label>
                    </div>
                    <div class="col-md-10">
                        <div id="communications_block">
                            @if (!empty($communications))
                            @foreach ($communications as $com_key => $communication)
                            <div class="card card-body card-communications">
                                @if(!$previewFlg)
                                <div class="d-flex justify-content-between">
                                    <span class="sp_com_no badge badge-warning">{{ config('const.trip_fee_type.communication').($com_key + 1) }}</span>
                                    <button
                                        class="d-delete communications-btnDelete btn bg-gradient-warning btn-sm pt-0 pb-0 pl-3 pr-3"
                                        title="{{ __('label.button_delete') }}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                @endif
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <span for="">
                                            {{ __('label.type') }}<span class="text-danger required"> (*)</span>
                                        </span>
                                        <select name="communications[{{ $com_key }}][method]" style="width: 100%;"
                                            class="form-control communications_method @error('communications.'.$com_key.'.method') is-invalid @enderror">
                                            <option value="">
                                                {{ __('label.select') }}
                                            </option>
                                            @foreach (config('const.communications') as $key => $value)
                                            <option value="{{ $key }}" @if($communication['method'] == $key) selected @endif>
                                                {{ __('label.business_communications.'.$key) }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('communications.'.$com_key.'.method')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3">
                                        <span for="">
                                            {{ __('label.amount') }}<span class="text-danger required"> (*)</span>
                                        </span>
                                        <input type="text" name="communications[{{ $com_key }}][amount]"
                                            class="form-control communications_amount sync_total amount @error('communications.'.$com_key.'.amount') is-invalid @enderror"
                                            value="{{ $communication['amount'] }}" autocomplete="off" @if($previewFlg) readonly @endif
                                            data-target="communications[{{ $com_key }}][exchange_rate]">
                                        @error('communications.'.$com_key.'.amount')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3">
                                        <span for="">
                                            {{ __('label.unit') }}<span class="text-danger required"> (*)</span>
                                        </span>
                                        <select name="communications[{{ $com_key }}][unit]" style="width: 100%;"
                                            class="form-control communications_unit select_unit @error('communications.'.$com_key.'.unit') is-invalid @enderror"
                                            data-target="communications[{{ $com_key }}][exchange_rate]">
                                            @foreach (config('const.units') as $key => $value)
                                            <option value="{{ $value }}" @if($communication['unit']==$value) selected @endif>
                                                {{ $value }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('communications.'.$com_key.'.unit')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3">
                                        <span for="">
                                            {{ __('label.rate') }}<span class="text-danger required d-none"> (*)</span>
                                        </span>
                                        <input type="text"
                                            class="form-control communications_rate sync_total rate @error('communications.'.$com_key.'.exchange_rate') is-invalid @enderror"
                                            name="communications[{{ $com_key }}][exchange_rate]" value="{{ $communication['exchange_rate'] }}"
                                            autocomplete="off" @if($previewFlg) readonly @endif
                                            data-target="communications[{{ $com_key }}][amount]">
                                        @error('communications.'.$com_key.'.exchange_rate')
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
                                        <textarea name="communications[{{ $com_key }}][note]" autocomplete="off" rows="1"
                                            class="form-control communications_note @error('communications.'.$com_key.'.note') is-invalid @enderror">{{ $communication['note'] }}</textarea>
                                        @error('communications.'.$com_key.'.note')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
                            <div class="card card-body card-communications copy d-none">
                                <div class="d-flex justify-content-between ">
                                    <span class="sp_com_no badge badge-warning"></span>
                                    <button
                                        class="d-delete communications-btnDelete btn bg-gradient-warning btn-sm pt-0 pb-0 pl-3 pr-3"
                                        title="{{ __('label.button_delete') }}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <span for="">{{ __('label.type') }}<span class="text-danger required"> (*)</span></span>
                                        <select style="width: 100%;" class="form-control communications_method">
                                            <option value="">
                                                {{ __('label.select') }}
                                            </option>
                                            @foreach (config('const.communications') as $key => $value)
                                            <option value="{{ $key }}">
                                                {{ __('label.business_communications.'.$key) }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <span for="">{{ __('label.amount') }}<span class="text-danger required"> (*)</span></span>
                                        <input type="text" class="form-control communications_amount" autocomplete="off"
                                            @if($previewFlg) readonly @endif>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <span for="">{{ __('label.unit') }}<span class="text-danger required"> (*)</span></span>
                                        <select style="width: 100%;" class="form-control communications_unit select_unit">
                                            @foreach (config('const.units') as $key => $value)
                                            <option value="{{ $value }}">
                                                {{ $value }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <span for="">{{ __('label.rate') }}<span class="text-danger required d-none"> (*)</span></span>
                                        <input type="text" class="form-control communications_rate" autocomplete="off" @if($previewFlg)
                                            readonly @endif>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        {{-- Note --}}
                                        <span class="mb-0 mr-1">{{ __('label.remarks') }}</span>
                                        <textarea class="form-control communications_note" autocomplete="off" rows="1"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(!$previewFlg)
                        <button id="communications-btnAdd" title="{{ __('label.button_addnew') }}"
                            class="btn bg-gradient-warning @if(!empty($communications) && count($communications) >= 10) d-none @endif">
                            <i class="fas fa-plus"></i>
                        </button>
                        @endif
                    </div>
                </div>
                <hr>
                {{-- TripFees - OtherFees --}}
                <div class="form-group row">
                    <div class="col-md-2 text-left">
                        <label>
                            <span class="sp_trans_no badge badge-secondary">{{ config('const.trip_fee_type.otherfees') }}</span>
                            {{ __('label.business_other_fees') }}
                        </label>
                    </div>
                    <div class="col-md-10">
                        <div id="otherfees_block">
                            @if (!empty($otherfees))
                            @foreach ($otherfees as $o_key => $otherfee)
                            <div class="card card-body card-otherfees">
                                @if(!$previewFlg)
                                <div class="d-flex justify-content-between">
                                    <span
                                        class="sp_acom_no badge badge-secondary">{{ config('const.trip_fee_type.otherfees').($o_key + 1) }}</span>
                                    <button class="d-delete otherfees-btnDelete btn bg-gradient-secondary btn-sm pt-0 pb-0 pl-3 pr-3"
                                        title="{{ __('label.button_delete') }}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                @endif
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <span for="">
                                            {{ __('label.amount') }}<span class="text-danger required"> (*)</span>
                                        </span>
                                        <input type="text" name="otherfees[{{ $o_key }}][amount]"
                                            class="form-control otherfees_amount sync_total amount @error('otherfees.'.$o_key.'.amount') is-invalid @enderror"
                                            value="{{ $otherfee['amount'] }}" autocomplete="off" @if($previewFlg) readonly @endif
                                            data-target="otherfees[{{ $o_key }}][exchange_rate]">
                                        @error('otherfees.'.$o_key.'.amount')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <span for="">
                                            {{ __('label.unit') }}<span class="text-danger required"> (*)</span>
                                        </span>
                                        <select name="otherfees[{{ $o_key }}][unit]" style="width: 100%;"
                                            class="form-control otherfees_unit select_unit @error('otherfees.'.$o_key.'.unit') is-invalid @enderror"
                                            data-target="otherfees[{{ $o_key }}][exchange_rate]">
                                            @foreach (config('const.units') as $key => $value)
                                            <option value="{{ $value }}" @if($otherfee['unit']==$value) selected @endif>
                                                {{ $value }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('otherfees.'.$o_key.'.unit')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <span for="">
                                            {{ __('label.rate') }}<span class="text-danger required d-none"> (*)</span>
                                        </span>
                                        <input type="text"
                                            class="form-control otherfees_rate sync_total rate @error('otherfees.'.$o_key.'.exchange_rate') is-invalid @enderror"
                                            name="otherfees[{{ $o_key }}][exchange_rate]" value="{{ $otherfee['exchange_rate'] }}"
                                            autocomplete="off" @if($previewFlg) readonly @endif
                                            data-target="otherfees[{{ $o_key }}][amount]">
                                        @error('otherfees.'.$o_key.'.exchange_rate')
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
                                        <textarea name="otherfees[{{ $o_key }}][note]" autocomplete="off" rows="1"
                                            class="form-control otherfees_note @error('otherfees.'.$o_key.'.note') is-invalid @enderror">{{ $otherfee['note'] }}</textarea>
                                        @error('otherfees.'.$o_key.'.note')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
                            <div class="card card-body card-otherfees copy d-none">
                                <div class="d-flex justify-content-between ">
                                    <span class="sp_acom_no badge badge-secondary"></span>
                                    <button class="d-delete otherfees-btnDelete btn bg-gradient-secondary btn-sm pt-0 pb-0 pl-3 pr-3"
                                        title="{{ __('label.button_delete') }}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <span for="">{{ __('label.amount') }}<span class="text-danger required"> (*)</span></span>
                                        <input type="text" class="form-control otherfees_amount" autocomplete="off" @if($previewFlg)
                                            readonly @endif>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <span for="">{{ __('label.unit') }}<span class="text-danger required"> (*)</span></span>
                                        <select style="width: 100%;" class="form-control otherfees_unit select_unit">
                                            @foreach (config('const.units') as $key => $value)
                                            <option value="{{ $value }}">
                                                {{ $value }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <span for="">{{ __('label.rate') }}<span class="text-danger required d-none"> (*)</span></span>
                                        <input type="text" class="form-control otherfees_rate" autocomplete="off" @if($previewFlg)
                                            readonly @endif>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        {{-- Note --}}
                                        <span class="mb-0 mr-1">{{ __('label.remarks') }}</span>
                                        <textarea class="form-control otherfees_note" autocomplete="off" rows="1"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(!$previewFlg)
                        <button id="otherfees-btnAdd" title="{{ __('label.button_addnew') }}"
                            class="btn bg-gradient-secondary @if(!empty($otherfees) && count($otherfees) >= 10) d-none @endif">
                            <i class="fas fa-plus"></i>
                        </button>
                        @endif
                    </div>
                </div>
                <hr>
                {{-- Daily allowances --}}
                <div class="form-group row">
                    <div class="col-md-2 text-left caption">
                        <label>{{ __('label.business_daily_allowance') }}</label>
                    </div>
                    <div class="col-md-10">
                        {{-- Daily 1 --}}
                        <div class="form-row">
                            {{-- Amount --}}
                            <div class="form-group col-md-4 mb-1">
                                <span class="mb-0 mr-1">{{__('label.amount_per_day')}}</span>
                                <div class="input-group">
                                    <input type="text" id="daily1_amount" name="daily1_amount"
                                        class="form-control daily-input amount @error('daily1_amount') is-invalid @enderror" autocomplete="off"
                                        value="{{ $daily1_amount }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">VND</span>
                                    </div>
                                </div>
                                @error('daily1_amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            {{-- Num of Days --}}
                            <div class="form-group col-md-4 mb-1">
                                <span class="mb-0 mr-1">{{__('label.business_number_of_days')}}
                                    <span class="text-danger required d-none"> (*)</span>
                                </span>
                                <input type="text" id="daily1_days" name="daily1_days"
                                    class="form-control daily-input number_of_days @error('daily1_days') is-invalid @enderror" autocomplete="off"
                                    value="{{ $daily1_days }}">
                                @error('daily1_days')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <hr>
                        {{-- Daily 2 --}}
                        <div class="form-row">
                            {{-- Amount --}}
                            <div class="form-group col-md-4 mb-1">
                                <span class="mb-0 mr-1">{{__('label.amount_per_day')}}</span>
                                <div class="input-group">
                                    <input type="text" id="daily2_amount" name="daily2_amount"
                                        class="form-control daily-input amount @error('daily2_amount') is-invalid @enderror" autocomplete="off"
                                        value="{{ $daily2_amount }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">USD</span>
                                    </div>
                                </div>
                                @error('daily2_amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            {{-- Rate --}}
                            <div class="form-group col-md-4 mb-1">
                                <span class="mb-0 mr-1">{{__('label.rate')}}</span>
                                <div class="input-group">
                                    <input type="text" id="daily2_rate" name="daily2_rate"
                                        class="form-control daily-input rate @error('daily2_rate') is-invalid @enderror" autocomplete="off"
                                        value="{{ $daily2_rate }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">USD/VND</span>
                                    </div>
                                </div>
                                @error('daily2_rate')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            {{-- Num of Days --}}
                            <div class="form-group col-md-4 mb-1">
                                <span class="mb-0 mr-1">{{__('label.business_number_of_days')}}
                                    <span class="text-danger required d-none"> (*)</span>
                                </span>
                                <input type="text" id="daily2_days" name="daily2_days"
                                    class="form-control daily-input number_of_days @error('daily2_days') is-invalid @enderror" autocomplete="off"
                                    value="{{ $daily2_days }}">
                                @error('daily2_days')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <hr style="border-top: 1px dashed rgba(0,0,0,.1)">
                        <strong>{{ __('label.business_total_daily_allowance') }}</strong>:
                        <h5><span class="badge badge-info"><span id="total_daily_allowance"></span><b> VND</b></span></h5>
                    </div>
                </div>
                {{-- Total daily allowances --}}
                
                {{-- Cost to be charged to (Sec Code) --}}
                <hr>
                <div class="form-group row">
                    <div class="col-md-2 text-left">
                        <label>{{ __('label.business_charged_to') }}<span class="text-danger required"> (*)</span></label>
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
                                            class="form-control chargedbys_department @error('chargedbys.'.$key.'.department') is-invalid @enderror">
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
                                                class="form-control chargedbys_percent @error('chargedbys.'.$key.'.percent') is-invalid @enderror"
                                                value="{{ $chargedbys[$key]['percent'] }}" autocomplete="off" @if($previewFlg) readonly @endif>
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
                                            <input type="text" name="chargedbys[0][percent]" style="width: 100px" class="form-control chargedbys_percent"
                                                autocomplete="off">
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
                                            <input type="text" class="form-control chargedbys_percent" style="width: 80px" autocomplete="off">
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(!$previewFlg)
                        <button id="chargedbys-btnAdd" title="{{ __('label.button_addnew') }}"
                            class="btn bg-gradient-danger @if(!empty($chargedbys) && count($chargedbys) >= 3) d-none @endif">
                            <i class="fas fa-plus"></i>
                        </button>
                        @endif
                    </div>
                </div>
                {{-- Total Expenses --}}
                <hr>
                <div class="form-group row">
                    <div class="col-md-2 text-left caption">
                        <label>{{ __('label.business_total_expenses') }}</label>
                    </div>
                    <div class="col-md-10">
                        <h5><span class="badge badge-primary"><span id="total_expenses"></span><b> VND</b></span></h5>
                        {{-- <button id="refresh-total" class="btn bg-gradient-info btn-sm" title="{{ __('label.refresh') }}">
                            <i class="fas fa-redo"></i>
                        </button> --}}
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