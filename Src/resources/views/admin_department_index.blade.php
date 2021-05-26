@extends('layouts.master')

@section('title')
{{ __('label.menu_department_list') }}
@endsection

@section('content-header')
{{ __('label.menu_department_list') }}
@endsection

@section('content-breadcrumb')
<li class="breadcrumb-item active">{{ __('label.menu_settings') }}</li>
<li class="breadcrumb-item active">{{ __('label.menu_department_list') }}</li>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content">
        <div class="button_wrap">
            <a href="{{ route('admin.department.create') }}" class="btn bg-gradient-danger">
                <i class="nav-icon fa fa-plus-circle" style="margin-right:5px;"></i>{{ __('label.button_addnew') }}</a>
        </div>
        {{-- <x-alert /> --}}
        <div class="invoice p-3">
            <div class="card-body p-0 card-list-items">
                <div class="wrap_tbl_ad">
                    <table class="table table-bordered table-hover" style="min-width: 500px;">
                        <thead>
                            <tr>
                                <th style="width: 10px">{{ __('label.department_no') }}</th>
                                <th style="width: 40%">{{ __('label.department_name') }}</th>
                                <th>{{ __('label.department_memo') }}</th>                               
                                <th style="width: 150px">
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($list_department))
                                @php
                                $page = request()->get("page");
                                if ($page)
                                $index = $page * 10- 9;
                                else
                                $index = 1;
                                @endphp
                                @foreach ($list_department as $department)
                                    @php
                                    if ($index < 10) $index='0' . $index @endphp <tr>
                                        <td>{{ $index }}</td>
                                        <td>{{ !empty($department->name) ? $department->name : '' }}</td>
                                        <td>{{ !empty($department->memo) ? $department->memo : '' }}</td>                                       
                                        <td>
                                            <x-action>
                                                <x-slot name="editUrl">
                                                    {{ route('admin.department.show', $department->id) }}
                                                </x-slot>
                                                <x-slot name="deleteUrl">
                                                    {{ route('admin.department.delete', $department->id) }}
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
            <!-- /.card-body -->
            @if (isset($list_department))
                {{ $list_department->withQueryString()->links('paginator') }}
            @endif
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
@endsection
