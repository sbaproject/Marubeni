@extends('layouts.master')

@section('title')
{{ __('label.menu.employee_setting') }}
@endsection

@section('css')
@endsection

@section('content-header')
{{ __('label.menu.employee_setting') }}
@endsection

@section('content-breadcrumb')
<li class="breadcrumb-item active">{{ __('label.menu.settings') }}</li>
<li class="breadcrumb-item active">{{ __('label.menu.employee_setting') }}</li>
@endsection

@section('content')
{{-- <x-popup-confirm /> --}}

<!-- Content Header (Page header) -->
<section class="content mb-3">
    {{-- <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-lg-6">
                <h1>{{ __('label.menu.employee_setting') }}</h1>
            </div>
        </div>
    </div> --}}
    <div class="row">
        <div class="col-xl-8 col-lg-10">
            <div class="invoice">
                <div class="card-header">
                    <h3 class="card-title">{{ __('label.button.search') }}</h3>
                </div>
                <div class="card-body">
                    <div class="search-content">
                        {{-- <x-alert /> --}}
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
                                    {{-- Name --}}
                                    <div class="form-group row">
                                        <label for="name" class="col-lg-3 col-form-label text-center font-weight-normal">
                                            {{ __('validation.attributes.user.name') }}
                                        </label>
                                        <div class="col-lg-9">
                                            <input id="name" name="name" type="text" class="form-control"
                                                placeholder="{{ __('validation.attributes.user.name') }}"
                                                value="@isset($conditions['name']){{ $conditions['name'] }}@endisset">
                                        </div>
                                    </div>
                                    {{-- Only show deleted user list --}}
                                    <div class="form-group row">
                                        <label for="show_del_user" class="col-lg-3 col-form-label text-center font-weight-normal">
                                            {{ __('label.only_show_deleted_users') }}
                                        </label>
                                        <div class="col-lg-9" style="padding-top: 10px">
                                            <input type="checkbox" id="show_del_user" name="show_del_user"
                                                @if(isset($conditions['show_del_user']) && $conditions['show_del_user'] == "on") checked @endif>
                                            <label class="form-check-label" for="show_del_user">{{ __('label.on') }}</label>
                                        </div>
                                    </div>
                                    {{-- Search button --}}
                                    <div class="form-group row">
                                        <label for="show_del_user" class="col-lg-3 col-form-label text-center font-weight-normal">
                                            {{-- {{ __('validation.attributes.user.name') }} --}}
                                        </label>
                                        <div class="col-lg-9">
                                            <button type="submit" class="btn bg-gradient-primary">
                                                <i class="nav-icon fas fa-search"></i>
                                                {{ __('label.button.search') }}
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
        <a class="btn bg-gradient-danger"
            href="{{ route('admin.user.create') }}">
            <i class="nav-icon fa fa-plus-circle"></i>
            {{ __('label.button.addnew') }}
        </a>
    </div>
    <div class="invoice p-3">
        {{-- paginator --}}
        <div class="card-body p-0 card-list-items">
            {{-- List Users --}}
            <table class="table table-bordered table-hover " style="min-width: 500px;">
                <thead>
                    <tr>
                        <th style="width: 100px" class="sortable {{ $sortable->headers['user_no']->activeCls }}">
                            {{-- {{ __('label._no_') }} --}}
                            {!! $sortable->headers['user_no']->title !!}
                        </th>
                        <th class="sortable {{ $sortable->headers['department_name']->activeCls }}">
                            {{-- {{ __('validation.attributes.department') }} --}}
                            {!! $sortable->headers['department_name']->title !!}
                        </th>
                        <th class="sortable {{ $sortable->headers['user_name']->activeCls }}">
                            {{-- {{ __('validation.attributes.user.name') }} --}}
                            {!! $sortable->headers['user_name']->title !!}
                        </th>
                        <th style="width: 150px">
                            {{-- {{ __('label.action') }} --}}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->user_no }}</td>
                        <td>{{ $user->department_name }}</td>
                        <td>{{ $user->user_name }}</td>
                        <td>
                            {{-- using action component with if stament --}}
                            {{-- Edit & Delete --}}
                            <x-action>
                                @if(!isset($conditions['show_del_user']) || $conditions['show_del_user'] != "on")
                                    <x-slot name="editUrl">
                                        {{ route('admin.user.show', $user->user_id) }}
                                    </x-slot>
                                @endif
                                @if ($user->user_id !== Auth::user()->id)
                                    @if(!isset($conditions['show_del_user']) || $conditions['show_del_user'] != "on")
                                        <x-slot name="deleteUrl">
                                            {{ route('admin.user.delete', $user->user_id) }}
                                        </x-slot>
                                    @endif
                                @endif
                            </x-action>
                            {{-- Restore --}}
                            @if(isset($conditions['show_del_user']) && $conditions['show_del_user'] == "on")
                                <ul class="list-inline m-0">
                                    <li class="list-inline-item">
                                        <form action="{{ route('admin.user.restore', $user->user_id) }}" method="POST">
                                            @csrf
                                            <button type="button" class="btn bg-gradient-primary btn-sm"
                                                data-toggle="tooltip" title="{{ __('label.button.restore') }}"
                                                data-toggle="modal" data-target="#popup-confirm">
                                                <i class="fa fa-undo"></i>
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            @endif
                            {{-- using action component with sort tag --}}
                            {{-- <x-action
                                    edit-url="{{ route('admin.user.show', $user->id) }}"
                                    delete-url="{{ route('admin.user.delete', $user->id) }}" /> --}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- paginator --}}
        {{$users->withQueryString()->links('paginator')}}
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</section>

<!-- /.content -->
@endsection