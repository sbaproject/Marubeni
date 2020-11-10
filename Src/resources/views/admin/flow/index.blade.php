@extends('layouts.master')
@section('title', 'Approval Flow List')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header mb-0">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-lg-6">
                    <h1>{{ __('label.flow.approval_flow_list') }}</h1>
                </div>
            </div>
        </div>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="button_wrap">
            <a href="{{ route('admin.flow.create') }}" class="btn btn-danger pt-1 pb-1 pl-5 pr-5 mb-2"><i
                    class="nav-icon fa fa-plus-circle" style="margin-right: 5px"></i>{{ __('label.button.addnew') }}</a>
        </div>
        <div class="card">
            <div class="card-body p-0 ">
                <div class="wrap_tbl_ad">
                    <div class="content_roll">
                        <table class="table table-bordered " style="min-width: 500px;">
                            <thead>
                                <tr>
                                    <th style="width: 10px;">{{ __('label.flow.no') }}</th>
                                    <th>{{ __('label.flow.flow_name') }}</th>
                                    <th>{{ __('label.flow.step') }}</th>
                                    <th>{{ __('label.flow.final_approver') }}</th>
                                    <th style="width: 150px;">{{ __('label.flow.Actions') }}</th>
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
