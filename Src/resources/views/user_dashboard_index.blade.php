@extends('layouts.master')
@section('title')
    {{ $intstatus == config('const.application.status.applying') ? __('label.status_list_of_applying_documents') : ($intstatus == config('const.application.status.approvel') ? __('label.status_list_of_approval_documents') : ($intstatus == config('const.application.status.declined') ? __('label.status_list_of_declined_documents') : ($intstatus == config('const.application.status.reject') ? __('label.status_list_of_reject_documents') : ($intstatus == config('const.application.status.completed') ? __('label.status_list_of_completed_documents') : __('label.dashboard_list_application'))))) }}
@endsection
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
    <script src="js/user/dashboard.js"></script>
@endsection

@section('content-header')
    {{ __('label.menu_dashboard') }}
@endsection

@section('content-breadcrumb')
{{-- <li class="breadcrumb-item active">{{ __('label.menu_dashboard') }}</li> --}}
@endsection

@section('content')
    <form method="get" id="formSearch" action="">
        <section class="invoice content-header">
            @csrf
            <div class="row">
                <div class="col-md-4 col-sm-4">
                    <label class="lbl-from">{{ __('label.date_from') }}</label>
                    <div class="form-group">
                        <div class="input-group date" id="dateFrom" data-target-input="nearest">
                            <div class="input-group-addon input-group-append" data-target="#dateFrom"
                                data-toggle="datetimepicker">
                                <div class="input-group-text btn-dtp-left"><i class="fa fa-calendar-alt"></i></div>
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
                                <div class="input-group-text btn-dtp-left"><i class="fa fa-calendar-alt"></i></div>
                            </div>
                            <input type="text" class="form-control datetimepicker-input" data-target="#dateTo" />
                        </div>
                        <input type="hidden" id="dataDateTo" name="dataDateTo">
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-3 col-sm-3">
                    <div class="btn-search">
                        <button type="submit" class="btn bg-gradient-primary">
                            <i class="fa fa-search" style="margin-right:5px;"></i>{{ __('label.button_search') }}</button>
                    </div>
                </div>
        </section>

        <input type="hidden" id="typeApply" name="typeApply">
        <!-- Main content -->
        <section class="invoice p-3 mb-3 content-dashboard">
            <div class="row" style="text-align: center;">
                <div class="col-xs-5ths">
                    <div class="">
                        <div class="btn btn-block bg-gradient-success btn-status" id="applying">
                            <span>{{ __('label.dashboard_applying') }}</span>
                            <div class="right-number">({{ $count_applying }})</div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-5ths col-set">
                    <div class="">
                        <div class="btn btn-block bg-gradient-danger btn-status" id="approval">
                            <span>{{ __('label.dashboard_approval') }}</span>
                            <div class="right-number">({{ $count_approval }})</div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-5ths col-set">
                    <div class="">
                        <div class="btn btn-block bg-gradient-warning btn-status" id="declined">
                            <span>{{ __('label.dashboard_declined') }}</span>
                            <div class="right-number">({{ $count_declined }})</div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-5ths col-set">
                    <div class="">
                        <div class="btn btn-block bg-gradient-secondary btn-status" id="reject">
                            <span>{{ __('label.dashboard_reject') }}</span>
                            <div class="right-number">({{ $count_reject }})</div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-5ths col-set">
                    <div class="">
                        <div class="btn btn-block bg-gradient-primary btn-status" id="completed">
                            <span>{{ __('label.dashboard_completed') }}</span>
                            <div class="right-number">({{ $count_completed }})</div>
                        </div>
                    </div>
                </div>
            </div>
            <h4 class="mb-2" style="border-bottom: 1px solid #000;font-weight: bold;margin-top: 25px;"><i
                    class="nav-icon fas fa-file-alt" aria-hidden="true"
                    style="margin-right: 5px;margin-bottom: 5px;"></i>{{ $intstatus == config('const.application.status.applying') ? __('label.status_list_of_applying_documents') : ($intstatus == config('const.application.status.approvel') ? __('label.status_list_of_approval_documents') : ($intstatus == config('const.application.status.declined') ? __('label.status_list_of_declined_documents') : ($intstatus == config('const.application.status.reject') ? __('label.status_list_of_reject_documents') : ($intstatus == config('const.application.status.completed') ? __('label.status_list_of_completed_documents') : __('label.dashboard_list_application'))))) }}
            </h4>
            <div class="card">
                <div class="card-body p-0 card-list-items">
                    <table class="table table-bordered table-hover" id="table_list_status">
                        <thead>
                            <tr class="">
                                <th class="sortable {{ $sortable->headers['application_no']->activeCls }}">
                                    {!! $sortable->headers['application_no']->title !!}
                                </th>
                                <th class="sortable {{ $sortable->headers['form_name']->activeCls }}">
                                    {!! $sortable->headers['form_name']->title !!}
                                </th>
                                <th class="sortable {{ $sortable->headers['status']->activeCls }}">
                                    {!! $sortable->headers['status']->title !!}
                                </th>
                                <th class="sortable {{ $sortable->headers['created_at']->activeCls }}">
                                    {!! $sortable->headers['created_at']->title !!}
                                </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($list_application))
                                @foreach ($list_application as $application)
                                    <tr class="">
                                        <td>{{ !empty($application->application_no) ? $application->application_no : '' }}
                                        </td>
                                        <td>{{ !empty($application->form_name) ? $application->form_name : '' }}</td>
                                        <td>
                                            {{-- <div
                                                class="badge {{ ($application->status_css >= 0 and $application->status_css <= 98 and $application->current_step == 1) ? 'badge-success' : ($application->status_css == config('const.application.status.declined') ? 'badge-warning' : ($application->status_css == config('const.application.status.reject') ? 'badge-secondary' : ($application->status_css == config('const.application.status.completed') ? 'badge-primary' : 'badge-danger'))) }}">
                                                {{ !empty($application->status) ? $application->status : '' }}
                                                
                                            </div> --}}
                                            {!! Common::generateStatusApplicationBadgeStyle($application->status_css, $application->current_step) !!}
                                        </td>
                                        <td>{{ !empty($application->created_at) ? \Carbon\Carbon::parse($application->created_at)->format('d/m/Y') : '' }}
                                        </td>
                                        <td>
                                            <a class="btn bg-gradient-info" title="{{ __('label.dashboard_view_details') }}"
                                                href="{{ Common::getRouteEditApplication($application->id, $application->form_id) }}">
                                                <i class="fas fa-search"></i>
                                                {{-- {{ __('label.dashboard_view_details') }} --}}
                                                {{-- <i class="fas fa-angle-right" style="margin-left: 5px;"></i> --}}
                                            </a>
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
            <div id='end_date' value='{{ $end_date }}'>
            </div>
        </section>
    </form>
@endsection
