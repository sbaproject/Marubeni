@extends('layouts.master')

@section('title')
{{ __('label.menu_company_list') }}
@endsection

@section('content-header')
{{ __('label.menu_company_list') }}
@endsection

@section('content-breadcrumb')
<li class="breadcrumb-item active">{{ __('label.menu_settings') }}</li>
<li class="breadcrumb-item active">{{ __('label.menu_company_list') }}</li>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content mb-3">
        <form method="get" id="formSearch" action="">
            @csrf
            <div class="row">
                <div class="col-xl-8 col-lg-10">
                    <div class="invoice">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('label.button_search') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="search-content">
                                <div class="row">
                                    <div class="col-xl-8 col-lg-9">
                                        <div class="form-group row">
                                            <label for="inputCompanyName"
                                                class="col-lg-4 col-form-label text-center font-weight-normal">{{ __('label.company_company_name') }}</label>
                                            <div class="col-lg-8">
                                                <input type="text" name="company_name"
                                                    value="{{ !empty($req_arr['company_name']) ? $req_arr['company_name'] : '' }}"
                                                    class="form-control" id="inputCompanyName" placeholder="{{ __('label.company_company_name') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputAttendantsName"
                                                class="col-lg-4 col-form-label text-center font-weight-normal">{{ __('label.company_att_name') }}</label>
                                            <div class="col-lg-8">
                                                <input type="text" name="company_att_name"
                                                    value="{{ !empty($req_arr['company_att_name']) ? $req_arr['company_att_name'] : '' }}"
                                                    class="form-control" id="inputAttendantsName"
                                                    placeholder="{{ __('label.company_att_name') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-8 col-lg-9">
                                        <div class="form-group row">
                                            <label for="inputKeyword"
                                                class="col-lg-4 col-form-label text-center font-weight-normal">{{ __('label.company_keyword') }}</label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control"
                                                    value="{{ !empty($req_arr['company_keyword']) ? $req_arr['company_keyword'] : '' }}"
                                                    name="company_keyword" id="inputKeyword" placeholder="{{ __('label.company_keyword') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3">
                                        <div class="btn-search">
                                            <button class="btn bg-gradient-primary" type="submit">
                                                <i class="fa fa-search" style="margin-right:5px;"></i>
                                                {{ __('label.button_search') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="button_wrap">
            <a href="{{ route('admin.company.create') }}" class="btn bg-gradient-danger">
                <i class="nav-icon fa fa-plus-circle" style="margin-right:5px;"></i>{{ __('label.button_addnew') }}</a>
        </div>
        {{-- <x-alert /> --}}
        <div class="invoice p-3">
            <div class="card-body p-0 card-list-items">
                <div class="wrap_tbl_ad">
                    <table class="table table-bordered table-hover" style="min-width: 500px;">
                        <thead>
                            <tr>
                                <th rowspan="2" class="align-middle" style="width: 10px;">{{ __('label.company_no') }}</th>
                                <th colspan="3" class="miss-border">{{ __('label.company_company_information') }}</th>
                                <th colspan="2" class="miss-border">{{ __('label.company_att_information') }}</th>
                                <th rowspan="2" class="align-middle" style="width: 150px;">{{ __('label.company_action') }}</th>
                            </tr>
                            <tr>
                                <th>{{ __('label.company_name') }}</th>
                                <th>{{ __('label.company_company_address') }}</th>
                                <th>{{ __('label.company_company_tell') }}</th>
                                <th>{{ __('label.company_name') }}</th>
                                <th>{{ __('label.company_att_department') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($list_company))
                                @php
                                $page = request()->get("page");
                                if ($page)
                                $index = $page * 5 - 4;
                                else
                                $index = 1;
                                @endphp
                                @foreach ($list_company as $company)
                                    @php
                                    if ($index < 10) $index='0' . $index @endphp <tr>
                                        <td>{{ $index }}</td>
                                        <td>{{ !empty($company->name) ? $company->name : '' }}</td>
                                        <td>{{ !empty($company->address) ? $company->address : '' }}</td>
                                        <td>{{ !empty($company->phone) ? $company->phone : '' }}</td>
                                        <td>{{ !empty($company->attendants_name) ? $company->attendants_name : '' }}</td>
                                        <td>{{ !empty($company->attendants_department) ? $company->attendants_department : '' }}
                                        </td>
                                        <td>
                                            <x-action>
                                                <x-slot name="editUrl">
                                                    {{ route('admin.company.show', $company->id) }}
                                                </x-slot>
                                                <x-slot name="deleteUrl">
                                                    {{ route('admin.company.delete', $company->id) }}
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
            @if (isset($list_company))
                {{ $list_company->withQueryString()->links('paginator') }}
            @endif
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
@endsection
