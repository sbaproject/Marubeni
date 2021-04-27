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
</style>
@endsection

@section('js')
{{-- datetimepicker --}}
<script src="js/moment/moment.min.js"></script>
<script src="js/moment/locale/{{ config('app.locale') }}.js"></script>
<script src="js/bootstrap-datetimepicker.js"></script>
{{-- for this view --}}
<script src="js/user/application/business/input.js"></script>
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

    $itineraries        = Session::exists('inputs') ? Session::get('inputs')['trans']               : (isset($application) ? $application->business->itineraries : null);
    $destinations       = Session::exists('inputs') ? Session::get('inputs')['destinations']        : (isset($application) ? $application->business->destinations : null);
    $trip_dt_from       = Session::exists('inputs') ? Session::get('inputs')['trip_dt_from']        : (isset($application) ? $application->business->trip_dt_from : null);
    $trip_dt_to         = Session::exists('inputs') ? Session::get('inputs')['trip_dt_to']          : (isset($application) ? $application->business->trip_dt_to : null);
    $accommodation      = Session::exists('inputs') ? Session::get('inputs')['accommodation']       : (isset($application) ? $application->business->accommodation : null);
    $accompany          = Session::exists('inputs') ? Session::get('inputs')['accompany']           : (isset($application) ? $application->business->accompany : null);
    $borne_by           = Session::exists('inputs') ? Session::get('inputs')['borne_by']            : (isset($application) ? $application->business->borne_by : null);
    $comment            = Session::exists('inputs') ? Session::get('inputs')['comment']             : (isset($application) ? $application->business->comment : null);
    $subsequent         = Session::exists('inputs') ? Session::get('inputs')['subsequent']          : (isset($application) ? $application->subsequent : null);
    $subsequent_reason  = Session::exists('inputs') ? Session::get('inputs')['subsequent_reason']   : (isset($application) ? $application->subsequent_reason : null);
    $budget_position    = Session::exists('inputs') ? Session::get('inputs')['budget_position']     : (isset($application) ? $application->budget_position : null);
    $file_path          = Session::exists('inputs') ? Session::get('inputs')['file_path']           : (isset($application) ? $application->file_path : null);

    // get action url
    if(isset($application)){
        if($previewFlg){
            $actionUrl = route('user.business.preview.pdf', $application->id);
        } else {
            $actionUrl = route('user.business.update', $application->id);
        }
    } else {
        $actionUrl = route('user.business.store');
    }

