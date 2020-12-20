@if(session()->has(config('const.keymsg.success')))
    <div class="alert alert-success alert-dismissible fade show">
        <h6 class="alert-msg"><i class="icon fas fa-check"></i> {{ session()->get(config('const.keymsg.success')) }}</h6>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    {{ Session::forget(config('const.keymsg.success')) }}
@endif
@if(session()->has(config('const.keymsg.error')))
    <div class="alert alert-danger alert-dismissible fade show">
        <h6 class="alert-msg"><i class="icon fas fa-ban"></i> {{ session()->get(config('const.keymsg.error')) }}</h6>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    {{ Session::forget(config('const.keymsg.error')) }}
@endif
