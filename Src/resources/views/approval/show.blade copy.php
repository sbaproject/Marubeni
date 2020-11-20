@extends('layouts.master')
@section('title')
{{ Str::upper(__('label.application_info')) }}
@endsection
@section('css')
<link rel="stylesheet" href="css/user/09_application_info.css">
@endsection
@section('js')

@endsection
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-9 col-md-12">
                <h4 class="mb-2" style="font-weight: 600;">{{ Str::upper(__('label.application_info')) }}</h4>
                <div class="card card-app">
                    <x-alert>
                        <div class="card-body p-0">
                            <div class="appcation_info">
                                <div class="row ">
                                    <div class="col-sm-3 pr-sm-0">
                                        <div class="app_title">Application No</div>
                                    </div>
                                    <div class="col-sm-9 pl-sm-0">
                                        <div class="app_info">{{ $app->application_no }}</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3 pr-sm-0">
                                        <div class="app_title">Application Type</div>
                                    </div>
                                    <div class="col-sm-9 pl-sm-0">
                                        <div class="app_info">{{ __('label.form.'.$app->form_id) }}</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3 pr-sm-0">
                                        <div class="app_title"> Application Name</div>
                                    </div>
                                    <div class="col-sm-9 pl-sm-0 ">
                                        <div class="app_info">{{ $app->applicant->name }}</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3 pr-sm-0">
                                        <div class="app_title">Date</div>
                                    </div>
                                    <div class="col-sm-9 pl-sm-0">
                                        <div class="app_info">
                                            @php
                                            // $dt = Carbon\Carbon::parse($app->created_at)->locale('vi');
                                            $dt = Carbon\Carbon::parse($app->created_at);
                                            if(config('app.locale') == 'vi'){
                                            echo 'Ngày ' . $dt->day . ' Tháng '. $dt->month . ' Năm '. $dt->year;
                                            } else {
                                            echo $dt->toFormattedDateString();
                                            }
                                            @endphp
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3 pr-sm-0">
                                        <div class="app_title">File</div>
                                    </div>
                                    <div class="col-sm-9 pl-sm-0">
                                        <div class="app_info last_item">
                                            <a href="{{ Storage::url($app->file_path) }}" target="_blank">
                                                {{ basename($app->file_path) }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                </div>
                <form action="{{ route('user.approval.update', $app->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <button type="button" class="bt_preview bt_border">Preview</button>
                        </div>
                    </div>
                    <div class="row wrap_comment">
                        <div class="col-md-3 col-xl-2 ">
                            <span class="comment_title">Comment</span>
                        </div>
                        <div class="col-md-9 col-xl-10">
                            <textarea class="form-control comment_area" id="app_comment" rows="4" placeholder="Type here">{{ $app->comment }}</textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" name="approve" value="approve" class="btn btn-success bt_border">Approval</button>
                            <button type="submit" name="reject" value="reject" class="btn btn-danger bt_border">Reject</button>
                            <button type="submit" name="declined" value="declined" class="btn btn-secondary bt_border bt_preview">Declined</button>
                            <a href="{{ route('user.approval.index') }}" class="btn btn-secondary bt_border bt_preview">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>
@endsection