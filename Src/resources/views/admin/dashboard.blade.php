@extends('layouts.master')
@section('title', 'ADMIN_DASHBOARD')
@section('css')
    <link rel="stylesheet" href="css/user/01_dashboard.css">
@endsection
@section('js')
    <!-- moment -->
    <script src="js/moment/moment.min.js"></script>
    <!-- moment locale-->
    <script src="js/moment/locale/{{ config('app.locale') }}.js"></script>
    <!-- DateTime Bootstrap 4 -->
    <script src="js/bootstrap-datetimepicker.js"></script>
    <script src="js/admin/dashboard.js"></script>
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

                <div class="col-md-2 col-sm-2">
                    <div class="btn-search">
                        <button class="btn btn-default sty-search" type="submit"><i class="fa fa-search"
                                style="margin-right:5px;"></i>{{ __('label.button.search') }}</button>
                    </div>
                </div>
        </form>
    </section>

    <!-- Main content -->
    <section class="content-dashboard">
        <div class="row" style="text-align: center;">
            <div class="col-xs-5ths">
                <a href="{{ route('admin.dashboard',config('const.application.status.applying'))}}">
                    <div class="card">
                        <div class="card-body card-wrap-items">
                            <span>{{ __('label.dashboard.applying') }}</span>
                            <span class="right-number">({{ $count_applying }})</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xs-5ths col-set">
                <a href="{{ route('admin.dashboard',config('const.application.status.approvel'))}}">
                    <div class="card">
                        <div class="card-body card-wrap-items">
                            <span>{{ __('label.dashboard.approval') }}</span>
                            <span class="right-number">({{ $count_approval }})</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xs-5ths col-set">
                <a href="{{ route('admin.dashboard',config('const.application.status.declined'))}}">
                    <div class="card">
                        <div class="card-body card-wrap-items">
                            <span>{{ __('label.dashboard.declined') }}</span>
                            <span class="right-number">({{ $count_declined }})</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xs-5ths col-set">
                <a href="{{ route('admin.dashboard',config('const.application.status.reject'))}}">
                    <div class="card">
                        <div class="card-body card-wrap-items">
                            <span>{{ __('label.dashboard.reject') }}</span>
                            <span class="right-number">({{ $count_reject }})</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xs-5ths col-set">
                <a href="{{ route('admin.dashboard',config('const.application.status.completed'))}}">
                    <div class="card">
                        <div class="card-body card-wrap-items">
                            <span>{{ __('label.dashboard.completed') }}</span>
                            <span class="right-number">({{ $count_completed }})</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <h4 class="mb-2" style="border-bottom: 1px solid #000;font-weight: bold;margin-top: 25px;"><i
                class="nav-icon fas fa-file-alt" aria-hidden="true"
                style="margin-right: 5px;margin-bottom: 5px;"></i>{{ $intstatus == config('const.application.status.applying') ? __('label.status.list_of_applying_documents') : ($intstatus == config('const.application.status.approvel') ? __('label.status.list_of_approval_documents') : ($intstatus == config('const.application.status.declined') ? __('label.status.list_of_declined_documents') : ($intstatus == config('const.application.status.reject') ? __('label.status.list_of_reject_documents') : ($intstatus == config('const.application.status.completed') ? __('label.status.list_of_completed_documents') : __('label.dashboard.list_application'))))) }}</h4>
        <div class="card">
            <div class="card-body p-0 card-list-items">
                <table class="table" id="table_list_status">
                    <thead>
                        <tr class="list-title">
                            <th>{{ __('label.dashboard.application_no') }}</th>
                            <th>{{ __('label.dashboard.application_name') }}</th>
                            <th>{{ __('label.dashboard.status') }}</th>
                            <th>{{ __('label.dashboard.apply_date') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($list_application))
                            @foreach ($list_application as $application)
                                 <tr class="list-content">
                                    <td>{{ !empty($application->application_no) ? $application->application_no : '' }}</td>
                                    <td>{{ !empty($application->form_id) ? $application->Form->name : '' }}</td>
                                    <td>
                                        <div
                                            class=" {{ $application->status == config('const.application.status.applying') ? 'status-apply' : ($application->status == config('const.application.status.declined') ? 'status-declined' : ($application->status == config('const.application.status.reject') ? 'status-reject' : ($application->status == config('const.application.status.completed') ? 'status-completed' : 'status-approval'))) }}">
                                            {{ $application->status == config('const.application.status.applying') ? __('label.dashboard.applying') : ($application->status == config('const.application.status.declined') ? __('label.dashboard.declined') : ($application->status == config('const.application.status.reject') ? __('label.dashboard.reject') : ($application->status == config('const.application.status.completed') ? __('label.dashboard.completed') : __('label.dashboard.approval')))) }}
                                        </div>
                                    </td>
                                    <td>{{ !empty($application->created_at) ? \Carbon\Carbon::parse($application->created_at)->format('d/m/Y') : '' }}
                                    </td>
                                    <td>
                                        <a class="btn btn-details" href="{{ $application->form_id == 1 ? route('user.leave.show', $application->id) : ($application->form_id == 2 ? route('user.business.show', $application->id) : ($application->form_id == 1 ? '' : '')) }}">{{ __('label.dashboard.view_details') }}<i class="fas fa-angle-right" style="margin-left: 5px;"></i></a>
                                    </td>
                                    </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        @if (isset($list_application))
            {{ $list_application->withQueryString()->links('paginator') }}
        @endif
        <div id='str_date' value='{{ $str_date }}'>
        </div>
    </section>
@endsection
