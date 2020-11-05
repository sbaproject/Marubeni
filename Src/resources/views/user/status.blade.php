@extends('layouts.master')
@section('title', 'User-Status')
@section('css')
    <link rel="stylesheet" href="css/user/02_status.css">
@endsection
@section('js')
    <!-- moment -->
    <script src="js/moment/moment.min.js"></script>
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
                <div class="col-md-4 col-sm-4">
                    <label class="lbl-to">To</label>
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
                                style="margin-right:5px;"></i>Search</button>
                    </div>
                </div>
        </form>
    </section>

    <!-- Main content -->
    <section class="content-status">
        <h4 class="mb-2" style="border-bottom: 1px solid #000;font-weight: bold;"><i class="nav-icon fas fa-file-alt"
                aria-hidden="true" style="margin-right: 5px;margin-bottom: 5px;"></i>List of applying documents</h4>
        <div class="card">
            <div class="card-body p-0 card-list-items">
                <table class="table">
                    <thead>
                        <tr class="list-title">
                            <th>Application No</th>
                            <th>Application type</th>
                            <th>Apply Date</th>
                            <th>Next Approver</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($list_applications_status))
                            @php
                            $page = request()->get("page");
                            if ($page)
                            $index = $page * 5 - 9;
                            else
                            $index = 1;
                            @endphp
                            @foreach ($list_applications_status as $application_status)
                                @php
                                if ($index < 10) $index='0' . $index @endphp <tr class="list-content">
                                    <td>{{ $index }}</td>
                                    <td>{{ !empty($application_status->nameapp) ? $application_status->nameapp : '' }}</td>
                                    <td>{{ !empty($application_status->datecreate) ? \Carbon\Carbon::parse($application_status->datecreate)->format('d/m/Y') : '' }}
                                    </td>
                                    <td>{{ !empty($application_status->nameuser) ? $application_status->nameuser : '' }}
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
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        @if (isset($list_applications_status))
            {{ $list_applications_status->withQueryString()->links('paginator') }}
        @endif
        <div id='str_date' value='@if (\Session::has('str_date'))
        {{ \Session::get('str_date') }}@else{{ Carbon\Carbon::now() }}@endif'>
        </div>
        <div id='end_date' value='@if (\Session::has('end_date'))
        {{ \Session::get('end_date') }}@else{{ Carbon\Carbon::now() }}@endif'>
        </div>
    </section>
@endsection
