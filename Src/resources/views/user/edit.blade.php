@extends('layouts.master')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">{{ __('label.update') }}</div>

				<div class="card-body">

					<x-alert />

					<form method="POST" action="{{ Request::url() }}">
						@csrf
						@method('PUT')
						{{-- ID --}}
						<div class="form-group row">
							<label for="user_no" class="col-md-4 col-form-label text-md-right">{{ __('validation.attributes.user_no') }}</label>
							<div class="col-md-6">
								<input id="user_no" type="text" class="form-control" readonly
									value="{{ str_pad($data['user']->id, config('const.num_fillzero'), "0", STR_PAD_LEFT) }}">
							</div>
						</div>
						{{-- Location --}}
						<div class="form-group row">
							<label for="location"
								class="col-md-4 col-form-label text-md-right">{{ __('validation.attributes.location') }}</label>
							<div class="col-md-6">
								<fieldset id="location" class="@error('location') form-control is-invalid @enderror">
									@foreach ($data['locations'] as $key => $value)
									<label class="radio-inline com_title col-form-label">
										<input type="radio" name="location" value="{{ $value }}"
											{{ $data['user']->location == $value ? 'checked' : '' }}>
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
								class="col-md-4 col-form-label text-md-right">{{ __('validation.attributes.department') }}</label>
							<div class="col-md-6">
								<select id="department" name="department" class="form-control @error('department') is-invalid @enderror">
									<option value='' selected>{{ __('label.select') }}</option>
									@foreach ($data['departments'] as $item)
									<option value="{{ $item->id }}" {{ $data['user']->department_id == $item->id ? 'selected' : '' }}>{{ $item->name }}
									</option>
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
							<label for="name" class="col-md-4 col-form-label text-md-right">{{ __('validation.attributes.name') }}</label>
							<div class="col-md-6">
								<input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
									value="{{ $data['user']->name }}" autofocus>
								@error('name')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
						</div>
						{{-- Role --}}
						<div class="form-group row">
							<label for="role" class="col-md-4 col-form-label text-md-right">{{ __('validation.attributes.role') }}</label>
							<div class="col-md-6">
								<select id="role" name="role" class="form-control @error('role') is-invalid @enderror">
									<option value='' selected>{{ __('label.select') }}</option>
									@foreach ($data['roles'] as $key => $value)
									<option value="{{ $value }}" {{ $data['user']->role == $value ? 'selected' : '' }}>{{ $key }}</option>
									@endforeach
								</select>
								@error('role')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
						</div>
						{{-- Phone --}}
						<div class="form-group row">
							<label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('validation.attributes.phone') }}</label>
							<div class="col-md-6">
								<input id="phone" name="phone" type="text" class="form-control @error('phone') is-invalid @enderror"
									value="{{ $data['user']->phone }}" autofocus>
								@error('phone')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
						</div>
						{{-- Email --}}
						<div class="form-group row">
							<label for="email" class="col-md-4 col-form-label text-md-right">{{ __('validation.attributes.email') }}</label>
							<div class="col-md-6">
								<input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email"
									value="{{ $data['user']->email }}" autocomplete="email">
						
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
								class="col-md-4 col-form-label text-md-right">{{ __('validation.attributes.approval') }}</label>
							<div class="col-md-6">
								<fieldset id="approval" class="@error('approval') form-control is-invalid @enderror">
									@foreach ($data['approvals'] as $key => $value)
									<label class="radio-inline com_title col-form-label">
										<input type="radio" name="approval" value="{{ $value }}"
											{{ $data['user']->approval == $value ? 'checked' : '' }}>
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
							<label for="memo" class="col-md-4 col-form-label text-md-right">{{ __('validation.attributes.memo') }}</label>
							<div class="col-md-6">
								<textarea id="memo" name="memo" rows="4"
									class="form-control @error('memo') is-invalid @enderror">{{ $data['user']->memo }}</textarea>
								@error('memo')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
						</div>
						{{-- Submit --}}
						<div class="form-group row mb-0">
							<div class="col-md-6 offset-md-4">
								<button type="submit" class="btn btn-primary">
									{{ __('label.update') }}
								</button>
								{{-- Cancel --}}
								<a href="{{ route('admin.user.list') }}" class="btn btn-outline-dark">
									{{__('label.cancel')}}
								</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection