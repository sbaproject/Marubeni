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
@endsection

@section('content')
@php
    $code_leave     = Session::has('inputs') ? Session::get('inputs')['code_leave']     :  (isset($model) ? $model->code_leave : null);
    $paid_type      = Session::has('inputs') ? Session::get('inputs')['paid_type']      :  (isset($model) ? $model->paid_type : null);
    $reason_leave   = Session::has('inputs') ? Session::get('inputs')['reason_leave']   :  (isset($model) ? $model->reason_leave : null);
    $date_from      = Session::has('inputs') ? Session::get('inputs')['date_from']      :  (isset($model) ? $model->date_from : null);
    $date_to        = Session::has('inputs') ? Session::get('inputs')['date_to']        :  (isset($model) ? $model->date_to : null);
    $time_day       = Session::has('inputs') ? Session::get('inputs')['time_day']       :  (isset($model) ? $model->time_day : null);
    $time_from      = Session::has('inputs') ? Session::get('inputs')['time_from']      :  (isset($model) ? $model->time_from : null);
    $time_to        = Session::has('inputs') ? Session::get('inputs')['time_to']        :  (isset($model) ? $model->time_to : null);
    $maternity_from = Session::has('inputs') ? Session::get('inputs')['maternity_from'] :  (isset($model) ? $model->maternity_from : null);
    $maternity_to   = Session::has('inputs') ? Session::get('inputs')['maternity_to']   :  (isset($model) ? $model->maternity_to : null);
    $file_path      = Session::has('inputs') ? Session::get('inputs')['file_path']      :  (isset($model) ? $model->file_path : null);
    $days_use       = Session::has('inputs') ? Session::get('inputs')['days_use']       :  (isset($model) ? $model->days_use : null);
    $times_use      = Session::has('inputs') ? Session::get('inputs')['times_use']      :  (isset($model) ? $model->times_use : null);
@endphp
<section class="content leave-application">
    <x-alert />
    <form method="POST" action="@if (isset($id)) {{ route('user.leave.update', $id) }} @else {{ route('user.leave.store') }} @endif"
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
                        <select name="code_leave" id="code_leave" style="width: auto;"
                            class="form-control @error('code_leave') is-invalid @enderror">
                            <option value="empty" @if($code_leave == 'empty') selected @endif>
                                {{ __('label.select') }}
                            </option>
                            @foreach ($codeLeaves as $key => $value)
                            <option value="{{ $value }}" @if($code_leave !== null && strval($code_leave) === strval($value)) selected @endif>
                                {{ $key }} : {{ __('label.leave.code_leave.'.$key) }}
                            </option>
                            @endforeach
                        </select>
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
                            style="width: 100%;"
                            placeholder="{{ __('label.leave.caption.reason_leave') }}">{{ $reason_leave }}</textarea>
                    </div>
                </div>
                <hr class="line-bottom">
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>{{ __('label.leave.caption.paid_type') }}</label>
                    </div>
                    <div class="col-sm-10">
                        <fieldset class="@error('paid_type') form-control is-invalid @enderror">
                            @foreach ($paidTypes as $key => $value)
                            <label class="radio-inline">
                                <input type="radio" name="paid_type" value="{{ $value }}"
                                    @if ($paid_type !==null && $paid_type == $value) checked @endif>
                                {{ __('label.leave.paid_type.'.$key) }}
                            </label>
                            @endforeach
                        </fieldset>
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
                                            data-target="#dateLeaveFrom" />
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
                                            data-target="#dateLeaveTo" />
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
                                                    data-target="#timeLeaveDate" />
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
                                            value="{{ $time_from }}"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <span class="col-md-2">{{ __('label.to') }}</span>
                                    <div class="col-md-10">
                                        <input type="text" id="timeLeaveTo" name="time_to"
                                            class="form-control datetimepicker-input" data-toggle="datetimepicker"
                                            data-target="#timeLeaveTo" autocomplete="off"
                                            value="{{ $time_to }}"/>
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
                                            data-target="#maternityLeaveFrom" />
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
                                            data-target="#maternityLeaveTo" />
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
                                    value="{{ $user->leave_days }}" readonly>
                                &nbsp;&nbsp;
                                <span>{{ __('label.leave.caption.days') }}</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <span class="col-sm-2">{{ __('label.leave.caption.used_this_year') }}</span>
                            <div class="col-sm-10">
                                <input type="text" class="form-control input-custom-2" id="used_days" name="used_days" 
                                    value="{{ $user->leave_days - $user->leave_remaining_days }}" readonly>
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
                                <input type="number" id="days_use" name="days_use" class="form-control input-custom-2" 
                                    value="{{ $days_use }}" autocomplete="off">
                                &nbsp;&nbsp;
                                <span>{{ __('label.leave.caption.days') }}</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="number" id="times_use" name="times_use" class="form-control input-custom-2"
                                    value="{{ $times_use }}" autocomplete="off">
                                &nbsp;&nbsp;
                                <span>{{ __('label.leave.caption.hours') }}</span>
                            </div>
                        </div>
                        <div class="row">
                            <span class="col-sm-2">{{ __('label.leave.caption.remaining') }}</span>
                            <div class="col-sm-10">
                                <input type="text" class="form-control input-custom-2" id="remaining_days"
                                    name="remaining_days" value="{{ $user->leave_remaining_days }}" readonly>
                                &nbsp;&nbsp;
                                <span>{{ __('label.leave.caption.days') }}</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="text" class="form-control input-custom-2" id="remaining_hours"
                                    name="remaining_hours" value="{{ $user->leave_remaining_time }}" readonly>
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
                        {{-- for edit --}}
                        @if(isset($id) && !empty($file_path))
                            @if ($model->file_path === $file_path)
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
                                            if((isset($id) && !empty($file_path))){
                                                if($model->file_path === $file_path){
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
                            <input type="file" id="input_file" name="input_file"/>
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
                    </div>
                </div>
                <hr class="line-bottom">
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label style="color: red;">{{ __('label.leave.caption.subsequent') }}</label>
                    </div>
                    <div class="col-sm-10">
                        <div class="form-check">
                            <input type="checkbox" id="subsequent" name="subsequent" class="form-check-input" 
                            @if (old('subsequent') !=null) checked @endif>
                            <label class="form-check-label" for="subsequent">{{ __('label.on') }}</label>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
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
        <br>
        <br>
    </form>
</section>
@endsection