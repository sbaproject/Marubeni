@extends('layouts.master')
@section('title', 'User-Draft')
@section('content')
    <section class="content">
        <div class="card">
            <x-alert />
            <div class="card-body p-0 card-list-items">
                <div class="wrap_tbl_ad">
                    <table class="table table-bordered" style="min-width: 500px;">
                        <thead>
                            <tr>
                                <th style="width: 10px">No</th>
                                <th>Application Name</th>
                                <th style="width: 150px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($list_application_draft))
                                @php
                                $page = request()->get("page");
                                if ($page)
                                $index = $page * 10 - 9;
                                else
                                $index = 1;
                                @endphp
                                @foreach ($list_application_draft as $application_draft)
                                    @php
                                    if ($index < 10) $index='0' . $index @endphp <tr>
                                        <td>{{ $index }}</td>
                                        <td>
                                            {{ !empty($application_draft->form_id) ? $application_draft->Form->name : '' }}
                                        </td>
                                        <td>
                                            {{-- using action component with if stament
                                            --}}
                                            <x-action>
                                                <x-slot name="editUrl">
                                                    {{-- {{ route('user.draft.edit.show', $application_draft->id) }} --}}
                                                </x-slot>
                                                <x-slot name="deleteUrl">
                                                    {{ route('user.draft.delete', $application_draft->id) }}
                                                </x-slot>
                                            </x-action>
                                            {{-- using action component with sort tag
                                            --}}
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
            @if (isset($list_application_draft))
                {{ $list_application_draft->withQueryString()->links('paginator') }}
            @endif
        </div>
        <!-- /.card -->
    </section>
@endsection
