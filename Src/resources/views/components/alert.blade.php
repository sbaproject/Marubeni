@if(session()->has(KEY_SUCCESS))
    <div class="alert alert-success alert-dismissible fade show">
        <strong>{{ __('label.success') }}!</strong> {{ session()->get(KEY_SUCCESS) }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    {{ Session::forget(KEY_SUCCESS) }}
@endif
@if(session()->has(KEY_ERROR))
    <div class="alert alert-danger alert-dismissible fade show">
        <strong>{{ __('label.fail') }}!</strong> {{ session()->get(KEY_ERROR) }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    {{ Session::forget(KEY_ERROR) }}
@endif
