resources/views/media/test2.blade.php@extends('layouts.master')

@section('title')
test media
@endsection

@section('css')

@endsection

@section('js')

@endsection

@section('content-header')
test media 2
@endsection

@section('content-breadcrumb')

@endsection

@section('content')
<iframe src="{{ route('media.test2') }}" frameborder="0"></iframe>
@endsection