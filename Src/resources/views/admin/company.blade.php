@extends('layouts.master')
@section('title', 'ADMIN_COMPANY')
@section('js')

@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-lg-6">
                    <h1>SEARCH</h1>
                </div>
            </div>
        </div>
        <form method="get" id="formSearch" action="">
            @csrf
            <div class="row">
                <div class="col-xl-8 col-lg-10">
                    <div class="card">
                        <div class="card-body">
                            <div class="search-content">

                                <div class="row">
                                    <div class="col-xl-8 col-lg-9">
                                        <div class="form-group row">
                                            <label for="inputCompanyName"
                                                class="col-lg-3 col-form-label text-center font-weight-normal">Company
                                                name</label>
                                            <div class="col-lg-9">
                                                <input type="text" name="company_name"
                                                    value="{{ !empty($req_arr['company_name']) ? $req_arr['company_name'] : '' }}"
                                                    class="form-control" id="inputCompanyName" placeholder="Company name">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputAttendantsName"
                                                class="col-lg-3 col-form-label text-center font-weight-normal">Attendants
                                                Name</label>
                                            <div class="col-lg-9">
                                                <input type="text" name="company_att_name"
                                                    value="{{ !empty($req_arr['company_att_name']) ? $req_arr['company_att_name'] : '' }}"
                                                    class="form-control" id="inputAttendantsName"
                                                    placeholder="Attendants name">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-8 col-lg-9">
                                        <div class="form-group row">
                                            <label for="inputKeyword"
                                                class="col-lg-3 col-form-label text-center font-weight-normal">Keyword</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control"
                                                    value="{{ !empty($req_arr['company_keyword']) ? $req_arr['company_keyword'] : '' }}"
                                                    name="company_keyword" id="inputKeyword" placeholder="Keyword">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3">
                                        <div class="btn-search">
                                            <button class="btn btn-default sty-search" type="submit"><i class="fa fa-search"
                                                    style="margin-right:5px;"></i>Search</button>
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
            <a href="{{ route('admin.company.create') }}" class="btn btn-danger  pt-1 pb-1 pl-5 pr-5"><i
                    class="nav-icon fa fa-plus-circle"></i> Add</a>
        </div>
        <x-alert />
        <div class="card">
            <div class="card-body p-0 card-list-items">
                <div class="wrap_tbl_ad">
                    <table class="table table-bordered " style="min-width: 500px;">
                        <thead>
                            <tr>
                                <th rowspan="2" class="align-middle" style="width: 10px;">No</th>
                                <th colspan="3" class="miss-border">Company Information</th>
                                <th colspan="2" class="miss-border">Attendants Information</th>
                                <th rowspan="2" class="align-middle" style="width: 150px;">Actions</th>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Tell</th>
                                <th>Name</th>
                                <th>Department</th>
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
                                                    {{--
                                                    {{ route('user.draft.edit.show', $application_draft->id) }}
                                                    --}}
                                                </x-slot>
                                                <x-slot name="deleteUrl">
                                                    {{ route('admin.company.index') }}
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
