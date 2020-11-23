@extends('layouts.master')

@section('title')
{{ __('label.leave_application') }}
@endsection

@section('css')
{{-- for this view --}}
<link rel="stylesheet" href="css/user/04_leave_application.css">
@endsection

@section('js')
{{-- datetimepicker --}}
<script src="js/moment/moment.min.js"></script>
<script src="js/moment/locale/{{ config('app.locale') }}.js"></script>
<script src="js/bootstrap-datetimepicker.js"></script>
{{-- for this view --}}
<script src="js/user/application/leave/create.js"></script>
<script>
    const code_leave = @json(config('const.code_leave'));
    const paid_type = @json(config('const.paid_type'));
    const previewFlg = @json(isset($previewFlg) ? true : false);
</script>
@endsection

@section('content')
@php
    $code_leave     = Session::has('inputs') ? Session::get('inputs')['code_leave']     :  (isset($application) ? $application->leave->code_leave : null);
    $paid_type      = Session::has('inputs') ? Session::get('inputs')['paid_type']      :  (isset($application) ? $application->leave->paid_type : null);
    $reason_leave   = Session::has('inputs') ? Session::get('inputs')['reason_leave']   :  (isset($application) ? $application->leave->reason_leave : null);
    $date_from      = Session::has('inputs') ? Session::get('inputs')['date_from']      :  (isset($application) ? $application->leave->date_from : null);
    $date_to        = Session::has('inputs') ? Session::get('inputs')['date_to']        :  (isset($application) ? $application->leave->date_to : null);
    $time_day       = Session::has('inputs') ? Session::get('inputs')['time_day']       :  (isset($application) ? $application->leave->time_day : null);
    $time_from      = Session::has('inputs') ? Session::get('inputs')['time_from']      :  (isset($application) ? $application->leave->time_from : null);
    $time_to        = Session::has('inputs') ? Session::get('inputs')['time_to']        :  (isset($application) ? $application->leave->time_to : null);
    $maternity_from = Session::has('inputs') ? Session::get('inputs')['maternity_from'] :  (isset($application) ? $application->leave->maternity_from : null);
    $maternity_to   = Session::has('inputs') ? Session::get('inputs')['maternity_to']   :  (isset($application) ? $application->leave->maternity_to : null);
    $days_use       = Session::has('inputs') ? Session::get('inputs')['days_use']       :  (isset($application) ? $application->leave->days_use : null);
    $times_use      = Session::has('inputs') ? Session::get('inputs')['times_use']      :  (isset($application) ? $application->leave->times_use : null);
    $file_path      = Session::has('inputs') ? Session::get('inputs')['file_path']      :  (isset($application) ? $application->file_path : null);
    $applicant      = isset($application) ? $application->applicant : Auth::user();

    if(isset($application)){
        if(isset($previewFlg)){
            $actionUrl = route('user.leave.preview.pdf', $application->id);
        } else {
            $actionUrl = route('user.leave.update', $application->id);
        }
    } else {
        $actionUrl = route('user.leave.store');
    }
