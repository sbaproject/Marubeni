@extends('layouts.master')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">{{ __('label.update') }}</div>

				<div class="card-body">

					<x-alert />

					<form method="POST" action="{{ route('changepass.update') }}">
						@csrf
						@method('PUT')
						<div class="form-group row">
							<label for="name" class="col-md-4 col-form-label text-md-right">{{ __('validation.attributes.current_password') }}</label>

							<div class="col-md-6">
								<input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror"
									name="current_password" autofocus>

								@error('current_password')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<label for="new_password" class="col-md-4 col-form-label text-md-right">{{ __('validation.attributes.new_password') }}</label>

							<div class="col-md-6">
								<input id="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror"
									name="new_password" autofocus>

								@error('new_password')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
						</div>

						<div class="form-group row">
							<label for="new_password_confirmation" class="col-md-4 col-form-label text-md-right">{{ __('validation.attributes.confirm_new_password') }}</label>

							<div class="col-md-6">
								<input id="new_password_confirmation" type="password" class="form-control"
									name="new_password_confirmation" autofocus>
							</div>
						</div>

						<div class="form-group row mb-0">
							<div class="col-md-6 offset-md-4">
								<button type="submit" class="btn btn-primary">
									{{ __('label.update') }}
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection