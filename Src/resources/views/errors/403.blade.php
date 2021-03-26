@extends('layouts.unauth_master')
@section('title')
{{ __('msg.page_error.404') }}
@endsection
@section('content')
<div class="card" style="text-align: center">
    <p>
        <div class="code-state">403</div>
        @if (session('msg-abort'))
            <div>{{ session('msg-abort') }}</div>
        @else
            <div>{{ __('msg.page_error.403') }}</div>
        @endif
        <div>
            <a href="{{ Common::getHomeUrl() }}">{{ __('msg.page_error.go_home') }}</a>
        </div>
    </p>
</div>
@endsection