@endphp
<section class="content leave-application">
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
                            <a class="btn bg-gradient-success" href="{{ route('user.business2.show', $application->id) }}"
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
                            <label>{{ __('label.business_application_no') }}</label>
                        </div>
                        <div class="col-sm-10">
                            {{ $application->application_no }}
                        </div>
                    </div>
                    <hr>
                @endif
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>{{ __('label.business_trip_destination') }}<span class="text-danger required"> (*)</span></label>
                    </div>
                    <div class="col-sm-10">
                        <input type="text" id="destinations" name="destinations" class="form-control @error('destinations') is-invalid @enderror"
                            autocomplete="off" value="{{ $destinations }}" @if($previewFlg) readonly @endif>
                        @error('destinations')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>{{ __('label.business_date_trip') }}<span class="text-danger required"> (*)</span></label>
                    </div>
                    <div class="col-sm-10">
                        <div class="col-sm-6 pl-0 pr-0">
                            <div class="row mb-2">
                                <span class="col-md-3">{{ __('label.from') }}</span>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <div id="trip_from" data-target-input="nearest" class="input-group date">
                                            <input type="text" class="form-control datetimepicker-input @error('trip_dt_from') is-invalid @enderror"
                                                data-target="#trip_from" @if($previewFlg) readonly @endif />
                                            <div class="input-group-addon input-group-append"
                                                data-target="#trip_from" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar-alt"></i>
                                                </div>
                                            </div>
                                        </div>
                                        @error('trip_dt_from')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <input type="hidden" id="trip_dt_from" name="trip_dt_from" value="{{ $trip_dt_from }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <span class="col-md-3">{{ __('label.to') }}</span>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <div class="input-group date" id="trip_to" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input @error('trip_dt_to') is-invalid @enderror"
                                                data-target="#trip_to" @if($previewFlg) readonly @endif/>
                                            <div class="input-group-addon input-group-append"
                                                data-target="#trip_to" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar-alt"></i>
                                                </div>
                                            </div>
                                        </div>
                                        @error('trip_dt_to')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <input type="hidden" id="trip_dt_to" name="trip_dt_to" value="{{ $trip_dt_to }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>{{ __('label.business_transportation') }}<span class="text-danger required"> (*)</span></label>
                    </div>
                    <div class="col-sm-10">
                        <div id="transport_block">
                            @if (!empty($itineraries))
                                @foreach ($itineraries as $key => $value)
                                <div class="card card-body card-itinerary-transport">
                                    @if(!$previewFlg)
                                    <div class="d-delete d-flex justify-content-end @if(count($itineraries) === 1 && $key === 0) d-none @endif">
                                        <button class="btnDelete btn bg-gradient-danger btn-sm pt-0 pb-0 pl-3 pr-3 mb-1">
                                            {{ __('label.button_delete') }}
                                        </button>
                                    </div>
                                    @endif
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <div class="row mb-2">
                                                <span class="col-md-3">{{ __('label.business_departure') }}<span class="text-danger required"> (*)</span></span>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control departure @error('trans.'.$key.'.departure') is-invalid @enderror"
                                                        name="trans[{{ $key }}][departure]" value="{{ $itineraries[$key]['departure'] }}" autocomplete="off"
                                                        @if($previewFlg) readonly @endif>
                                                    @error('trans.'.$key.'.departure')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row">
                                                <span class="col-md-3">{{ __('label.business_arrival') }}<span class="text-danger required"> (*)</span></span>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control arrive @error('trans.'.$key.'.arrive') is-invalid @enderror"
                                                        name="trans[{{ $key }}][arrive]" value="{{ $itineraries[$key]['arrive'] }}" autocomplete="off"
                                                        @if($previewFlg) readonly @endif>
                                                    @error('trans.'.$key.'.arrive')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <span class="col-md-3">{{ __('label.business_method') }}<span class="text-danger required"> (*)</span></span>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control method @error('trans.'.$key.'.method') is-invalid @enderror"
                                                        name="trans[{{ $key }}][method]" value="{{ $itineraries[$key]['method'] }}" autocomplete="off"
                                                        @if($previewFlg) readonly @endif>
                                                    @error('trans.'.$key.'.method')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="card card-body card-itinerary-transport">
                                    <div class="d-delete d-flex justify-content-end d-none">
                                        <button class="btnDelete btn bg-gradient-danger btn-sm pt-0 pb-0 pl-3 pr-3 mb-1">
                                            {{ __('label.button_delete') }}
                                        </button>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <div class="row mb-2">
                                                <span class="col-md-3">{{ __('label.business_departure') }}<span class="text-danger required"> (*)</span></span>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control departure" name="trans[0][departure]"
                                                        autocomplete="off" @if($previewFlg) readonly @endif>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <span class="col-md-3">{{ __('label.business_arrival') }}<span class="text-danger required"> (*)</span></span>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control arrive" name="trans[0][arrive]"
                                                        autocomplete="off" @if($previewFlg) readonly @endif>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <span class="col-md-3">{{ __('label.business_method') }}<span class="text-danger required"> (*)</span></span>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control method" name="trans[0][method]"
                                                        autocomplete="off" @if($previewFlg) readonly @endif>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="card card-body card-itinerary-transport copy d-none">
                                <div class="d-delete d-flex justify-content-end">
                                    <button class="btnDelete btn bg-gradient-danger btn-sm pt-0 pb-0 pl-3 pr-3 mb-1">
                                        {{ __('label.button_delete') }}
                                    </button>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <div class="row mb-2">
                                            <span class="col-md-3">{{ __('label.business_departure') }}<span class="text-danger required"> (*)</span></span>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control departure" autocomplete="off"
                                                    @if($previewFlg) readonly @endif>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <span class="col-md-3">{{ __('label.business_arrival') }}<span class="text-danger required"> (*)</span></span>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control arrive" autocomplete="off"
                                                    @if($previewFlg) readonly @endif>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <span class="col-md-3">{{ __('label.business_method') }}<span class="text-danger required"> (*)</span></span>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control method" autocomplete="off"
                                                    @if($previewFlg) readonly @endif>
                                            </div>
                                        </div>
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
                <div class="form-group row ">
                    <label class="col-lg-2 col-form-label text-left">
                        {{ __('label.entertainment_budget_position') }}<span class="text-danger required"> (*)</span>
                    </label>
                    <div class="col-lg-10 text-lg-left text-left">
                        <fieldset class="@error('budget_position') form-control is-invalid @enderror">
                            <label class="radio-inline com_title col-form-label">
                                @php
                                $val = config('const.budget.position.economy_class');
                                $key = array_search($val, config('const.budget.position'));
                                @endphp
                                <input type="radio" name="rd_budget_position" value="{{ $val }}"
                                    @if($budget_position !==null && $budget_position==$val) checked @endif
                                    @if($previewFlg) disabled @endif>
                                {{ __('label.budget_'. $key) }}
                            </label>
                            <label class="radio-inline com_title col-form-label">
                                @php
                                $val = config('const.budget.position.business_class');
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
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>{{ __('label.business_accommodation') }}<span class="text-danger required"> (*)</span></label>
                    </div>
                    <div class="col-sm-10">
                        <input type="text" id="accommodation" name="accommodation" class="form-control @error('accommodation') is-invalid @enderror"
                            autocomplete="off" value="{{ $accommodation }}" @if($previewFlg) readonly @endif>
                        @error('accommodation')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>{{ __('label.business_accompany') }}<span class="text-danger required"> (*)</span></label>
                    </div>
                    <div class="col-sm-10">
                        <input type="text" id="accompany" name="accompany" class="form-control @error('accompany') is-invalid @enderror"
                            autocomplete="off" value="{{ $accompany }}" @if($previewFlg) readonly @endif>
                        @error('accompany')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>{{ __('label.business_borne_by') }}<span class="text-danger required"> (*)</span></label>
                    </div>
                    <div class="col-sm-10">
                        <input type="text" id="borne_by" name="borne_by" class="form-control @error('borne_by') is-invalid @enderror"
                             autocomplete="off" value="{{ $borne_by }}" @if($previewFlg) readonly @endif>
                        @error('borne_by')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>{{ __('label.business_comment') }}</label>
                    </div>
                    <div class="col-sm-10">
                        <textarea class="form-control" id="comment" name="comment" rows="2" style="width: 100%;"
                            @if($previewFlg) readonly @endif>{{ $comment }}</textarea>
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
                                @if($subsequent !==null && $subsequent==config('const.check.on')) checked @endif
                                @if($previewFlg) disabled @endif>
                            <input type="hidden" id="subsequent" name="subsequent" value="{{ $subsequent }}">
                            <label class="form-check-label" for="cb_subsequent">{{ __('label.on') }}</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label text-left">
                        {{ __('label.application_subsequent_reason') }}
                    </label>
                    <div class="col-lg-10">
                        <textarea id="subsequent_reason" name="subsequent_reason"
                            class="form-control @error('subsequent_reason') is-invalid @enderror" rows="2" @if($previewFlg) readonly
                            @endif>{{ $subsequent_reason }}</textarea>
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