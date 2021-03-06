@extends('layouts.master')

@section('title')
{{ __('label.leave_application') }}
@endsection

@section('css')
{{-- for this view --}}
<link rel="stylesheet" href="css/user/04_leave_application.css">
<style type="text/css">
.invalid-feedback {
    display: block;
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
{{-- for this view --}}
<script src="js/user/application/leave/input.js"></script>
<script>
    const code_leave = @json(config('const.code_leave'));
    const paid_type = @json(config('const.paid_type'));
    const previewFlg = @json($previewFlg ? true : false);
</script>
@endsection

@section('content-header')
{{ __('label.form_leave') }}
@endsection

@section('content-breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('user.form.index') }}">{{ __('label.application_list') }}</a></li>
<li class="breadcrumb-item active">{{ __('label.form_leave') }}</li>
@endsection

@section('content')
@php
    $code_leave         = Session::exists('inputs') ? Session::get('inputs')['code_leave']          :  (isset($application) ? $application->leave->code_leave : null);
    $paid_type          = Session::exists('inputs') ? Session::get('inputs')['paid_type']           :  (isset($application) ? $application->leave->paid_type : null);
    $reason_leave       = Session::exists('inputs') ? Session::get('inputs')['reason_leave']        :  (isset($application) ? $application->leave->reason_leave : null);
    $date_from          = Session::exists('inputs') ? Session::get('inputs')['date_from']           :  (isset($application) ? $application->leave->date_from : null);
    $date_to            = Session::exists('inputs') ? Session::get('inputs')['date_to']             :  (isset($application) ? $application->leave->date_to : null);
    $time_day           = Session::exists('inputs') ? Session::get('inputs')['time_day']            :  (isset($application) ? $application->leave->time_day : null);
    $time_from          = Session::exists('inputs') ? Session::get('inputs')['time_from']           :  (isset($application) ? $application->leave->time_from : null);
    $time_to            = Session::exists('inputs') ? Session::get('inputs')['time_to']             :  (isset($application) ? $application->leave->time_to : null);
    $maternity_from     = Session::exists('inputs') ? Session::get('inputs')['maternity_from']      :  (isset($application) ? $application->leave->maternity_from : null);
    $maternity_to       = Session::exists('inputs') ? Session::get('inputs')['maternity_to']        :  (isset($application) ? $application->leave->maternity_to : null);
    $days_use           = Session::exists('inputs') ? Session::get('inputs')['days_use']            :  (isset($application) ? $application->leave->days_use : null);
    $times_use          = Session::exists('inputs') ? Session::get('inputs')['times_use']           :  (isset($application) ? $application->leave->times_use : null);
    $file_path          = Session::exists('inputs') ? Session::get('inputs')['file_path']           :  (isset($application) ? $application->file_path : null);
    $subsequent         = Session::exists('inputs') ? Session::get('inputs')['subsequent']          :  (isset($application) ? $application->subsequent : null);
    $subsequent_reason  = Session::exists('inputs') ? Session::get('inputs')['subsequent_reason']   :  (isset($application) ? $application->subsequent_reason : null);
    $applicant          = isset($application) ? $application->applicant : Auth::user();

    // get action url
    if(isset($application)){
        if($previewFlg){
            $actionUrl = route('user.leave.preview.pdf', $application->id);
        } else {
            $actionUrl = route('user.leave.update', $application->id);
        }
    } else {
        $actionUrl = route('user.leave.store');
    }
@endphp
<section class="content leave-application">
    {{-- <x-alert /> --}}
    <form method="POST" action="{{ $actionUrl }}"
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
                        <label>{{ __('label.leave.caption_code_leave') }}<span class="text-danger required"> (*)</span></label>
                    </div>
                    <div class="col-sm-10">
                        <select name="rd_code_leave" style="width: auto;"
                            class="form-control @error('code_leave') is-invalid @enderror"
                            @if($previewFlg) disabled @endif>
                            <option value="empty" @if($code_leave == 'empty') selected @endif>
                                {{ __('label.select') }}
                            </option>
                            @foreach (config('const.code_leave') as $key => $value)
                            <option value="{{ $value }}" @if($code_leave !== null && strval($code_leave) === strval($value)) selected @endif>
                                {{ $key }} : {{ __('label.leave.code_leave.'.$key) }}
                            </option>
                            @endforeach
                        </select>
                        <input type="hidden" id="code_leave" name="code_leave" value="{{ $code_leave }}">
                        @error('code_leave')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <hr class="line-bottom">
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>{{ __('label.leave.caption_reason_leave') }}<span class="text-danger required"> (*)</span></label>
                    </div>
                    <div class="col-sm-10">
                        <textarea name="reason_leave" id="reason_leave" rows="3" class="form-control @error('reason_leave') is-invalid @enderror"
                            style="width: 100%;" @if($previewFlg) readonly @endif
                            placeholder="{{ __('label.leave.caption_reason_leave') }}">{{ $reason_leave }}</textarea>
                        @error('reason_leave')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <hr class="line-bottom">
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>{{ __('label.leave.caption_paid_type') }}<span id="rq_paid_type" class="text-danger required"> (*)</span></label>
                    </div>
                    <div class="col-sm-10">
                        @php
                            if($previewFlg
                                || $code_leave != config('const.code_leave.SL')
                                || ($code_leave == config('const.code_leave.SL') && $paid_type != config('const.paid_type.AL'))){
                                $paidTypeReadFlg = true;
                            }
                        @endphp
                        <fieldset id="fs_paid_type" class="@error('paid_type') form-control is-invalid @enderror">
                            @foreach (config('const.paid_type') as $key => $value)
                            <label class="radio-inline">
                                <input type="radio" name="rd_paid_type" value="{{ $value }}"
                                    @if ($paid_type !==null && $paid_type == $value) checked @endif
                                    @if(isset($paidTypeReadFlg)) disabled @endif>
                                {{ __('label.leave.paid_type.'.$key) }}
                            </label>
                            @endforeach
                        </fieldset>
                        <input type="hidden" id="paid_type" name="paid_type" value="{{ $paid_type }}">
                        @error('paid_type')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <hr class="line-bottom">
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>{{ __('label.leave.caption_date_leave') }}<span id="rq_date_leave" class="text-danger required"> (*)</span></label>
                    </div>
                    <div class="col-sm-10">
                        <div class="row mb-2">
                            <span class="col-md-2">{{ __('label.from') }}</span>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="input-group date" id="dateLeaveFrom" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input @error('date_from') is-invalid @enderror"
                                            data-target="#dateLeaveFrom"
                                            @if($previewFlg || $code_leave == config('const.code_leave.ML')) readonly @endif/>
                                        <div class="input-group-addon input-group-append" data-target="#dateLeaveFrom"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                    </div>
                                    @error('date_from')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <input type="hidden" id="date_from" name="date_from" value="{{ $date_from }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <span class="col-md-2">{{ __('label.to') }}</span>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="input-group date" id="dateLeaveTo" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input @error('date_to') is-invalid @enderror"
                                            data-target="#dateLeaveTo"
                                            @if($previewFlg || $code_leave == config('const.code_leave.ML')) readonly @endif/>
                                        <div class="input-group-addon input-group-append" data-target="#dateLeaveTo"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                    </div>
                                    @error('date_to')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <input type="hidden" id="date_to" name="date_to" value="{{ $date_to }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="line-bottom">
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>{{ __('label.leave.caption_time_leave') }}</label>
                    </div>
                    <div class="col-sm-10">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="row">
                                    <span class="col-md-4">{{ __('label.date') }}</span>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <div class="input-group date" id="timeLeaveDate"
                                                data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input"
                                                    data-target="#timeLeaveDate"
                                                    @if($previewFlg || $code_leave == config('const.code_leave.ML')) readonly @endif/>
                                                <div class="input-group-addon input-group-append"
                                                    data-target="#timeLeaveDate" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar-alt"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" id="time_day" name="time_day"
                                                value="{{ $time_day }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <div class="row mb-2">
                                    <span class="col-md-3">{{ __('label.from') }}</span>
                                    <div class="col-md-9">
                                        <input type="text" id="timeLeaveFrom" name="time_from"
                                            class="form-control datetimepicker-input" data-toggle="datetimepicker"
                                            data-target="#timeLeaveFrom" autocomplete="off"
                                            value="{{ $time_from }}"
                                            @if($previewFlg || $code_leave == config('const.code_leave.ML')) readonly @endif/>
                                    </div>
                                </div>
                                <div class="row">
                                    <span class="col-md-3">{{ __('label.to') }}</span>
                                    <div class="col-md-9">
                                        <input type="text" id="timeLeaveTo" name="time_to"
                                            class="form-control datetimepicker-input" data-toggle="datetimepicker"
                                            data-target="#timeLeaveTo" autocomplete="off"
                                            value="{{ $time_to }}"
                                            @if($previewFlg || $code_leave == config('const.code_leave.ML')) readonly @endif/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="line-bottom">
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>{{ __('label.leave.caption_maternity_leave') }}<span id="rq_maternity_leave" class="text-danger required"> (*)</span></label>
                    </div>
                    <div class="col-sm-10">
                        <div class="row mb-2">
                            <span class="col-md-2">{{ __('label.from') }}</span>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="input-group date" id="maternityLeaveFrom" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input @error('maternity_from') is-invalid @enderror"
                                            data-target="#maternityLeaveFrom"
                                            @if($previewFlg || $code_leave != config('const.code_leave.ML')) readonly @endif/>
                                        <div class="input-group-addon input-group-append"
                                            data-target="#maternityLeaveFrom" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                    </div>
                                    @error('maternity_from')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <input type="hidden" id="maternity_from" name="maternity_from"
                                        value="{{ $maternity_from }}">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <span class="col-md-2">{{ __('label.to') }}</span>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="input-group date" id="maternityLeaveTo" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input @error('maternity_to') is-invalid @enderror"
                                            data-target="#maternityLeaveTo"
                                            @if($previewFlg || $code_leave != config('const.code_leave.ML')) readonly @endif/>
                                        <div class="input-group-addon input-group-append"
                                            data-target="#maternityLeaveTo" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                    </div>
                                    @error('maternity_to')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <input type="hidden" id="maternity_to" name="maternity_to"
                                        value="{{ $maternity_to }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="line-bottom">
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>{{ __('label.leave.caption_annual_leave') }}</label>
                    </div>
                    <div class="col-sm-10">
                        <div class="row mb-2">
                            <span class="col-md-2">{{ __('label.leave.caption_entitled_year') }}</span>
                            <div class="col-md-10">
                                <input type="text" id="entitled" name="entitled_days" class="form-control input-custom-2"
                                    value="{{ $applicant->leave_days }}" readonly>
                                &nbsp;&nbsp;
                                <span>{{ __('label.leave.caption_days') }}</span>
                            </div>
                        </div>
                        @php
                            $workingHourPerDay = config('const.working_hours_per_day');
                            // get total entitled hours
                            $entitledHours = $applicant->leave_days * $workingHourPerDay;
                            // get total used hours
                            $totalUsedHours = $entitledHours - ($applicant->leave_remaining_days * $workingHourPerDay) - $applicant->leave_remaining_time;
                            // used days
                            $usedDays = intval($totalUsedHours / $workingHourPerDay);
                            // used hours
                            $usedHours = (($totalUsedHours % $workingHourPerDay) / $workingHourPerDay) * $workingHourPerDay;
                        @endphp
                        <div class="row mb-2">
                            <span class="col-md-2">{{ __('label.leave.caption_used_this_year') }}</span>
                            <div class="col-md-10">
                                <input type="text" class="form-control input-custom-2" id="used_days" name="used_days"
                                    value="{{ $usedDays }}" readonly>
                                &nbsp;&nbsp;
                                <span>{{ __('label.leave.caption_days') }}</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="text" class="form-control input-custom-2" id="used_time" name="used_hours"
                                    value="{{ $usedHours }}" readonly>
                                &nbsp;&nbsp;
                                <span>{{ __('label.leave.caption_hours') }}</span>

                            </div>
                        </div>
                        <div class="row mb-2">
                            <span class="col-md-2">{{ __('label.leave.caption_take_this_time') }} <span id="rq_take_this_time" class="text-danger required"> (*)</span></span>
                            <div class="col-md-10">
                                @php
                                    if($previewFlg
                                        || $code_leave == config('const.code_leave.ML')
                                        || ($code_leave == config('const.code_leave.SL') && $paid_type != config('const.paid_type.AL'))) {
                                            $daysUsedReadFlg = true;
                                        }
                                @endphp
                                <input type="text" id="txt_days_use" name="txt_days_use" data-value="{{ old('txt_days_use', $days_use) }}" class="form-control input-custom-2 days_use @error('days_use') is-invalid @enderror" 
                                    value="{{ $days_use }}" autocomplete="off"
                                    @if(isset($daysUsedReadFlg)) readonly @endif>
                                    <input type="hidden" name="days_use" value="{{ old('days_use', $days_use) }}">
                                &nbsp;&nbsp;
                                <span>{{ __('label.leave.caption_days') }}</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="text" id="txt_times_use" data-value="{{ $times_use }}" class="form-control input-custom-2 times_use"
                                    value="{{ $times_use }}" autocomplete="off" max-number="2" max-value="24"
                                    @if(isset($daysUsedReadFlg)) readonly @endif>
                                    <input type="hidden" name="times_use" value="{{ $times_use }}">
                                &nbsp;&nbsp;
                                <span>{{ __('label.leave.caption_hours') }}</span>
                                @error('days_use')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <span class="col-md-2">{{ __('label.leave.caption_remaining') }}</span>
                            <div class="col-md-10">
                                <input type="text" class="form-control input-custom-2" id="remaining_days"
                                    name="remaining_days" value="{{ $applicant->leave_remaining_days }}" readonly>
                                &nbsp;&nbsp;
                                <span>{{ __('label.leave.caption_days') }}</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="text" class="form-control input-custom-2" id="remaining_hours"
                                    name="remaining_hours" value="{{ $applicant->leave_remaining_time }}" readonly>
                                &nbsp;&nbsp;
                                <span>{{ __('label.leave.caption_hours') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="line-bottom">
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label for="myfile">{{ __('label.leave.caption_file_path') }}</label>
                    </div>
                    <div class="col-sm-5">
                        @if($previewFlg)
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
                                    {{ basename($file_path) }}
                                    <a href="{{ Storage::url($file_path) }}" class="d-none" target="_blank">
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
                <hr class="line-bottom">
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label style="color: red;">{{ __('label.leave.caption_subsequent') }}</label>
                    </div>
                    <div class="col-sm-10">
                        <div class="form-check">
                            <input type="checkbox" id="cb_subsequent" name="cb_subsequent" class="form-check-input"
                                @if($subsequent !==null && $subsequent == config('const.check.on')) checked @endif
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
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <div class="text-center">
            @if (!$previewFlg)
                <button type="button" name="apply" value="apply" class="btn bg-gradient-success btn-form"
                    data-toggle="modal" data-target="#popup-confirm" @if(Common::detectMobile()) disabled @endif>
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
{{-- confirming popup --}}
{{-- <x-popup-confirm/> --}}

@endsection