@endphp
<section class="content leave-application">
    <x-alert />
    <form method="POST" action="{{ $actionUrl }}"
        enctype="multipart/form-data">
        @csrf
        <div class="main-top">
            <h4 class="main-header-text">{{ Str::upper(__('label.form.leave')) }}</h4>
            <button type="submit" name="pdf" value="pdf" class="btn btn-outline-dark" href="#">
                <i class="fas fa-external-link-alt" style="margin-right: 5px; color: #fff;"></i>
                {{ __('label.button.export') }}
            </button>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>{{ __('label.leave.caption.code_leave') }}</label>
                    </div>
                    <div class="col-sm-10">
                        <select name="rd_code_leave" style="width: auto;"
                            class="form-control @error('code_leave') is-invalid @enderror"
                            @if(isset($previewFlg)) disabled @endif>
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
                        <label>{{ __('label.leave.caption.reason_leave') }}</label>
                    </div>
                    <div class="col-sm-10">
                        <textarea class="form-control" name="reason_leave" id="reason_leave" rows="3"
                            style="width: 100%;" @if(isset($previewFlg)) readonly @endif
                            placeholder="{{ __('label.leave.caption.reason_leave') }}">{{ $reason_leave }}</textarea>
                    </div>
                </div>
                <hr class="line-bottom">
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>{{ __('label.leave.caption.paid_type') }}</label>
                    </div>
                    <div class="col-sm-10">
                        @php
                            if(isset($previewFlg)
                                || $code_leave != config('const.code_leave.SL')
                                || ($code_leave == config('const.code_leave.SL') && $paid_type != config('const.paid_type.AL'))){
                                $paidTypeReadFlg = true;
                            }
                        @endphp
                        <fieldset class="@error('paid_type') form-control is-invalid @enderror">
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
                        <label>{{ __('label.leave.caption.date_leave') }}</label>
                    </div>
                    <div class="col-sm-10">
                        <div class="row mb-2">
                            <span class="col-md-2">{{ __('label.from') }}</span>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="input-group date" id="dateLeaveFrom" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input"
                                            data-target="#dateLeaveFrom"
                                            @if(isset($previewFlg) || $code_leave == config('const.code_leave.ML')) readonly @endif/>
                                        <div class="input-group-addon input-group-append" data-target="#dateLeaveFrom"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="date_from" name="date_from" value="{{ $date_from }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <span class="col-md-2">{{ __('label.to') }}</span>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="input-group date" id="dateLeaveTo" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input"
                                            data-target="#dateLeaveTo"
                                            @if(isset($previewFlg) || $code_leave == config('const.code_leave.ML')) readonly @endif/>
                                        <div class="input-group-addon input-group-append" data-target="#dateLeaveTo"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="date_to" name="date_to" value="{{ $date_to }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="line-bottom">
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>{{ __('label.leave.caption.time_leave') }}</label>
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
                                                    @if(isset($previewFlg) || $code_leave == config('const.code_leave.ML')) readonly @endif/>
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
                                    <span class="col-md-2">{{ __('label.from') }}</span>
                                    <div class="col-md-10">
                                        <input type="text" id="timeLeaveFrom" name="time_from"
                                            class="form-control datetimepicker-input" data-toggle="datetimepicker"
                                            data-target="#timeLeaveFrom" autocomplete="off"
                                            value="{{ $time_from }}"
                                            @if(isset($previewFlg) || $code_leave == config('const.code_leave.ML')) readonly @endif/>
                                    </div>
                                </div>
                                <div class="row">
                                    <span class="col-md-2">{{ __('label.to') }}</span>
                                    <div class="col-md-10">
                                        <input type="text" id="timeLeaveTo" name="time_to"
                                            class="form-control datetimepicker-input" data-toggle="datetimepicker"
                                            data-target="#timeLeaveTo" autocomplete="off"
                                            value="{{ $time_to }}"
                                            @if(isset($previewFlg) || $code_leave == config('const.code_leave.ML')) readonly @endif/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="line-bottom">
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>{{ __('label.leave.caption.maternity_leave') }}</label>
                    </div>
                    <div class="col-sm-10">
                        <div class="row mb-2">
                            <span class="col-md-2">{{ __('label.from') }}</span>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="input-group date" id="maternityLeaveFrom" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input"
                                            data-target="#maternityLeaveFrom"
                                            @if(isset($previewFlg) || $code_leave != config('const.code_leave.ML')) readonly @endif/>
                                        <div class="input-group-addon input-group-append"
                                            data-target="#maternityLeaveFrom" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                    </div>
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
                                        <input type="text" class="form-control datetimepicker-input"
                                            data-target="#maternityLeaveTo"
                                            @if(isset($previewFlg) || $code_leave != config('const.code_leave.ML')) readonly @endif/>
                                        <div class="input-group-addon input-group-append"
                                            data-target="#maternityLeaveTo" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                    </div>
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
                        <label>{{ __('label.leave.caption.annual_leave') }}</label>
                    </div>
                    <div class="col-sm-10">
                        <div class="row mb-2">
                            <span class="col-sm-2">{{ __('label.leave.caption.entitled_year') }}</span>
                            <div class="col-sm-10">
                                <input type="text" id="entitled" name="entitled_days" class="form-control input-custom-2"
                                    value="{{ $applicant->leave_days }}" readonly>
                                &nbsp;&nbsp;
                                <span>{{ __('label.leave.caption.days') }}</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <span class="col-sm-2">{{ __('label.leave.caption.used_this_year') }}</span>
                            <div class="col-sm-10">
                                <input type="text" class="form-control input-custom-2" id="used_days" name="used_days" 
                                    value="{{ $applicant->leave_days - $applicant->leave_remaining_days }}" readonly>
                                &nbsp;&nbsp;
                                <span>{{ __('label.leave.caption.days') }}</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="text" class="form-control input-custom-2" id="used_time" name="used_hours" 
                                    value="" readonly>
                                &nbsp;&nbsp;
                                <span>{{ __('label.leave.caption.hours') }}</span>

                            </div>
                        </div>
                        <div class="row mb-2">
                            <span class="col-sm-2">{{ __('label.leave.caption.take_this_time') }}</span>
                            <div class="col-sm-10">
                                @php
                                    if(isset($previewFlg)
                                        || $code_leave == config('const.code_leave.ML')
                                        || ($code_leave == config('const.code_leave.SL') && $paid_type != config('const.paid_type.AL'))) {
                                            $daysUsedReadFlg = true;
                                        }
                                @endphp
                                <input type="number" id="days_use" name="days_use" class="form-control input-custom-2" 
                                    value="{{ $days_use }}" autocomplete="off"
                                    @if(isset($daysUsedReadFlg)) readonly @endif>
                                &nbsp;&nbsp;
                                <span>{{ __('label.leave.caption.days') }}</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="number" id="times_use" name="times_use" class="form-control input-custom-2"
                                    value="{{ $times_use }}" autocomplete="off"
                                    @if(isset($daysUsedReadFlg)) readonly @endif>
                                &nbsp;&nbsp;
                                <span>{{ __('label.leave.caption.hours') }}</span>
                            </div>
                        </div>
                        <div class="row">
                            <span class="col-sm-2">{{ __('label.leave.caption.remaining') }}</span>
                            <div class="col-sm-10">
                                <input type="text" class="form-control input-custom-2" id="remaining_days"
                                    name="remaining_days" value="{{ $applicant->leave_remaining_days }}" readonly>
                                &nbsp;&nbsp;
                                <span>{{ __('label.leave.caption.days') }}</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="text" class="form-control input-custom-2" id="remaining_hours"
                                    name="remaining_hours" value="{{ $applicant->leave_remaining_time }}" readonly>
                                &nbsp;&nbsp;
                                <span>{{ __('label.leave.caption.hours') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="line-bottom">
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
                @if (!isset($previewFlg))
                <hr class="line-bottom">
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label style="color: red;">{{ __('label.leave.caption.subsequent') }}</label>
                    </div>
                    <div class="col-sm-10">
                        <div class="form-check">
                            <input type="checkbox" id="subsequent" name="subsequent" class="form-check-input" @if (old('subsequent')
                                !=null) checked @endif>
                            <label class="form-check-label" for="subsequent">{{ __('label.on') }}</label>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <!-- /.card-body -->
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