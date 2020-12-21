{{-- <div id="toastsContainerTopRight" class="toasts-top-right fixed">
    <div class="toast bg-danger fade show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header"><strong class="mr-auto">Toast Title</strong><small>Subtitle</small><button
                data-dismiss="toast" type="button" class="ml-2 mb-1 close" aria-label="Close"><span
                    aria-hidden="true">×</span></button></div>
        <div class="toast-body">Lorem ipsum dolor sit amet, consetetur sadipscing elitr.</div>
    </div>
</div> --}}
@if(session()->has(config('const.keymsg.success')))
    <script>
        $(function(){
            $(document).Toasts('create', {
                position: 'topCenter',
                class: 'bg-success',
                icon: 'icon fas fa-check',
                title: @json(__('label.success')),
                // subtitle: 'Subtitle',
                body: @json(session()->get(config('const.keymsg.success')))
            });
        });
    </script>
    {{-- <div class="alert alert-success alert-dismissible fade show">
        <h6 class="alert-msg"><i class="icon fas fa-check"></i> {{ session()->get(config('const.keymsg.success')) }}</h6>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    {{ Session::forget(config('const.keymsg.success')) }} --}}
@endif
@if(session()->has(config('const.keymsg.error')))
    <script>
        $(function(){
            $(document).Toasts('create', {
                position: 'topCenter',
                class: 'bg-danger',
                icon: 'icon fas fa-ban',
                title: @json(__('label.fail')),
                // subtitle: 'Subtitle',
                body: @json(session()->get(config('const.keymsg.error')))
            });
        });
    </script>
    {{-- <div class="alert alert-danger alert-dismissible fade show">
        <h6 class="alert-msg"><i class="icon fas fa-ban"></i> {{ session()->get(config('const.keymsg.error')) }}</h6>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    {{ Session::forget(config('const.keymsg.error')) }} --}}
@endif