@extends('layouts.master')

@section('css')

@endsection

@section('content')
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<div class="main-top">
					<h4 class="main-header-text" style="font-weight: 500;">{{ __('label.change_pass') }}</h4>
				</div>
				<form method="POST" action="{{ route('changepass.update') }}">
					<div class="invoice p-3 mb-3 card-company">
						<div class="card-body">
							<x-alert />
							@csrf
							<div class="form-group row ">
								<label for="name"
									class="col-lg-2 col-form-label text-left">{{ __('validation.attributes.current_password') }}</label>
								<div class="col-lg-4">
									<input id="current_password" type="password" placeholder="{{ __('validation.attributes.current_password') }}"
										class="form-control @error('current_password') is-invalid @enderror"
										name="current_password" autofocus autocomplete="off">

									@error('current_password')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
									@enderror
								</div>
							</div>
							<hr>
							<div class="form-group row ">
								<label for="new_password"
									class="col-lg-2 col-form-label text-left">{{ __('validation.attributes.new_password') }}</label>
								<div class="col-lg-4">
									<input id="new_password" type="password" placeholder="{{ __('validation.attributes.new_password') }}"
										class="form-control @error('new_password') is-invalid @enderror"
										name="new_password" autofocus autocomplete="off">

									@error('new_password')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
									@enderror
								</div>
							</div>
							<div class="form-group row ">
								<label for="confirm_new_password"
									class="col-lg-2 col-form-label text-left">{{ __('validation.attributes.confirm_new_password') }}</label>
								<div class="col-lg-4">
									<input id="confirm_new_password" type="password" class="form-control @error('confirm_new_password') is-invalid @enderror"
										name="confirm_new_password" autofocus autocomplete="off" placeholder="{{ __('validation.attributes.confirm_new_password') }}">

										@error('confirm_new_password')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
										@enderror
										<br>
									<button type="submit" class="btn bg-gradient-success">
										<i class="nav-icon far fa-check-circle"></i>
										{{ __('label.button.change') }}
									</button>
								</div>
							</div>

						</div>
						<!-- /.card-body -->
					</div>
					{{-- <br>
					<div class="row">
						<div class="col-lg-9">
							<button type="submit"
								class="btn bg-gradient-success">
								<i class="nav-icon far fa-check-circle"></i>
								{{ __('label.button.change') }}
							</button>
						</div>
					</div>
					<br> --}}
				</form>
			</div>
		</div>
	</div>
	<!-- /.card -->
</section>
@endsection