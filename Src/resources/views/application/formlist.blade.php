@extends('layouts.master')

@section('title')
	{{ __('label.application_list') }}
@endsection

@section('css')
	<link rel="stylesheet" href="css/user/03_application_list.css">
@endsection

@section('content')
<section class="content">
	<h4 class="mb-2">{{ __('label.application_list') }}</h4>
	<div class="card">
		<div class="card-body p-4">
			<div class="container">
				<x-alert />
				<div class="row">
					<div class="col-sm-12 col-md-4 btn">
						<a href="{{ route('user.entertainment.create') }}">
							<div class="application-item">
								{{ __('label.form.entertainment') }}
							</div>
						</a>
					</div>
					<div class="col-sm-12 col-md-4 btn">
						<a href="{{ route('user.business.create') }}">
							<div class="application-item">
								{{ __('label.form.biz_trip') }}
							</div>
						</a>
					</div>
					<div class="col-sm-12 col-md-4 btn">
						<a href="{{ route('user.leave.create') }}">
							<div class="application-item">
								{{ __('label.form.leave') }}
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>
		<!-- /.card-body -->
	</div>
	<!-- /.card -->

</section>
@endsection