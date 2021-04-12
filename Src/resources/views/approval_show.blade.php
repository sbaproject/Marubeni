@extends('layouts.master')
@section('title')
{{ Str::upper(__('label.application_info')) }}
@endsection
@section('css')
<link rel="stylesheet" href="css/user/09_application_info.css">
@endsection
@section('js')

@endsection

@section('content-header')
{{ __('label.application_info') }}
@endsection

@section('content-breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('user.approval.index') }}">{{ __('label.title_approval_list') }}</a></li>
<li class="breadcrumb-item active">{{ __('label.application_info') }}</li>
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 invoice p-3">
                {{-- <h4 class="mb-2" style="font-weight: 600;">{{ Str::upper(__('label.application_info')) }}</h4> --}}
                <div class="col-lg-9 card-app" style="margin:0 auto">
                    {{-- <x-alert/> --}}
                    <div class="card-body p-0">
                        <div class="appcation_info">
                            <div class="row ">
                                <div class="col-sm-3 pr-sm-0">
                                    <div class="app_title">{{ __('label.application_no') }}</div>
                                </div>
                                <div class="col-sm-9 pl-sm-0">
                                    <div class="app_info">
                                        {{ $application->application_no }}
                                        @if ($application->subsequent == config('const.check.on'))
                                            <span style="color: red">Subsequent</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3 pr-sm-0">
                                    <div class="app_title">{{ __('label.status_application_type') }}</div>
                                </div>
                                <div class="col-sm-9 pl-sm-0">
                                    <div class="app_info">{{ __('label.form.'.$application->form_id) }}</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3 pr-sm-0">
                                    <div class="app_title">{{ __('label.applicant') }}</div>
                                </div>
                                <div class="col-sm-9 pl-sm-0 ">
                                    <div class="app_info">
                                        {{ $application->applicant_name }}
                                        /
                                        {{$application->applicant_department_name}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3 pr-sm-0">
                                    <div class="app_title">{{ __('label.date') }}</div>
                                </div>
                                <div class="col-sm-9 pl-sm-0">
                                    <div class="app_info">
                                        @php
                                            // $dt = Carbon\Carbon::parse($application->created_at)->locale('vi');
                                            $dt = Carbon\Carbon::parse($application->created_at);
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
                                        @if (!empty($application->file_path))
                                            <a href="{{ Storage::url($application->file_path) }}" target="_blank">
                                                {{ basename($application->file_path) }}
                                            </a>
                                        @else
                                            {{ __('label.no_attached_file') }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <form action="{{ route('user.approval.update', $application->id) }}" method="POST" class="mt-1">
                        @csrf
                        <div class="row mt-3 mb-3">
                            <div class="col-12">
                                <a href="{{ Common::getRoutePreviewApplication($application->id, $application->form_id) }}"
                                    class="btn btn-outline-secondary" target="_blank">
                                    {{ __('label.button_preview') }}
                                </a>
                            </div>
                        </div>

                        {{-- comments --}}
                        <div class="col-md-12 p-0">
                            <div class="invoice direct-chat direct-chat-danger mb-3">
                                <div class="card-header">
                                    <h3 class="card-title">{{ __('label.comments') }}  ({{ count($comments) }})</h3>
                                </div>
                                <div class="card-body">
                                    <div class="direct-chat-messages">
                                        @if (count($comments) > 0)
                                            @foreach ($comments as $item)
                                            <div class="direct-chat-msg">
                                                <div class="direct-chat-infos clearfix">
                                                    <span class="direct-chat-name float-left">{{ $item->user_name }}</span>
                                                    <span class="direct-chat-timestamp float-right">
                                                        {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i:s') }}
                                                    </span>
                                                </div>
                                                <img class="direct-chat-img" src="/images/no-photo.jpg" alt="Message User Image">
                                                <div class="direct-chat-text">
                                                    {{-- was skipped --}}
                                                    @if (!empty($item->skiped_by))
                                                        <span class='badge badge-dark'>{{ __('label.approval_action_skipped') }}</span>
                                                    {{-- approval action --}}
                                                    @else
                                                        {!! Common::generateBadgeByApprovalStatus($item->status, $item->step) !!}
                                                    @endif
                                                    <div style="white-space: pre-wrap;">{{ $item->comment }}</div>
                                                </div>
                                            </div>
                                            @endforeach
                                        @else
                                            <div class="text-center">{{ __('msg.no_comments') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-footer">
                                        <div class="input-group">
                                            <textarea class="form-control comment_area" id="app_comment" name="comment" rows="4"
                                                @if(!$flgUserTO && !$flgUserCC) readonly @endif
                                                placeholder="{{ __('label.comment_here') }}">{{ old('comment') }}</textarea>
                                        </div>
                                </div>
                            </div>
                        </div>

                        <div class="row text-center">
                            <div class="col-12">
                                @if ($flgUserTO)
                                <button type="button" name="approve" value="approve" class="btn bg-gradient-success" data-toggle="modal"
                                    data-target="#popup-confirm">
                                    {{ __('label.button_approval') }}
                                </button>
                                <button type="button" name="reject" value="reject" class="btn bg-gradient-danger" data-toggle="modal"
                                    data-target="#popup-confirm">
                                    {{ __('label.button_reject') }}
                                </button>
                                @endif
                                @if ($flgUserTO || $flgUserCC)
                                    <button type="button" name="declined" value="declined" class="btn bg-gradient-warning" data-toggle="modal"
                                        data-target="#popup-confirm">
                                        {{ __('label.button_declined') }}
                                    </button>
                                @endif
                                <a href="{{ route('user.approval.index') }}" class="btn bg-gradient-secondary">
                                    {{ __('label.button_cancel') }}
                                </a>
                            </div>
                        </div>
                        <input type="hidden" name="form_id" value="{{ $application->form_id }}">
                        <input type="hidden" name="last_updated_at" value="{{ $application->updated_at }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>
@endsection