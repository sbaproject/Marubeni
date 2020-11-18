@extends('layouts.master')
@section('title')
Approve List
@endsection
@section('css')
<link rel="stylesheet" href="css/user/08_waiting_approval_list.css">
@endsection
@section('js')

@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 style="font-weight: 600;">SEARCH</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-8 col-lg-10">
            <div class="card">
                <div class="card-body">
                    <div class="search-content">
                        <form action="{{ route('user.approve.list') }}" method="GET">
                            @csrf
                            <div class="row">
                                <div class="col-xl-8 col-lg-9">
                                    <div class="form-group row">
                                        <label for="shourui"
                                            class="col-sm-4 col-form-label text-center font-weight-normal">Application Type</label>
                                        <div class="col-sm-8">
                                            <select id="application_type" name="application_type" class="form-control">
                                                <option value="" selected>{{ __('label.select') }}</option>
                                                @foreach (config('const.form') as $key => $value)
                                                    <option value={{ $value }}>{{ __('label.form.'.$key) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-8 col-lg-9">
                                    <div class="form-group row">
                                        <label for="inputKeyword"
                                            class="col-sm-4 col-form-label text-center font-weight-normal">Keyword</label>
                                        <div class="col-sm-8">
                                            <input type="text" id="keyword" name="keyword" class="form-control" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3">
                                    <div class="btn-search">
                                        <button class="btn btn-default sty-search" type="submit">
                                            <i class="fa fa-search" style="margin-right:5px;"></i>
                                            {{ __('label.button.search') }}
                                        </button>
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
    <h4 class="mb-2" style="font-weight: 600;"><i class="nav-icon fas fa-file-alt" aria-hidden="true"
            style="margin-right: 5px;"></i>PENDING APPROVAL DOCUMENT</h4>
    <div class="card">
        <div class="card-body p-0 card-list-items">
            <table class="table">
                <thead>
                    <tr class="list-title">
                        <th>Application No</th>
                        <th>Application type</th>
                        <th>Apply Date</th>
                        <th>Next Approver</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $item)
                    <tr class="list-content">
                        <td>NO-TM-0000</td>
                        <td>交際費申請</td>
                        <td>2020/10/22</td>
                        <td>text text</td>
                        <td>
                            <a class="btn btn-details" href="/pages/examples/09_application_info.html">
                                View Details
                                <i class="fas fa-angle-right" style="margin-left: 5px;"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- paginator --}}
        {{$data->withQueryString()->links('paginator')}}
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</section>
@endsection