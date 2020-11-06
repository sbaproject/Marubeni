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
<script src="js/bootstrap-datetimepicker.js"></script>
{{-- for this view --}}
<script src="js/user/application/leave/create.js"></script>
@endsection

@section('content')
<section class="content leave-application">
    <x-alert />
    <form method="POST" action="@if (isset($id)) {{ route('user.leave.update', $id) }} @else {{ route('user.leave.store') }} @endif"
        enctype="multipart/form-data">
        @csrf
        <div class="main-top">
            <h4 class="main-header-text">{{ Str::upper(__('label.leave_application')) }}</h4>
            <a class="btn btn-outline-dark" role="button" href="#">
                <i class="fas fa-external-link-alt" style="margin-right: 5px; color: #fff;"></i>
                Export
            </a>
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
                            <option value='' selected>{{ __('label.select') }}</option>
                            @foreach ($codeLeaves as $key => $value)
                            <option value="{{ $value }}" @if (old('code_leave') !==null && old('code_leave')==$value)
                                selected @endif>
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
                            placeholder="{{ __('label.leave.caption.reason_leave') }}">{{ old('reason_leave') }}</textarea>
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
                                <input type="radio" name="paid_type" value="{{ $value }}" @if (old('paid_type') !==null
                                    && old('paid_type')==$value) checked @endif>
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
                                    <input type="hidden" id="date_from" name="date_from" value="{{ old('date_from') }}">
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
                                    <input type="hidden" id="date_to" name="date_to" value="{{ old('date_to') }}">
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
                                                value="{{ old('time_day') }}">
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
                                            value="{{ old('time_from') }}"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <span class="col-md-2">{{ __('label.to') }}</span>
                                    <div class="col-md-10">
                                        <input type="text" id="timeLeaveTo" name="time_to"
                                            class="form-control datetimepicker-input" data-toggle="datetimepicker"
                                            data-target="#timeLeaveTo" autocomplete="off"
                                            value="{{ old('time_to') }}"/>
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
                                        value="{{ old('maternity_from') }}">
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
                                        value="{{ old('maternity_to') }}">
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
                            <span class="col-sm-2">Entitled this year</span>
                            <div class="col-sm-10">
                                <input type="text" class="form-control input-custom-2" id="entitled" name="entitled"
                                    value="30">
                                &nbsp;&nbsp;
                                <span>Days</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="text" style="display: none;" class="form-control input-custom-2"
                                    id="usedHours" name="usedHours" value="30">
                                &nbsp;&nbsp;
                                <span></span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <span class="col-sm-2">Used this year</span>
                            <div class="col-sm-10">
                                <input type="text" class="form-control input-custom-2" id="usedDays" name="usedDays"
                                    value="30">
                                &nbsp;&nbsp;
                                <span>Days</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="text" class="form-control input-custom-2" id="usedHours" name="usedHours"
                                    value="30">
                                &nbsp;&nbsp;
                                <span>Hours</span>

                            </div>
                        </div>
                        <div class="row mb-2">
                            <span class="col-sm-2">Take this year</span>
                            <div class="col-sm-10">
                                <input type="text" class="form-control input-custom-2" id="takeDays" name="takeDays"
                                    value="30">
                                &nbsp;&nbsp;
                                <span>Days</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="text" class="form-control input-custom-2" id="takeHours" name="takeHours"
                                    value="30">
                                &nbsp;&nbsp;
                                <span>Hours</span>
                            </div>
                        </div>
                        <div class="row">
                            <span class="col-sm-2">Remaining</span>
                            <div class="col-sm-10">
                                <input type="text" class="form-control input-custom-2" id="remainingDays"
                                    name="remainingDays" value="30">
                                &nbsp;&nbsp;
                                <span>Days</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="text" class="form-control input-custom-2" id="remainingHours"
                                    name="remainingHours" value="30">
                                &nbsp;&nbsp;
                                <span>Hours</span>
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
                        <div class="custom-file">
                            <input type="file" id="file_path" name="file_path"
                                class="custom-file-input form-control @error('file_path') is-invalid @enderror">
                            <label class="custom-file-label" for="file_path"></label>
                        </div>
                        @error('file_path')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
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