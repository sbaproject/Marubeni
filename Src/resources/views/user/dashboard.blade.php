@extends('layouts.master')
@section('title','User-Dashboard')
@section('css')
    <link rel="stylesheet" href="css/user/01_dashboard.css">
@endsection
@section('js')
    <script src="js/user/dashboard.js"></script>
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
                            <div class="input-group-addon input-group-append" data-target="#dateFrom" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                            </div>
                            <input type="text" name="dateFrom" class="form-control datetimepicker-input"
                                data-target="#dateFrom" />
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <label class="lbl-to">To</label>
                    <div class="form-group">
                        <div class="input-group date" id="dateTo" data-target-input="nearest">
                            <div class="input-group-addon input-group-append" data-target="#dateTo" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                            </div>
                            <input type="text" name="dateTo" class="form-control datetimepicker-input"
                                data-target="#dateTo" />
                        </div>
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
    <section class="content-dashboard">
        <div class="row" style="text-align: center;">
            <div class="col-xs-5ths">
                <a href="/pages/examples/user-status.html">
                    <div class="card">
                        <div class="card-body card-wrap-items">
                            <span>Applying</span>
                            <span class="right-number">(2)</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xs-5ths col-set">
                <a href="/pages/examples/user-status.html">
                    <div class="card">
                        <div class="card-body card-wrap-items">
                            <span>Approval</span>
                            <span class="right-number">(2)</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xs-5ths col-set">
                <a href="/pages/examples/user-status.html">
                    <div class="card">
                        <div class="card-body card-wrap-items">
                            <span>Declined</span>
                            <span class="right-number">(2)</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xs-5ths col-set">
                <a href="/pages/examples/user-status.html">
                    <div class="card">
                        <div class="card-body card-wrap-items">
                            <span>Reject</span>
                            <span class="right-number">(2)</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xs-5ths col-set">
                <a href="/pages/examples/user-status.html">
                    <div class="card">
                        <div class="card-body card-wrap-items">
                            <span>Completed</span>
                            <span class="right-number">(2)</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <h4 class="mb-2" style="border-bottom: 1px solid #000;font-weight: bold;margin-top: 25px;"><i
                class="nav-icon fas fa-file-alt" aria-hidden="true" style="margin-right: 5px;margin-bottom: 5px;"></i>List
            of Applications</h4>
        <div class="card">
            <div class="card-body p-0 card-list-items">
                <table class="table">
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
                        <tr class="list-content">
                            <td>NO-TM-0000</td>
                            <td>交際費申請書</td>
                            <td>
                                <div class="status-apply">
                                    Applying
                                </div>
                            </td>
                            <td>22/10/2020</td>
                            <td>

                                <a class="btn btn-details" href="/pages/examples/09_application_info.html">View
                                    Details<i class="fas fa-angle-right" style="margin-left: 5px;"></i></a>
                            </td>
                        </tr>
                        <tr class="list-content">
                            <td>NO-TM-0000</td>
                            <td>交際費申請書</td>
                            <td>
                                <div class="status-approval">
                                    Approval
                                </div>
                            </td>
                            <td>22/10/2020</td>
                            <td>
                                <a class="btn btn-details" href="/pages/examples/09_application_info.html">View
                                    Details<i class="fas fa-angle-right" style="margin-left: 5px;"></i></a>
                            </td>
                        </tr>
                        <tr class="list-content">
                            <td>NO-BT-0000</td>
                            <td>出張申請書</td>
                            <td>
                                <div class="status-declined">
                                    Declined
                                </div>
                            </td>
                            <td>22/10/2020</td>
                            <td>
                                <a class="btn btn-details" href="/pages/examples/09_application_info.html">View
                                    Details<i class="fas fa-angle-right" style="margin-left: 5px;"></i></a>
                        </tr>
                        <tr class="list-content">
                            <td>NO-PH-0000</td>
                            <td>有給申請書</td>
                            <td>
                                <div class="status-reject">
                                    Reject
                                </div>
                            </td>
                            <td>22/10/2020</td>
                            <td>
                                <a class="btn btn-details" href="/pages/examples/09_application_info.html">View
                                    Details<i class="fas fa-angle-right" style="margin-left: 5px;"></i></a>
                            </td>
                        </tr>
                        <tr class="list-content">
                            <td>NO-PH-0000</td>
                            <td>有給申請書</td>
                            <td>
                                <div class="status-completed">
                                    Completed
                                </div>
                            </td>
                            <td>22/10/2020</td>
                            <td>
                                <a class="btn btn-details" href="/pages/examples/09_application_info.html">View
                                    Details<i class="fas fa-angle-right" style="margin-left: 5px;"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
