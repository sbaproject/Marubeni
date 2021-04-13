@extends('layouts.master')

@section('title')
{{ __('label.biz_application') }}
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
</style>
@endsection

@section('js')
{{-- datetimepicker --}}
<script src="js/moment/moment.min.js"></script>
<script src="js/moment/locale/{{ config('app.locale') }}.js"></script>
<script src="js/bootstrap-datetimepicker.js"></script>
{{-- for this view --}}
<script src="js/user/application/business2/input.js"></script>
@endsection

@section('content-header')
{{ __('label.form_biz_trip') }}
@endsection

@section('content-breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('user.form.index') }}">{{ __('label.application_list') }}</a></li>
<li class="breadcrumb-item active">{{ __('label.form_biz_trip') }}</li>
@endsection

@section('content')
@php

    // $trans              = Session::exists('inputs') ? Session::get('inputs')['trans']           : (isset($application) ? $application->business->transportations : null);
    // $destinations       = Session::exists('inputs') ? Session::get('inputs')['destinations']    : (isset($application) ? $application->business->destinations : null);
    // $trip_dt_from       = Session::exists('inputs') ? Session::get('inputs')['trip_dt_from']    : (isset($application) ? $application->business->trip_dt_from : null);
    // $trip_dt_to         = Session::exists('inputs') ? Session::get('inputs')['trip_dt_to']      : (isset($application) ? $application->business->trip_dt_to : null);
    // $accommodation      = Session::exists('inputs') ? Session::get('inputs')['accommodation']   : (isset($application) ? $application->business->accommodation : null);
    // $accompany          = Session::exists('inputs') ? Session::get('inputs')['accompany']       : (isset($application) ? $application->business->accompany : null);
    // $borne_by           = Session::exists('inputs') ? Session::get('inputs')['borne_by']        : (isset($application) ? $application->business->borne_by : null);
    // $comment            = Session::exists('inputs') ? Session::get('inputs')['comment']         : (isset($application) ? $application->business->comment : null);
    // $subsequent         = Session::exists('inputs') ? Session::get('inputs')['subsequent']      : (isset($application) ? $application->subsequent : null);
    // $budget_position    = Session::exists('inputs') ? Session::get('inputs')['budget_position'] : (isset($application) ? $application->budget_position : null);
    // $file_path          = Session::exists('inputs') ? Session::get('inputs')['file_path']       : (isset($application) ? $application->file_path : null);

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
        action="{{ route('user.business2.create', $applicationId) }}"
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
                {{-- @if (isset($application))
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
                            <label>{{ __('label.business_application_no') }}</label>
                        </div>
                        <div class="col-sm-10">
                            {{ $application->application_no }}
                        </div>
                    </div>
                    <hr>
                @endif --}}

                {{-- Destinations --}}
                <div class="form-group row">
                    <div class="col-md-2 text-left caption">
                        <label>Destinations<span class="text-danger required"> (*)</span></label>
                    </div>
                    <div class="col-md-10">
                        <input type="text" id="destinations" name="destinations" class="form-control @error('destinations') is-invalid @enderror"
                            autocomplete="off" value="">
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
                        <label>Number of days<span class="text-danger required"> (*)</span></label>
                    </div>
                    <div class="col-md-2">
                        <input type="text" id="accommnumber_of_daysodation" name="number_of_days"
                            class="form-control @error('number_of_days') is-invalid @enderror" autocomplete="off"
                            value="">
                        @error('number_of_days')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <hr>
                {{-- Total daily allowances --}}
                <div class="form-group row">
                    <div class="col-md-2 text-left caption">
                        <label>Total daily allowances<span class="text-danger required"> (*)</span></label>
                    </div>
                    <div class="col-md-10">
                        <div class="form-row">
                            {{-- Unit --}}
                            <div class="form-group col-md-4 mb-1">
                                <label class="mb-0 mr-1">Unit</label>
                                <select name="total_daily_unit" style="width: 100%;"
                                    class="form-control @error('total_daily_unit') is-invalid @enderror">
                                    <option>
                                        {{ __('label.select') }}
                                    </option>
                                    @foreach (config('const.units') as $value)
                                    <option value="{{ $value }}">
                                        {{ $value }}
                                    </option>
                                    @endforeach
                                </select>
                                {{-- @error('total_daily_unit') --}}
                                <span class="invalid-feedback" role="alert">
                                    <strong>asdasdasdasdasdasdasd qwdqwd</strong>
                                </span>
                                {{-- @enderror --}}
                            </div>
                            {{-- Rate --}}
                            <div class="form-group col-md-4 mb-1">
                                <label class="mb-0 mr-1">Rate</label>
                                <input type="text" id="total_daily_rate" name="total_daily_rate"
                                    class="form-control @error('total_daily_rate') is-invalid @enderror" autocomplete="off" value="">
                                {{-- @error('total_daily_rate') --}}
                                <span class="invalid-feedback" role="alert">
                                    <strong>asdasdasdasdasdasdasd qwdqwd</strong>
                                </span>
                                {{-- @enderror --}}
                            </div>
                            {{-- Amount --}}
                            <div class="form-group col-md-4 mb-1">
                                <label class="mb-0 mr-1">Amount</label>
                                <input type="text" id="total_daily_allowance" name="total_daily_allowance"
                                    class="form-control @error('total_daily_allowance') is-invalid @enderror" autocomplete="off" value="">
                                {{-- @error('total_daily_allowance') --}}
                                <span class="invalid-feedback" role="alert">
                                    <strong>asdasdasdasdasdasdasd qwdqwd</strong>
                                </span>
                                {{-- @enderror --}}
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                {{-- Other Fees --}}
                <div class="form-group row">
                    <div class="col-md-2 text-left caption">
                        <label>Other Fees<span class="text-danger required"> (*)</span></label>
                    </div>
                    <div class="col-md-10">
                        <div class="form-row">
                            {{-- Unit --}}
                            <div class="form-group col-md-4 mb-1">
                                <label class="mb-0 mr-1">Unit</label>
                                <select name="other_fees_unit" style="width: 100%;"
                                    class="form-control @error('other_fees_unit') is-invalid @enderror">
                                    <option>
                                        {{ __('label.select') }}
                                    </option>
                                    @foreach (config('const.units') as $value)
                                    <option value="{{ $value }}">
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
                                <label class="mb-0 mr-1">Rate</label>
                                <input type="text" id="other_fees_rate" name="other_fees_rate"
                                    class="form-control @error('other_fees_rate') is-invalid @enderror" autocomplete="off" value="">
                                @error('other_fees_rate')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            {{-- Amount --}}
                            <div class="form-group col-md-4 mb-1">
                                <label class="mb-0 mr-1">Amount</label>
                                <input type="text" id="other_fees" name="other_fees" class="form-control @error('other_fees') is-invalid @enderror"
                                    autocomplete="off" value="">
                                @error('other_fees')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                {{-- Note --}}
                                <label class="mb-0 mr-1">Note</label>
                                <textarea type="text" id="other_fees_note" name="other_fees_note" autocomplete="off" rows="3"
                                    class="form-control @error('other_fees_note') is-invalid @enderror"></textarea>
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
                        <label>Cost to be charged to (Sec Code)<span class="text-danger required"> (*)</span></label>
                    </div>
                    <div class="col-md-10">
                        <select name="charged_to" style="width: auto;" class="form-control @error('charged_to') is-invalid @enderror">
                            <option>
                                {{ __('label.select') }}
                            </option>
                            @foreach ($departments as $item)
                            <option value="{{ $item->id }}">
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
                <hr>
                {{-- Under Instruction by --}}
                <div class="form-group row">
                    <div class="col-md-2 text-left caption">
                        <label>Under Instruction by<span class="text-danger required"> (*)</span></label>
                    </div>
                    <div class="col-md-10">
                        <div class="form-row">
                            <div class="form-group col-md-6 mb-1">
                                <label>Date<span class="text-danger required"> (*)</span></label>
                                <div id="instruction_date_picker" data-target-input="nearest" class="input-group date">
                                    <input type="text" class="form-control datetimepicker-input @error('under_instruction_date') is-invalid @enderror"
                                        data-target="#instruction_date_picker" />
                                    <div class="input-group-addon input-group-append" data-target="#instruction_date_picker"
                                        data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="under_instruction_date" name="under_instruction_date" value="">
                                @error('under_instruction_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 mb-1">
                                <label>Approval No<span class="text-danger required"> (*)</span></label>
                                <input type="text" id="under_instruction_approval_no" name="under_instruction_approval_no"
                                    class="form-control @error('under_instruction_approval_no') is-invalid @enderror" autocomplete="off" value="">
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