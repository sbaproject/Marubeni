@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="css/admin/admin_102_shain_ichiran.css">
@endsection

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-lg-6">
                <h1>{{ __('label.search') }}</h1>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-8 col-lg-10">
            <div class="card">
                <div class="card-body">
                    <div class="search-content">
                        <x-alert />
                        <form action="{{ route('admin.user.index') }}" method="GET">
                            <div class="row">
                                <div class="col-lg-10 col-xl-9">
                                    {{-- Location --}}
                                    <div class="form-group row">
                                        <label for="location"
                                            class="col-lg-3 col-form-label text-center font-weight-normal">{{ __('validation.attributes.location') }}</label>
                                        <div class="col-lg-9">
                                            <select id="location" name="location" class="form-control">
                                                <option value='' selected>{{ __('label.select') }}</option>
                                                @foreach ($locations as $key => $value)
                                                <option value="{{ $value }}"
                                                    @isset($conditions['location']) @if ($conditions['location']==$value) selected @endif @endisset>
                                                    {{ __('label.'.$key) }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    {{-- Employee No --}}
                                    <div class="form-group row">
                                        <label for="user_no"
                                            class="col-lg-3 col-form-label text-center font-weight-normal">
                                            {{ __('validation.attributes.user_no') }}
                                        </label>
                                        <div class="col-lg-9">
                                            <input type="text" id="user_no" name="user_no" class="form-control" placeholder="{{ __('validation.attributes.user_no') }}"
                                                value="@isset($conditions['user_no']){{ $conditions['user_no'] }}@endisset">
                                        </div>
                                    </div>
                                    {{-- Department --}}
                                    <div class="form-group row">
                                        <label for="department"
                                            class="col-lg-3 col-form-label text-center font-weight-normal">
                                            {{ __('validation.attributes.department') }}
                                        </label>
                                        <div class="col-lg-9">
                                        <select id="department" name="department" class="form-control">
                                            <option value='' selected>{{ __('label.select') }}</option>
                                            @foreach ($departments as $item)
                                            <option value="{{ $item->id }}"
                                                @isset($conditions['department']) @if ($conditions['department'] == $item->id) selected @endif @endisset>
                                                {{ $item->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-10 col-xl-9">
                                    {{-- Name --}}
                                    <div class="form-group row">
                                        <label for="name"
                                            class="col-lg-3 col-form-label text-center font-weight-normal">
                                            {{ __('validation.attributes.name') }}
                                        </label>
                                        <div class="col-lg-9">
                                            <input id="name" name="name" type="text" class="form-control" placeholder="{{ __('validation.attributes.name') }}"
                                                value="@isset($conditions['name']){{ $conditions['name'] }}@endisset">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-xl-3">
                                    {{-- Submit --}}
                                    <div class="form-group row">
                                        <div class="col-lg-10">
                                            <button type="submit" class="btn btn-primary search_bt">
                                                <i class="nav-icon fas fa-search"></i>
                                                {{ __('label.search') }}
                                            </button>
                                        </div>
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
    {{-- Add new button --}}
    <div class="button_wrap">
        <a class="btn btn-danger pt-1 pb-1 pl-5 pr-5"
            href="{{ route('admin.user.add.create') }}">
            <i class="nav-icon fa fa-plus-circle"></i>
            {{ __('label.addnew') }}
        </a>
    </div>
    <div class="card">
        <div class="card-body p-0 ">
            <div class="wrap_tbl_ad">
                {{-- List Users --}}
                <div class="content_roll">
                    <table class="table table-bordered " style="min-width: 500px;">
                        <thead>
                            <tr>
                                <th style="width: 100px">{{ __('label._no_') }}</th>
                                <th>{{ __('validation.attributes.department') }}</th>
                                <th>{{ __('validation.attributes.name') }}</th>
                                <th style="width: 150px">{{ __('label.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->user_no }}</td>
                                <td>{{ $user->department->name }}</td>
                                <td>{{ $user->name }}</td>
                                <td>
                                    {{-- using action component with if stament --}}
                                    <x-action>
                                        <x-slot name="editUrl">
                                            {{ route('admin.user.edit.show', $user->id) }}
                                        </x-slot>
                                        @if ($user->id !== Auth::user()->id)
                                        <x-slot name="deleteUrl">
                                            {{ route('admin.user.delete', $user->id) }}
                                        </x-slot>
                                        @endif
                                    </x-action>
                                    {{-- using action component with sort tag --}}
                                    {{-- <x-action
                                        edit-url="{{ route('admin.user.edit.show', $user->id) }}"
                                        delete-url="{{ route('admin.user.delete', $user->id) }}" /> --}}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- paginator --}}
                {{$users->withQueryString()->links('paginator')}}
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection