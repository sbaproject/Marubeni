@extends('layouts.master')
@section('title')
{{ __('label.menu.draft') }}
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4 style="font-weight: 600;">{{ Str::upper(__('label.draft.list')) }}</h4>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="invoice p-3 mb-3">
            {{-- <x-alert /> --}}
            <div class="card-body p-0 card-list-items">
                <div class="wrap_tbl_ad">
                    <table class="table table-bordered table-hover" style="min-width: 500px;">
                        <thead>
                            <tr>
                                <th style="width: 170px">{{ __('label.draft.no') }}</th>
                                <th>{{ __('label.draft.application_name') }}</th>
                                <th>{{ __('label.draft.date_create') }}</th>
                                <th style="width: 150px">{{ __('label.draft.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($list_application_draft))
                                @foreach ($list_application_draft as $application_draft)
                                    <tr>
                                        <td>{{ !empty($application_draft->application_no) ? $application_draft->application_no : '' }}</td>
                                        <td>
                                            {{ !empty($application_draft->form_id) ? $application_draft->Form->name : '' }}
                                        </td>
                                        <td>
                                            {{ !empty($application_draft->created_at) ? \Carbon\Carbon::parse($application_draft->created_at)->format('d/m/Y') : '' }}
                                        </td>
                                        <td>
                                            <x-action>
                                                <x-slot name="editUrl">
                                                    {{ Common::getRouteEditApplication($application_draft->id, $application_draft->form_id) }}
                                                </x-slot>
                                                <x-slot name="deleteUrl">
                                                    {{ route('user.draft.delete', $application_draft->id) }}
                                                </x-slot>
                                            </x-action>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.card-body -->
            @if (isset($list_application_draft))
                {{ $list_application_draft->withQueryString()->links('paginator') }}
            @endif
        </div>
        <!-- /.card -->
    </section>
@endsection
