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
@php
    $app_comment = Session::has('inputs') ? Session::get('inputs')['comment'] : (isset($app) ? $app->comment : null);
    $isAbleToAction = false;
    if($app->status >= config('const.application.status.applying') && $app->status < config('const.application.status.completed')){
        if($app->approver_id === Auth::user()->id
            && $app->approver_type === config('const.approver_type.to')
            && $app->created_by !== Auth::user()->id) {
            $isAbleToAction = true;
        }
    }
@endphp
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-9 col-md-12">
                <h4 class="mb-2" style="font-weight: 600;">{{ Str::upper(__('label.application_info')) }}</h4>
                <div class="card card-app">
                    <x-alert/>
                    <div class="card-body p-0">
                        <div class="appcation_info">
                            <div class="row ">
                                <div class="col-sm-3 pr-sm-0">
                                    <div class="app_title">{{ __('label.application_no') }}</div>
                                </div>
                                <div class="col-sm-9 pl-sm-0">
                                    <div class="app_info">{{ $app->application_no }}</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3 pr-sm-0">
                                    <div class="app_title">{{ __('label.status.application_type') }}</div>
                                </div>
                                <div class="col-sm-9 pl-sm-0">
                                    <div class="app_info">{{ __('label.form.'.$app->form_id) }}</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3 pr-sm-0">
                                    <div class="app_title">{{ __('label.applicant') }}</div>
                                </div>
                                <div class="col-sm-9 pl-sm-0 ">
                                    <div class="app_info">{{ $app->applicant }}</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3 pr-sm-0">
                                    <div class="app_title">{{ __('label.date') }}</div>
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
                                    <div class="app_title">{{ __('label.file') }}</div>
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
                            <a href="{{ Common::getRoutePreviewApplication($app->id, $app->form_id) }}" class="btn btn-secondary bt_border bt_preview" target="_blank">
                                {{ __('label.button.preview') }}
                            </a>
                        </div>
                    </div>
                    <div class="row wrap_comment">
                        <div class="col-md-3 col-xl-2 ">
                            <span class="comment_title">{{ __('label.comment') }}</span>
                        </div>
                        <div class="col-md-9 col-xl-10">
                            <textarea class="form-control comment_area" id="app_comment" name="comment" rows="4" @if (!$isAbleToAction) readonly @endif
                                placeholder="{{ __('label.comment') }}">{{ $app_comment }}</textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            @if ($isAbleToAction)
                                <button type="button" name="approve" value="approve" class="btn btn-success bt_border"
                                    data-toggle="modal" data-target="#popup-confirm">
                                    {{ __('label.button.approval') }}
                                </button>
                                <button type="button" name="reject" value="reject" class="btn btn-danger bt_border"
                                    data-toggle="modal" data-target="#popup-confirm">
                                    {{ __('label.button.reject') }}
                                </button>
                                <button type="button" name="declined" value="declined" class="btn btn-secondary bt_border bt_preview"
                                    data-toggle="modal" data-target="#popup-confirm">
                                    {{ __('label.button.declined') }}
                                </button>
                            @endif
                            <a href="{{ route('user.approval.index') }}" class="btn btn-secondary bt_border bt_preview">
                                {{ __('label.button.cancel') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>
@endsection