<ul class="list-inline m-0">
    @isset($editUrl)
    <li class="list-inline-item">
        <a href="{{ $editUrl }}" title="{{ __('label.button_edit') }}" class="btn bg-gradient-success btn-sm @isset($editDisabled) disabled @endisset"
            data-toggle="tooltip">
            <i class="fa fa-pencil-alt"></i>
        </a>
    </li>
    @endisset
    @isset($deleteUrl)
    <li class="list-inline-item">
        <form action="{{ $deleteUrl }}" method="POST">
            @csrf
            <button type="button" class="btn bg-gradient-danger btn-sm @isset($deleteDisabled) disabled @endisset" data-toggle="tooltip"
                title="{{ __('label.button_delete') }}" @isset($deleteDisabled) disabled  @endisset
                data-toggle="modal" data-target="#popup-confirm">
                <i class="fa fa-trash"></i>
            </button>
        </form>
    </li>
    @endisset
</ul>