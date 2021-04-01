@extends('layouts.master')
@section('title', __('label.flow_approval_flow_list'))

@section('content-header')
{{ __('label.flow_approval_flow_list') }}
@endsection

@section('content-breadcrumb')
<li class="breadcrumb-item active">{{ __('label.menu_settings') }}</li>
<li class="breadcrumb-item active">{{ __('label.flow_approval_flow_list') }}</li>
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="button_wrap">
            <a href="{{ route('admin.flow.create') }}" class="btn bg-gradient-danger">
                <i class="nav-icon fa fa-plus-circle" style="margin-right: 5px"></i>{{ __('label.button_addnew') }}</a>
        </div>
        <div class="invoice p-3">
            <div class="card-body p-0 ">
                <div class="wrap_tbl_ad">
                    <div class="content_roll">
                        <table class="table table-bordered table-hover" style="min-width: 500px;">
                            <thead>
                                <tr>
                                    <th style="width: 10px;">{{ __('label.flow_no') }}</th>
                                    <th>{{ __('label.flow_flow_name') }}</th>
                                    <th>{{ __('label.flow_step') }}</th>
                                    <th>{{ __('label.flow_final_approver') }}</th>
                                    <th style="width: 150px;">{{ __('label.flow_actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($list_flows))
                                    @php
                                    $page = request()->get("page");
                                    if ($page)
                                    $index = $page * 5 - 4;
                                    else
                                    $index = 1;
                                    @endphp
                                    @foreach ($list_flows as $flow)
                                        @php
                                        if ($index < 10) $index='0' . $index @endphp <tr>
                                            <td>{{ $index }}</td>
                                            <td>{{ !empty($flow->flow_name) ? $flow->flow_name : '' }}</td>
                                            <td>{{ !empty($flow->step_type) ? $flow->step_type : '' }}</td>
                                            <td>{{ !empty($flow->user_name) ? $flow->user_name : '' }}</td>
                                            <td>
                                                <x-action>
                                                    <x-slot name="editUrl">
                                                        {{ route('admin.flow.edit', $flow->id) }}
                                                    </x-slot>
                                                    <x-slot name="deleteUrl">
                                                        {{ route('admin.flow.update', $flow->id) }}
                                                    </x-slot>
                                                </x-action>
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
            </div>
            <!-- /.card-body -->
            @if (isset($list_flows))
                {{ $list_flows->withQueryString()->links('paginator') }}
            @endif
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection
