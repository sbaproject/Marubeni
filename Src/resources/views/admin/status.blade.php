@extends('layouts.master')
@section('title')
    {{ $intstatus == config('const.application.status.applying') ? __('label.status.list_of_applying_documents') : ($intstatus == config('const.application.status.approvel_un') ? __('label.status.list_of_approval_un_documents') : ($intstatus == config('const.application.status.approvel_in') ? __('label.status.list_of_approval_in_documents') : ($intstatus == config('const.application.status.declined') ? __('label.status.list_of_declined_documents') : ($intstatus == config('const.application.status.reject') ? __('label.status.list_of_reject_documents') : ($intstatus == config('const.application.status.completed') ? __('label.status.list_of_completed_documents') : ''))))) }}
@endsection
@section('css')
    <link rel="stylesheet" href="css/user/02_status.css">
@endsection
@section('js')
    <!-- moment -->
    <script src="js/moment/moment.min.js"></script>
    <!-- moment locale-->
    <script src="js/moment/locale/{{ config('app.locale') }}.js"></script>
    <!-- DateTime Bootstrap 4 -->
    <script src="js/bootstrap-datetimepicker.js"></script>
    <script src="js/user/status.js"></script>
@endsection
@section('content')
    <section class="content-header">
        <form method="get" id="formSearch" action="">
            @csrf
            <div class="row">
                <div class="col-md-4 col-sm-4">
                    <label class="lbl-from">{{ __('label.date_from') }}</label>
                    <div class="form-group">
                        <div class="input-group date" id="dateFrom" data-target-input="nearest">
                            <div class="input-group-addon input-group-append" data-target="#dateFrom"
                                data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                            </div>
                            <input type="text" class="form-control datetimepicker-input" data-target="#dateFrom" />
                        </div>
                        <input type="hidden" id="dataDateFrom" name="dataDateFrom">
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <label class="lbl-to">{{ __('label.date_to') }}</label>
                    <div class="form-group">
                        <div class="input-group date" id="dateTo" data-target-input="nearest">
                            <div class="input-group-addon input-group-append" data-target="#dateTo"
                                data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                            </div>
                            <input type="text" class="form-control datetimepicker-input" data-target="#dateTo" />
                        </div>
                        <input type="hidden" id="dataDateTo" name="dataDateTo">
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-3 col-sm-3">
                    <div class="btn-search">
                        <button class="btn btn-default sty-search" type="submit"><i class="fa fa-search"
                                style="margin-right:5px;"></i>{{ __('label.button.search') }}</button>
                    </div>
                </div>
        </form>
    </section>

    <!-- Main content -->
    <section class="content-status">
        <h4 class="mb-2" style="border-bottom: 1px solid #000;font-weight: bold;"><i class="nav-icon fas fa-file-alt"
                aria-hidden="true"
                style="margin-right: 5px;margin-bottom: 5px;"></i>{{ $intstatus == config('const.application.status.applying') ? __('label.status.list_of_applying_documents') : ($intstatus == config('const.application.status.approvel_un') ? __('label.status.list_of_approval_un_documents') : ($intstatus == config('const.application.status.approvel_in') ? __('label.status.list_of_approval_in_documents') : ($intstatus == config('const.application.status.declined') ? __('label.status.list_of_declined_documents') : ($intstatus == config('const.application.status.reject') ? __('label.status.list_of_reject_documents') : ($intstatus == config('const.application.status.completed') ? __('label.status.list_of_completed_documents') : ''))))) }}
        </h4>
        <div class="card">
            <div class="card-body p-0 card-list-items">
                <table class="table">
                    <thead>
                        <tr class="list-title">
                            <th>{{ __('label.status.no') }}</th>
                            <th>{{ __('label.status.application_type') }}</th>
                            <th>{{ __('label.status.apply_date') }}</th>
                            <th>{{ __('label.status.next_approver') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($list_applications_status))
                            @foreach ($list_applications_status as $application_status)
                                <tr class="list-content">
                                    <td>{{ !empty($application_status->application_no) ? $application_status->application_no : '' }}
                                    </td>
                                    <td>{{ !empty($application_status->nameapp) ? $application_status->nameapp : '' }}</td>
                                    <td>{{ !empty($application_status->datecreate) ? \Carbon\Carbon::parse($application_status->datecreate)->format('d/m/Y') : '' }}
                                    </td>
                                    <td>{{ !empty($application_status->nameuser) ? $application_status->nameuser : '' }}
                                    </td>
                                    <td>
                                        <a class="btn btn-details"
                                            href="{{ $application_status->form_id == config('const.form.leave') ? route('user.leave.show', $application_status->id) : ($application_status->form_id == config('const.form.biz_trip') ? route('user.business.show', $application_status->id) : ($application_status->form_id == config('const.form.entertaiment') ? route('user.entertainment.show', $application_status->id) : '')) }}">{{ __('label.status.view_details') }}<i
                                                class="fas fa-angle-right" style="margin-left: 5px;"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        @if (isset($list_applications_status))
            {{ $list_applications_status->withQueryString()->links('paginator') }}
        @endif
        <div id='str_date' value='{{ $str_date }}'>
        </div>
        <div id='end_date' value='{{ $end_date }}'>
        </div>
    </section>
@endsection
