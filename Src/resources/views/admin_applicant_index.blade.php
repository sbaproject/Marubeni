@extends('layouts.master')

@section('title')
{{ __('label.menu_applicant_list') }}
@endsection

@section('content-header')
{{ __('label.menu_applicant_list') }}
@endsection

@section('content-breadcrumb')
<li class="breadcrumb-item active">{{ __('label.menu_settings') }}</li>
<li class="breadcrumb-item active">{{ __('label.menu_applicant_list') }}</li>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content">
        <div class="button_wrap">
            <a href="{{ route('admin.applicant.create') }}" class="btn bg-gradient-danger">
                <i class="nav-icon fa fa-plus-circle" style="margin-right:5px;"></i>{{ __('label.button_addnew') }}</a>
        </div>
        {{-- <x-alert /> --}}
        <div class="invoice p-3">
            <div class="card-body p-0 card-list-items">
                <div class="wrap_tbl_ad">
                    <table class="table table-bordered table-hover" style="min-width: 500px;">
                        <thead>
                            <tr>
                                <th style="width: 10px">{{ __('label.applicant_no') }}</th>
                                <th style="width: 20%">{{ __('label.applicant_location') }}</th>
                                <th>{{ __('label.applicant_department') }}</th>
                                <th style="width: 10%">{{ __('label.applicant_role') }}</th>                         
                                <th style="width: 150px">
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($list_applicant))
                                @php
                                $page = request()->get("page");
                                if ($page)
                                $index = $page * 10- 9;
                                else
                                $index = 1;
                                @endphp
                                @foreach ($list_applicant as $applicant)
                                    @php
                                    if ($index < 10) $index='0' . $index @endphp
                                        <tr>
                                            <td>{{ $index }}</td>
                                            <td>{{ $applicant->location == 0 ? __('label.han') : __('label.hcm') }}</td>
                                            <td>{{ !empty($applicant->name) ? $applicant->name : '' }}</td>
                                            <td>{{ array_search($applicant->role, $roles) }}</td>
                                            <td>
                                                <x-action>
                                                    <x-slot name="editUrl">
                                                        {{ route('admin.applicant.show', $applicant->id) }}
                                                    </x-slot>
                                                    <x-slot name="deleteUrl">
                                                        {{ route('admin.applicant.delete', $applicant->id) }}
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
            @if (isset($list_applicant))
                {{ $list_applicant->withQueryString()->links('paginator') }}
            @endif
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
@endsection
