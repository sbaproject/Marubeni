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
<script src="js/user/application/business/create.js"></script>
@endsection

@section('content')
@php

    $trans          = Session::exists('inputs') ? Session::get('inputs')['trans']         : (isset($application) ? $application->business->transportations : null);
    $destinations   = Session::exists('inputs') ? Session::get('inputs')['destinations']  : (isset($application) ? $application->business->destinations : null);
    $trip_dt_from   = Session::exists('inputs') ? Session::get('inputs')['trip_dt_from']  : (isset($application) ? $application->business->trip_dt_from : null);
    $trip_dt_to     = Session::exists('inputs') ? Session::get('inputs')['trip_dt_to']    : (isset($application) ? $application->business->trip_dt_to : null);
    $accommodation  = Session::exists('inputs') ? Session::get('inputs')['accommodation'] : (isset($application) ? $application->business->accommodation : null);
    $accompany      = Session::exists('inputs') ? Session::get('inputs')['accompany']     : (isset($application) ? $application->business->accompany : null);
    $borne_by       = Session::exists('inputs') ? Session::get('inputs')['borne_by']      : (isset($application) ? $application->business->borne_by : null);
    $comment        = Session::exists('inputs') ? Session::get('inputs')['comment']       : (isset($application) ? $application->business->comment : null);
    $file_path      = Session::exists('inputs') ? Session::get('inputs')['file_path']     : (isset($application) ? $application->file_path : null);

    // get action url
    if(isset($application)){
        if(isset($previewFlg)){
            $actionUrl = route('user.business.preview.pdf', $application->id);
        } else {
            $actionUrl = route('user.business.update', $application->id);
        }
    } else {
        $actionUrl = route('user.business.store');
    }
