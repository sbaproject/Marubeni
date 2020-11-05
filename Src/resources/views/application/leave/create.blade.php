@extends('layouts.master')

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
    <form method="POST" action="{{ route('user.leave.store') }}">
        @csrf
        <div class="main-top">
            <h4 class="main-header-text">LEAVE APPLICATION</h4>
            <a class="btn btn-outline-dark" role="button" href="#"><i class="fas fa-external-link-alt"
                    style="margin-right: 5px; color: #fff;"></i>Export</a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>Code of Leave</label>
                    </div>
                    <div class="col-sm-10">
                        <select name="code_leave" id="code_leave" class="form-control" style="width: auto;">
                            <option value=''>{{ __('label.select') }}</option>
                            @foreach ($codeLeaves as $key => $value)
                            <option value="{{ $value }}" {{ old('code_leave') == $value ? 'selected' : '' }}>
                                {{ __('label.code_leave.'.$key) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <hr class="line-bottom">
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>Reason for Leave</label>
                    </div>
                    <div class="col-sm-10">
                        <textarea class="form-control" name="reason_leave" id="reason_leave" rows="3"
                            placeholder="Some text" style="width: 100%;"></textarea>
                    </div>
                </div>
                <hr class="line-bottom">
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>Choose the type of leave switch</label>
                    </div>
                    <div class="col-sm-10">
                        {{-- <fieldset class="form-control"> --}}
                            @foreach ($paidTypes as $key => $value)
                            <div class="form-check form-check-inline">
                                <input type="radio" id="paid_type_{{$key}}" name="paid_type" class="form-check-input"
                                    value="{{ $value }}">
                                <label for="paid_type_{{$key}}" class="form-check-label" style="margin-left: 5px;">
                                    {{ __('label.paid_type.'.$key) }}
                                </label>
                            </div>
                            @endforeach
                        {{-- </fieldset> --}}
                    </div>
                </div>
                <hr class="line-bottom">
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>Date of Leave</label>
                    </div>
                    <div class="col-sm-10">
                        <div class="row mb-2">
                            <span class="col-md-2">From</span>
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
                                    <input type="hidden" id="date_from" name="date_from">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <span class="col-md-2">To</span>
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
                                    <input type="hidden" id="date_to" name="date_to">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="line-bottom">
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>Time of Leave</label>
                    </div>
                    <div class="col-sm-10">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="row">
                                    <span class="col-md-4">Date</span>
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
                                            <input type="hidden" id="time_day" name="time_day">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <div class="row mb-2">
                                    <span class="col-md-2">From</span>
                                    <div class="col-md-10">
                                        <input type="text" name="time_from" class="form-control datetimepicker-input"
                                            id="timeLeaveFrom" data-toggle="datetimepicker" data-target="#timeLeaveFrom"
                                            autocomplete="off" />
                                    </div>
                                </div>
                                <div class="row">
                                    <span class="col-md-2">To</span>
                                    <div class="col-md-10">
                                        <input type="text" name="time_to" class="form-control datetimepicker-input"
                                            id="timeLeaveTo" data-toggle="datetimepicker" data-target="#timeLeaveTo"
                                            autocomplete="off" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="line-bottom">
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>Maternity of Leave</label>
                    </div>
                    <div class="col-sm-10">

                        <div class="row mb-2">
                            <span class="col-md-2">From</span>
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
                                    <input type="hidden" id="maternity_from" name="maternity_from">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <span class="col-md-2">To</span>
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
                                    <input type="hidden" id="maternity_to" name="maternity_to">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <hr class="line-bottom">
                <div class="form-group row">
                    <div class="col-sm-2 text-left">
                        <label>Annual Leave</label>
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
                        <label style="color: red;">Subsequent</label>
                    </div>
                    <div class="col-sm-10">
                        <div class="form-check">
                            <input type="checkbox" id="subsequent" name="subsequent" class="form-check-input">
                            <label class="form-check-label" for="subsequent">ON</label>
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
                Apply
            </button>
            <button type="submit" name="draft" value="draft" class="btn btn-draft btn-custom">
                <i class="nav-icon fas fa-edit" style="margin-right: 5px;"></i>
                Draft
            </button>
            <button type="button" class="btn btn-cancel btn-custom">
                <i class="fa fa-ban" aria-hidden="true" style="margin-right: 5px;"></i>
                Cancel
            </button>
        </div>
        <br>
        <br>
    </form>
</section>
@endsection