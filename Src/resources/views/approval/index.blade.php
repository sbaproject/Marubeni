@extends('layouts.master')
@section('title')
{{ Str::upper(__('label.pending_approval')) }}
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
                <h4 style="font-weight: 600;">{{ Str::upper(__('label.button.search')) }}</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-8 col-lg-10">
            <div class="card">
                <div class="card-body">
                    <div class="search-content">
                        <form action="{{ route('user.approval.index') }}" method="GET">
                            @csrf
                            <div class="row">
                                <div class="col-xl-8 col-lg-9">
                                    <div class="form-group row">
                                        <label for="shourui"
                                            class="col-sm-4 col-form-label text-center font-weight-normal">{{ __('label.status.application_type') }}</label>
                                        <div class="col-sm-8">
                                            <select id="application_type" name="application_type" class="form-control">
                                                <option value="" selected>{{ __('label.select') }}</option>
                                                @foreach (config('const.form') as $key => $value)
                                                    <option value={{ $value }} @if(isset($inputs['application_type']) && $inputs['application_type'] == $value) selected @endif>
                                                        {{ __('label.form.'.$key) }}
                                                    </option>
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
                                            class="col-sm-4 col-form-label text-center font-weight-normal">{{ __('label.keyword') }}</label>
                                        <div class="col-sm-8">
                                            <input type="text" id="keyword" name="keyword" class="form-control" autocomplete="off"
                                                value="{{ isset($inputs['keyword']) ? $inputs['keyword'] : '' }}"
                                                placeholder="{{ __('label.search_by_app_no_applicant') }}">
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
            style="margin-right: 5px;"></i>{{ Str::upper(__('label.pending_approval')) }}</h4>
    <div class="card">
        <div class="card-body p-0 card-list-items">
            <table class="table table-bordered">
                <thead>
                    <tr class="list-title">
                        <th>
                            {{-- {{ __('label.application_no') }} --}}
                            {!! $sort->titles['application_no'] !!}
                        </th>
                        <th>{{ __('label.status.application_type') }}</th>
                        <th>{{ __('label.status.apply_date') }}</th>
                        <th>{{ __('label.status.next_approver') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $item)
                    <tr class="list-content">
                        <td>{{ $item->application_no }}</td>
                        <td>{{ $item->application_type }}</td>
                        <td>
                            @if(config('app.locale') == 'en')
                                {{ date('Y/m/d', strtotime($item->apply_date)) }}
                            @else
                                {{ date('d/m/Y', strtotime($item->apply_date)) }}
                            @endif
                        </td>
                        <td>{{ $item->next_approver }}</td>
                        <td>
                            <a class="btn btn-details" href="{{ route('user.approval.show',$item->application_id) }}">
                                {{ __('label.status.view_details') }}
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