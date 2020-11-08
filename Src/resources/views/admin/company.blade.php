@extends('layouts.master')
@section('title','ADMIN_COMPANY')
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
                <div class="row">
                    <div class="col-xl-8 col-lg-10">
                        <div class="card">
                            <div class="card-body">
                                <div class="search-content">
                                    <form>
                                        <div class="row">
                                            <div class="col-xl-8 col-lg-9">
                                                <div class="form-group row">
                                                    <label for="inputCompanyName"
                                                        class="col-lg-3 col-form-label text-center font-weight-normal">Company
                                                        name</label>
                                                    <div class="col-lg-9">
                                                        <input type="text" class="form-control" id="inputCompanyName"
                                                            placeholder="Company name">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="inputAttendantsName"
                                                        class="col-lg-3 col-form-label text-center font-weight-normal">Attendants
                                                        Name</label>
                                                    <div class="col-lg-9">
                                                        <input type="text" class="form-control" id="inputAttendantsName"
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
                                                        <input type="text" class="form-control" id="inputKeyword"
                                                            placeholder="Keyword">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-lg-3">
                                                <div class="btn-search">
                                                    <button class="btn btn-default sty-search" type="submit"><i
                                                            class="fa fa-search"
                                                            style="margin-right:5px;"></i>Search</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Main content -->
            <section class="content">
                <div class="button_wrap">
                    <button type="button" class="btn btn-danger  pt-1 pb-1 pl-5 pr-5"><i
                            class="nav-icon fa fa-plus-circle"></i> New</button>
                </div>
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
                                    <tr>
                                        <td>001</td>
                                        <td>TEXT TEXT</td>
                                        <td>TEXT TEXT</td>
                                        <td>00000000</td>
                                        <td>Ada Lovelace</td>
                                        <td>Sales</td>
                                        <td>
                                            <ul class="list-inline m-0">
                                                <li class="list-inline-item">
                                                    <a href="#"><button class="btn btn-success btn-sm rounded-0"
                                                            type="button" data-toggle="tooltip" data-placement="top"
                                                            title="" data-original-title="Edit"><i
                                                                class="fa fa-edit"></i></button></a>
                                                </li>
                                                <li class="list-inline-item">
                                                    <button class="btn btn-danger btn-sm rounded-0" type="button"
                                                        data-toggle="tooltip" data-placement="top" title=""
                                                        data-original-title="Delete"><i
                                                            class="fa fa-trash"></i></button>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

            </section>
            <!-- /.content -->
@endsection