@extends('layouts.master')

@section('css')

@endsection

@section('content')
<div class="col-lg-9">
	<div class="card">
		<div class="card-body">
			<div class="search-content">
				<form action="{{ route('admin.user.store') }}" method="POST">
					@csrf
					{{-- Location --}}
					<div class="form-group row">
						<label for="location"
							class="col-lg-3 col-form-label text-center">{{ __('validation.attributes.location') }}</label>
						<div class="col-lg-9 text-lg-left text-center">
							<fieldset id="location" class="@error('location') form-control is-invalid @enderror">
								@foreach ($data['locations'] as $key => $value)
								<label class="radio-inline com_title col-form-label">
									<input type="radio" name="location" value="{{ $value }}"
										{{ old('location') == $value ? 'checked' : '' }}>
									{{ __('label.'.$key) }}
								</label>
								@endforeach
							</fieldset>
							@error('location')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
							@enderror
						</div>
					</div>
					{{-- Department --}}
					<div class="form-group row">
						<label for="department"
							class="col-lg-3 col-form-label text-center">{{ __('validation.attributes.department') }}</label>
						<div class="col-lg-9">
							<select id="department" name="department"
								class="form-control @error('department') is-invalid @enderror">
								<option value='' selected>{{ __('label.select') }}</option>
								@foreach ($data['departments'] as $item)
								<option value="{{ $item->id }}" {{ old('department') == $item->id ? 'selected' : '' }}>
									{{ $item->name }}</option>
								@endforeach
							</select>
							@error('department')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
							@enderror
						</div>
					</div>
					{{-- Name --}}
					<div class="form-group row">
						<label for="name"
							class="col-lg-3 col-form-label text-center">{{ __('validation.attributes.name') }}</label>
						<div class="col-lg-9">
							<input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
								name="name" value="{{ old('name') }}" placeholder="{{ __('validation.attributes.name') }}" autocomplete="off" autofocus>
							@error('name')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
							@enderror
						</div>
					</div>
					{{-- Role --}}
					<div class="form-group row">
						<label for="role"
							class="col-lg-3 col-form-label text-center">{{ __('validation.attributes.role') }}</label>
						<div class="col-lg-9">
							<div class="col-md-6" style="padding-left:0">
								<select id="role" name="role" class="form-control @error('role') is-invalid @enderror">
									<option value='' selected>{{ __('label.select') }}</option>
									@foreach ($data['roles'] as $key => $value)
									<option value="{{ $value }}" {{ old('role') == $value ? 'selected' : '' }}>
										{{ $key }}</option>
									@endforeach
								</select>
								@error('role')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
						</div>
					</div>
					{{-- Phone --}}
					<div class="form-group row">
						<label for="phone"
							class="col-lg-3 col-form-label text-center">{{ __('validation.attributes.phone') }}</label>
						<div class="col-lg-9">
							<input id="phone" name="phone" type="text"
								class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}"
								placeholder="{{ __('validation.attributes.phone') }}" autocomplete="off" autofocus>
							@error('phone')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
							@enderror
						</div>
					</div>
					{{-- Email --}}
					<div class="form-group row">
						<label for="email"
							class="col-lg-3 col-form-label text-center">{{ __('validation.attributes.email') }}</label>
						<div class="col-lg-9">
							<input id="email" type="text" class="form-control @error('email') is-invalid @enderror"
								name="email" value="{{ old('email') }}" autofocus
									placeholder="{{ __('validation.attributes.email') }}" autocomplete="off">

							@error('email')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
							@enderror
						</div>
					</div>
					{{-- Approval --}}
					<div class="form-group row">
						<label for="approval"
							class="col-lg-3 col-form-label text-center">{{ __('validation.attributes.approval') }}</label>
						<div class="col-lg-9 text-lg-left text-center">
							<fieldset id="approval" class="@error('approval') form-control is-invalid @enderror">
								@foreach ($data['approvals'] as $key => $value)
								<label class="radio-inline com_title col-form-label">
									<input type="radio" name="approval" value="{{ $value }}"
										{{ old('approval') == $value ? 'checked' : '' }}>
									{{ __('label.'.$key) }}
								</label>
								@endforeach
							</fieldset>

							@error('approval')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
							@enderror
						</div>
					</div>
					{{-- Memo --}}
					<div class="form-group row">
						<label for="memo"
							class="col-lg-3 col-form-label text-center d-flex align-items-center justify-content-center">{{ __('validation.attributes.memo') }}</label>
						<div class="col-lg-9">
							<textarea id="memo" name="memo" rows="4"
								class="form-control @error('memo') is-invalid @enderror"
									placeholder="{{ __('validation.attributes.memo') }}" autocomplete="off" autofocus>{{ old('memo') }}</textarea>
							@error('memo')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
							@enderror
						</div>
					</div>
					<br>
					<div class="mt-5 mb-5 text-center">
						{{-- Submit --}}
						<button type="submit" class="btn btn-danger pt-1 pb-1 mr-4 col-5 col-sm-2 col-md-4 col-lg-2">
							<i class="nav-icon far fa-check-circle"></i> {{ __('label.button.register') }}</button>
						{{-- Cancel --}}
						<a class="btn btn-outline-dark pt-1 pb-1 col-5 col-sm-2 col-md-4 col-lg-2"
							href="{{ route('admin.user.index') }}">
							<i class="nav-icon far fa-times-circle"></i> {{__('label.button.cancel')}}</a>
					</div>
			</div>

			</form>
		</div>
	</div>
</div>
@endsection