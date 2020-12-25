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
<li class="breadcrumb-item"><a href="{{ route('user.approval.index') }}">{{ __('label.title.approval.list') }}</a></li>
<li class="breadcrumb-item active">{{ __('label.application_info') }}</li>
@endsection

@section('content')
@php
    $app_comment = Session::has('inputs') ? Session::get('inputs')['comment'] : (isset($application) ? $application->comment : null);
    $isAbleToAction = false;
    if($application->status >= config('const.application.status.applying') && $application->status < config('const.application.status.completed')){
        if($application->approver_id === Auth::user()->id
            && $application->approver_type === config('const.approver_type.to')
            && $application->created_by !== Auth::user()->id) {
            $isAbleToAction = true;
        }
    }
@endphp
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
                                    <div class="app_title">{{ __('label.status.application_type') }}</div>
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
                                    <div class="app_info">{{ $application->applicant }}</div>
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
                        <div class="row">
                            <div class="col-12">
                                <a href="{{ Common::getRoutePreviewApplication($application->id, $application->form_id) }}"
                                    class="btn btn-outline-secondary" target="_blank">
                                    {{ __('label.button.preview') }}
                                </a>
                            </div>
                        </div>
                        {{-- <div class="row wrap_comment">
                            <div class="col-md-3 col-xl-2 ">
                                <span class="comment_title">{{ __('label.comment') }}</span>
                            </div>
                            <div class="col-md-9 col-xl-10">
                                <textarea class="form-control comment_area" id="app_comment" name="comment" rows="4" @if (!$isAbleToAction)
                                    readonly @endif placeholder="{{ __('label.comment') }}">{{ $app_comment }}</textarea>
                            </div>
                        </div> --}}

                        {{-- comments --}}
                        <div class="col-md-12 p-0">
                            <!-- DIRECT CHAT DANGER -->
                            <div class="card card-danger direct-chat direct-chat-danger">
                                <div class="card-header">
                                    <h3 class="card-title">{{ __('label.comment') }}</h3>
                                    <div class="card-tools">
                                        <span data-toggle="tooltip" title="{{ count($comments).' '. __('label.comment') }}"
                                            class="badge">{{ count($comments) }}</span>
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <!-- Conversations are loaded here -->
                                    <div class="direct-chat-messages">
                                        <!-- Message. Default to the left -->
                                        <div class="direct-chat-msg">
                                            <div class="direct-chat-infos clearfix">
                                                <span class="direct-chat-name float-left">Alexander Pierce</span>
                                                <span class="direct-chat-timestamp float-right">23 Jan 2:00 pm</span>
                                            </div>
                                            <!-- /.direct-chat-infos -->
                                            {{-- <img class="direct-chat-img" src="" alt="Message User Image"> --}}
                                            <!-- /.direct-chat-img -->
                                            @foreach ($comments as $item)
                                                <div class="direct-chat-text" style="white-space: pre-wrap;">
                                                    {{ $item->comment }}
                                                </div>
                                            @endforeach
                                            
                                            <!-- /.direct-chat-text -->
                                        </div>
                                        <!-- /.direct-chat-msg -->
                                    </div>
                                    <!--/.direct-chat-messages-->
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <form action="#" method="post">
                                        <div class="input-group">
                                            <textarea class="form-control comment_area" id="app_comment" name="comment" rows="4" @if(!$isAbleToAction) readonly @endif
                                                placeholder="{{ __('label.comment') }}">{{ $app_comment }}</textarea>
                                            {{-- <span class="input-group-append">
                                                                    <button type="submit" class="btn btn-danger">Send</button>
                                                                </span> --}}
                                        </div>
                                    </form>
                                </div>
                                <!-- /.card-footer-->
                            </div>
                            <!--/.direct-chat -->
                        </div>

                        <div class="row text-center">
                            <div class="col-12">
                                @if ($isAbleToAction)
                                <button type="button" name="approve" value="approve" class="btn bg-gradient-success" data-toggle="modal"
                                    data-target="#popup-confirm">
                                    {{ __('label.button.approval') }}
                                </button>
                                <button type="button" name="reject" value="reject" class="btn bg-gradient-danger" data-toggle="modal"
                                    data-target="#popup-confirm">
                                    {{ __('label.button.reject') }}
                                </button>
                                <button type="button" name="declined" value="declined" class="btn bg-gradient-warning" data-toggle="modal"
                                    data-target="#popup-confirm">
                                    {{ __('label.button.declined') }}
                                </button>
                                @endif
                                <a href="{{ route('user.approval.index') }}" class="btn bg-gradient-secondary">
                                    {{ __('label.button.cancel') }}
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