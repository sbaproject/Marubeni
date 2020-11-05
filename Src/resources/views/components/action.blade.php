<ul class="list-inline m-0">
    @isset($editUrl)
    <li class="list-inline-item">
        <a href="{{ $editUrl }}" title="{{ __('label.button.edit') }}" class="btn btn-success btn-sm rounded-0 @isset($editDisabled) disabled @endisset"
            data-toggle="tooltip">
            <i class="fa fa-edit"></i>
        </a>
    </li>
    @endisset
    @isset($deleteUrl)
    <li class="list-inline-item">
        <form action="{{ $deleteUrl }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm rounded-0 @isset($deleteDisabled) disabled @endisset" data-toggle="tooltip"
                title="{{ __('label.button.delete') }}" @isset($deleteDisabled) disabled  @endisset>
                <i class="fa fa-trash"></i>
            </button>
        </form>
    </li>
    @endisset
</ul>