@extends('layouts.master')
@section('title', 'ADMIN_DASHBOARD')
@section('css')
    <link rel="stylesheet" href="css/user/01_dashboard.css">
@endsection
@section('js')
    <!-- moment -->
    <script src="js/moment/moment.min.js"></script>
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
                    <label class="lbl-from">From</label>
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
                <a href="/pages/examples/user-status.html">
                    <div class="card">
                        <div class="card-body card-wrap-items">
                            <span>Applying</span>
                            <span class="right-number">({{ $count_applying }})</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xs-5ths col-set">
                <a href="/pages/examples/user-status.html">
                    <div class="card">
                        <div class="card-body card-wrap-items">
                            <span>Approval</span>
                            <span class="right-number">({{ $count_approval }})</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xs-5ths col-set">
                <a href="/pages/examples/user-status.html">
                    <div class="card">
                        <div class="card-body card-wrap-items">
                            <span>Declined</span>
                            <span class="right-number">({{ $count_declined }})</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xs-5ths col-set">
                <a href="/pages/examples/user-status.html">
                    <div class="card">
                        <div class="card-body card-wrap-items">
                            <span>Reject</span>
                            <span class="right-number">({{ $count_reject }})</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xs-5ths col-set">
                <a href="/pages/examples/user-status.html">
                    <div class="card">
                        <div class="card-body card-wrap-items">
                            <span>Completed</span>
                            <span class="right-number">({{ $count_completed }})</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <h4 class="mb-2" style="border-bottom: 1px solid #000;font-weight: bold;margin-top: 25px;"><i
                class="nav-icon fas fa-file-alt" aria-hidden="true"
                style="margin-right: 5px;margin-bottom: 5px;"></i>{{ __('label.list_application') }}</h4>
        <div class="card">
            <div class="card-body p-0 card-list-items">
                <table class="table" id="table_list_status">
                    <thead>
                        <tr class="list-title">
                            <th>Application No</th>
                            <th>Application Name</th>
                            <th>Status</th>
                            <th>Apply Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($list_application))
                            @php
                            $page = request()->get("page");
                            if ($page)
                            $index = $page * 5 - 4;
                            else
                            $index = 1;
                            @endphp
                            @foreach ($list_application as $application)
                                @php
                                if ($index < 10) $index='0' . $index @endphp <tr class="list-content">
                                    <td>{{ $index }}</td>
                                    <td>{{ !empty($application->form_id) ? $application->Form->name : '' }}</td>
                                    <td>
                                        <div
                                            class=" {{ $application->status == config('const.application.status.applying') ? 'status-apply' : ($application->status == config('const.application.status.declined') ? 'status-declined' : ($application->status == config('const.application.status.reject') ? 'status-reject' : ($application->status == config('const.application.status.completed') ? 'status-completed' : 'status-approval'))) }}">
                                            {{ $application->status == config('const.application.status.applying') ? 'Applying' : ($application->status == config('const.application.status.declined') ? 'Declined' : ($application->status == config('const.application.status.reject') ? 'Reject' : ($application->status == config('const.application.status.completed') ? 'Completed' : 'Approval'))) }}
                                        </div>
                                    </td>
                                    <td>{{ !empty($application->created_at) ? \Carbon\Carbon::parse($application->created_at)->format('d/m/Y') : '' }}
                                    </td>
                                    <td>
                                        <a class="btn btn-details" href="/pages/examples/09_application_info.html">View
                                            Details<i class="fas fa-angle-right" style="margin-left: 5px;"></i></a>
                                    </td>
                                    </tr>
                                    @php
                                    $index++;
                                    @endphp
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