@endphp
<section class="content leave-application">
    <x-alert />
    <form method="POST"
        action="{{ $actionUrl }}"
        enctype="multipart/form-data">
        @csrf
        <div class="main-top">
            <h4 class="main-header-text">{{ Str::upper(__('label.form.biz_trip')) }}</h4>
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
                            <label>{{ __('label.business.application_no') }}</label>
                        </div>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" readonly
                                value="{{ $application->application_no }}">
                        </div>
                    </div>
                    <hr>
                @endif
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>{{ __('label.business.trip_destination') }}</label>
                    </div>
                    <div class="col-sm-10">
                        <input type="text" id="destinations" name="destinations" class="form-control"
                            autocomplete="off" value="{{ $destinations }}" @if(isset($previewFlg)) readonly @endif>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>{{ __('label.business.date_trip') }}</label>
                    </div>
                    <div class="col-sm-10">
                        <div class="col-sm-6 pl-0 pr-0">
                            <div class="row mb-2">
                                <span class="col-md-3">{{ __('label.from') }}</span>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <div id="trip_from" data-target-input="nearest" class="input-group date">
                                            <input type="text" class="form-control datetimepicker-input @error('trip_dt_from') is-invalid @enderror"
                                                data-target="#trip_from" @if(isset($previewFlg)) readonly @endif />
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
                                                data-target="#trip_to" @if(isset($previewFlg)) readonly @endif/>
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
                        <label>{{ __('label.business.transportation') }}</label>
                    </div>
                    <div class="col-sm-10">
                        <div id="transport_block">
                            @if (!empty($trans))
                                @foreach ($trans as $key => $value)
                                <div class="card card-body card-itinerary-transport">
                                    @if(!isset($previewFlg))
                                    <div class="d-delete d-flex justify-content-end @if(count($trans) === 1 && $key === 0) d-none @endif">
                                        <button class="btnDelete btn btn-danger btn-sm pt-0 pb-0 pl-3 pr-3 mb-1">
                                            {{ __('label.button.delete') }}
                                        </button>
                                    </div>
                                    @endif
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <div class="row mb-2">
                                                <span class="col-md-3">{{ __('label.business.departure') }}</span>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control departure @error('trans.'.$key.'.departure') is-invalid @enderror"
                                                        name="trans[{{ $key }}][departure]" value="{{ $trans[$key]['departure'] }}" autocomplete="off"
                                                        @if(isset($previewFlg)) readonly @endif>
                                                    @error('trans.'.$key.'.departure')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row">
                                                <span class="col-md-3">{{ __('label.business.arrival') }}</span>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control arrive @error('trans.'.$key.'.arrive') is-invalid @enderror"
                                                        name="trans[{{ $key }}][arrive]" value="{{ $trans[$key]['arrive'] }}" autocomplete="off"
                                                        @if(isset($previewFlg)) readonly @endif>
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
                                                <span class="col-md-3">{{ __('label.business.method') }}</span>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control method @error('trans.'.$key.'.method') is-invalid @enderror"
                                                        name="trans[{{ $key }}][method]" value="{{ $trans[$key]['method'] }}" autocomplete="off"
                                                        @if(isset($previewFlg)) readonly @endif>
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
                                        <button class="btnDelete btn btn-danger btn-sm pt-0 pb-0 pl-3 pr-3 mb-1">
                                            {{ __('label.button.delete') }}
                                        </button>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <div class="row mb-2">
                                                <span class="col-md-3">{{ __('label.business.departure') }}</span>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control departure" name="trans[0][departure]"
                                                        autocomplete="off" @if(isset($previewFlg)) readonly @endif>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <span class="col-md-3">{{ __('label.business.arrival') }}</span>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control arrive" name="trans[0][arrive]"
                                                        autocomplete="off" @if(isset($previewFlg)) readonly @endif>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <span class="col-md-3">{{ __('label.business.method') }}</span>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control method" name="trans[0][method]"
                                                        autocomplete="off" @if(isset($previewFlg)) readonly @endif>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="card card-body card-itinerary-transport copy d-none">
                                <div class="d-delete d-flex justify-content-end">
                                    <button class="btnDelete btn btn-danger btn-sm pt-0 pb-0 pl-3 pr-3 mb-1">
                                        {{ __('label.button.delete') }}
                                    </button>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <div class="row mb-2">
                                            <span class="col-md-3">{{ __('label.business.departure') }}</span>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control departure" autocomplete="off"
                                                    @if(isset($previewFlg)) readonly @endif>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <span class="col-md-3">{{ __('label.business.arrival') }}</span>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control arrive" autocomplete="off"
                                                    @if(isset($previewFlg)) readonly @endif>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <span class="col-md-3">{{ __('label.business.method') }}</span>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control method" autocomplete="off"
                                                    @if(isset($previewFlg)) readonly @endif>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(!isset($previewFlg))
                        <button id="btnAdd" class="btn btn-outline-dark">+ {{ __('label.button.addnew') }}</button>
                        @endif
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>{{ __('label.business.accommodation') }}</label>
                    </div>
                    <div class="col-sm-10">
                        <input type="text" id="accommodation" name="accommodation" class="form-control"
                            autocomplete="off" value="{{ $accommodation }}" @if(isset($previewFlg)) readonly @endif>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>{{ __('label.business.accompany') }}</label>
                    </div>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="accompany" name="accompany"
                            autocomplete="off" value="{{ $accompany }}" @if(isset($previewFlg)) readonly @endif>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>{{ __('label.business.borne_by') }}</label>
                    </div>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="borne_by" name="borne_by"
                             autocomplete="off" value="{{ $borne_by }}" @if(isset($previewFlg)) readonly @endif>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>{{ __('label.business.comment') }}</label>
                    </div>
                    <div class="col-sm-10">
                        <textarea class="form-control" id="comment" name="comment" rows="2" style="width: 100%;"
                            @if(isset($previewFlg)) readonly @endif>{{ $comment }}</textarea>
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
                                    <a id="file_link" href="{{ Storage::url($file_path) }}" target="_blank">
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
                @if(!isset($previewFlg))
                <hr>
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label style="color: red;">{{ __('label.leave.caption.subsequent') }}</label>
                    </div>
                    <div class="col-sm-10">
                        <div class="form-check">
                            <input type="checkbox" id="subsequent" name="subsequent" class="form-check-input" @if (old('subsequent') != null) checked @endif>
                            <label class="form-check-label" for="subsequent">{{ __('label.on') }}</label>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <!-- /.card -->
        @if (!isset($previewFlg))
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
        @endif
        <br>
        <br>
    </form>
</section>
@endsection