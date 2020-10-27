@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">User list</div>
				<div class="card-body">
					<form action="{{ route('user.list') }}" method="GET">
						{{-- Location --}}
						<div class="form-group row">
							<label for="location"
								class="col-md-4 col-form-label text-md-right">{{ __('validation.attributes.location') }}</label>
							<div class="col-md-6">
								<select id="location" name="location" class="form-control">
									<option value='' selected>{{ __('label.select') }}</option>
									@foreach ($locations as $key => $value)
									<option value="{{ $value }}"
										@isset($conditions['location']) @if ($conditions['location'] == $value) selected @endif @endisset>
										{{ __('label.'.$key) }}
									</option>
									@endforeach
								</select>
							</div>
						</div>
						{{-- Employee No --}}
						<div class="form-group row">
							<label for="user_no" class="col-md-4 col-form-label text-md-right">{{ __('validation.attributes.user_no') }}</label>
							<div class="col-md-6">
								<input id="user_no" type="text" class="form-control" name="user_no"
									value="@isset($conditions['user_no']){{ $conditions['user_no'] }}@endisset">
							</div>
						</div>
						{{-- Department --}}
						<div class="form-group row">
							<label for="department"
								class="col-md-4 col-form-label text-md-right">{{ __('validation.attributes.department') }}</label>
							<div class="col-md-6">
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
							<label for="name" class="col-md-4 col-form-label text-md-right">{{ __('validation.attributes.name') }}</label>
							<div class="col-md-6">
								<input id="name" type="text" class="form-control" name="name"
									value="@isset($conditions['name']){{ $conditions['name'] }}@endisset">
							</div>
						</div>
						{{-- Submit --}}
						<div class="form-group row mb-0">
							<div class="col-md-6 offset-md-4">
								<button type="submit" class="btn btn-primary">
									{{ __('label.search') }}
								</button>
							</div>
						</div>
					</form>
					{{-- Add new button --}}
					<div class="container mt-5">
						<a href="{{ route('user.register.show') }}" class="btn btn-warning">{{ __('label.addnew') }}</a>
					</div>
					{{-- List Users --}}
					<div class="container mt-5">
						<table class="table table-bordered mb-5">
							<thead>
								<tr class="table-success">
									<th scope="col">{{ __('label._no_') }}</th>
									<th scope="col">{{ __('validation.attributes.department') }}</th>
									<th scope="col">{{ __('validation.attributes.name') }}</th>
									<th scope="col">{{ __('label.action') }}</th>
								</tr>
							</thead>
							<tbody>
								@foreach($users as $user)
								<tr>
									<th scope="row">{{ str_pad($user->id, config('const.num_fillzero'), "0", STR_PAD_LEFT) }}</th>
									<td>{{ $user->department->name }}</td>
									<td>{{ $user->name }}</td>
									<td>
										<a href="{{ route('user.edit.show', $user->id) }}">{{ __('label.edit') }}</a>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					@if ($users->total() === 0)
					<div class="d-flex justify-content-center">
						{{ __('msg.no_data') }}
					</div>
					@endif
					{{-- paginator --}}
					<div class="d-flex justify-content-center">
						{{$users->withQueryString()->links('paginator')}}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection