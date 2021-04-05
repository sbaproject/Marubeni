@extends('layouts.master')
@section('title')
    {{ $intstatus == config('const.application.status.applying') ? __('label.status_list_of_applying_documents') : ($intstatus == config('const.application.status.approvel_un') ? __('label.status_list_of_approval_un_documents') : ($intstatus == config('const.application.status.approvel_in') ? __('label.status_list_of_approval_in_documents') : ($intstatus == config('const.application.status.declined') ? __('label.status_list_of_declined_documents') : ($intstatus == config('const.application.status.reject') ? __('label.status_list_of_reject_documents') : ($intstatus == config('const.application.status.completed') ? __('label.status_list_of_completed_documents') : ''))))) }}
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

@php
    $title = $intstatus == config('const.application.status.applying') ? __('label.status_list_of_applying_documents') : ($intstatus == config('const.application.status.approvel_un') ? __('label.status_list_of_approval_un_documents') : ($intstatus == config('const.application.status.approvel_in') ? __('label.status_list_of_approval_in_documents') : ($intstatus == config('const.application.status.declined') ? __('label.status_list_of_declined_documents') : ($intstatus == config('const.application.status.reject') ? __('label.status_list_of_reject_documents') : ($intstatus == config('const.application.status.completed') ? __('label.status_list_of_completed_documents') : '')))));
@endphp

@section('content-header')
{{-- {{ $title }} --}}
@endsection

@section('content-breadcrumb')
<li class="breadcrumb-item active">{{ __('label.menu_status') }}</li>
<li class="breadcrumb-item active">{{ $title }}</li>
@endsection

@section('content')
    <section class="invoice p-3 mb-3">
        <form method="get" id="formSearch" action="">
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
                        <button class="btn bg-gradient-primary" type="submit"><i class="fa fa-search"
                                style="margin-right:5px;"></i>{{ __('label.button_search') }}</button>
                    </div>
                </div>
        </form>
    </section>

    <!-- Main content -->
    <section class="invoice p-3 mb-3">
        <h4 class="mb-2" style="border-bottom: 1px solid #000;font-weight: bold;"><i class="nav-icon fas fa-file-alt"
                aria-hidden="true"
                style="margin-right: 5px;margin-bottom: 5px;"></i>
                {{ $title }}
        </h4>
        <div class="card">
            <div class="card-body p-0 card-list-items">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="">
                            <th class="sortable {{ $sortable->headers['application_no']->activeCls }}">
                                {!! $sortable->headers['application_no']->title !!}
                            </th>
                            <th class="sortable {{ $sortable->headers['form_name']->activeCls }}">
                                {!! $sortable->headers['form_name']->title !!}
                            </th>
                            <th class="sortable {{ $sortable->headers['datecreate']->activeCls }}">
                                {!! $sortable->headers['datecreate']->title !!}
                            </th>
                            <th class="sortable {{ $sortable->headers['next_approver']->activeCls }}">
                                {!! $sortable->headers['next_approver']->title !!}
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($list_applications_status))
                            @foreach ($list_applications_status as $application_status)
                                @php
                                    $completedFlg = $intstatus == config('const.application.status.completed');
                                    $finalApproverFlg = $application_status->final_approver_id == $application_status->next_approver_id;
                                @endphp
                                <tr class="">
                                    <td>{{ !empty($application_status->application_no) ? $application_status->application_no : '' }}
                                    </td>
                                    <td>{{ !empty($application_status->form_name) ? $application_status->form_name : '' }}</td>
                                    <td>{{ !empty($application_status->datecreate) ? \Carbon\Carbon::parse($application_status->datecreate)->format('d/m/Y') : '' }}
                                    </td>
                                    <td>
                                        @if (!$completedFlg && !empty($application_status->next_approver))
                                            {{ $application_status->next_approver }}
                                        @endif
                                    </td>
                                    <td>
                                        @if (!$completedFlg && !$finalApproverFlg)
                                            <button type="button" name="btnSkip" class="btn bg-gradient-warning btn-sm" title="{{ __('label.button_skip') }}"
                                                data-toggle="modal" data-target="#modal-skip"
                                                data-skip-who-id="{{ $application_status->next_approver_id }}"
                                                data-skip-who-name="{{ $application_status->next_approver }}"
                                                data-skip-form-id="{{ $application_status->form_id }}"
                                                data-skip-url="{{ route('user.approval.skip', $application_status->id) }}"
                                                data-last-updated-at="{{ $application_status->updated_at }}">
                                                <i class="fa fa-fast-forward"></i>
                                            </button>
                                        @endif
                                        <a class="btn bg-gradient-info btn-sm" href="{{ Common::getRouteEditApplication($application_status->id, $application_status->form_id) }}"
                                                title="{{ __('label.status_view_details') }}">
                                            <i class="fas fa-search"></i>
                                        </a>
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
    {{-- Skip Modal --}}
    <div class="modal fade" id="modal-skip" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="form-skip" action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Bạn muốn bỏ qua người duyệt: <span id="skip-who-name"></span>
                    </h5>
                    <button type="button" id="btn-skip-close" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Lý do:</label>
                        <textarea class="form-control" id="skip_comment" name="skip_comment" rows="5"></textarea>
                        <input type="hidden" id="skip_group_id" name="skip_group_id">
                        <input type="hidden" id="skip_step_id" name="skip_step_id">
                        <input type="hidden" id="skip_form_id" name="skip_form_id">
                        <input type="hidden" id="skip_who_id" name="skip_who_id">
                        <input type="hidden" id="skip_last_updated_at" name="skip_last_updated_at">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btn-skip-cancel" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="btn-skip-submit" class="btn btn-warning">Accept</button>
                    <button type="button" id="btn-skip-submit-processing" class="btn btn-warning d-none" disabled>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Accept
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(function(){

            $('#btnSkip').on('click', function(){
                $($(this).data('data-target')).modal('show', $(this));
            });

            $('#modal-skip').on('show.bs.modal', function (event) {
                // button trigger this modal
                var button = $(event.relatedTarget);
                // data
                var skipWhoId = button.data('skip-who-id');
                var skipWhoName = button.data('skip-who-name');
                var skipFormId = button.data('skip-form-id');
                var actionUrl = button.data('skip-url');
                var lastUpdatedAt = button.data('last-updated-at');
                // get modal
                var modal = $(this);
                // form post
                var formPost = modal.find('#form-skip');
                //
                modal.find('#skip-who-name').text(skipWhoName);
                modal.find('#skip_who_id').val(skipWhoId);
                modal.find('#skip_form_id').val(skipFormId);
                modal.find('#skip_comment').val('');
                modal.find('#skip_last_updated_at').val(lastUpdatedAt);
                modal.find('#form-skip').attr('action', actionUrl);
            });

            $('#btn-skip-submit').on('click', function(){
                if($('#skip_comment').val().trim() == ''){
                    alert('Vui lòng nhập lý do');
                    return;
                }
                // not show loading dialog while processing
                showLoadingFlg = false;
                // get form post
                let form = this.form;
                // lock screen
                $(this).hide();
                $('#btn-skip-cancel').attr('disabled','disabled');
                $('#btn-skip-close').attr('disabled','disabled');
                $('#btn-skip-submit-processing').removeClass('d-none');
                $('#modal-skip').data('bs.modal')._config.backdrop = 'static';
                //submit
                form.submit();
            });
        })
    </script>
@endsection
