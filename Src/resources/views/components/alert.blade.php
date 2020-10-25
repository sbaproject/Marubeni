@if(session()->has(config('const.keymsg.success')))
    <div class="alert alert-success alert-dismissible fade show">
        <strong>{{ __('label.success') }}!</strong> {{ session()->get(config('const.keymsg.success')) }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    {{ Session::forget(config('const.keymsg.success')) }}
@endif
@if(session()->has(config('const.keymsg.error')))
    <div class="alert alert-danger alert-dismissible fade show">
        <strong>{{ __('label.fail') }}!</strong> {{ session()->get(config('const.keymsg.error')) }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    {{ Session::forget(config('const.keymsg.error')) }}
@endif
