@if ($paginator->hasPages())
<div class="clearfix p-0 text-center pb-3">
    <div class="pager-wrap">
        {{-- Main Nav  --}}
        <ul class="pagination pagination-sm pager_custom ">
            {{-- Prev --}}
            @if ($paginator->onFirstPage())
            <li class="page-item disabled" aria-disabled="true" aria-label="« {{ __('label.paginator.prev') }}">
                <span class="page-link" aria-hidden="true">«</span>
            </li>
            @else
            <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}">«</a></li>
            @endif

            @foreach ($elements as $element)

            {{-- for like this [...] --}}
            @if (is_string($element))
            <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
            @endif

            {{-- for like this [1][2][3] --}}
            @if (is_array($element))
            @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
            <li class="page-item active"><span class="page-link">{{ $page }}</a></span></li>
            @else
            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
            @endif
            @endforeach
            @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}">»</a></li>
            @else
            <li class="page-item disabled" aria-disabled="true" aria-label="{{ __('label.paginator.next') }} »">
                <span class="page-link" aria-hidden="true">»</span>
            </li>
            @endif
        </ul>

        {{-- Sub Nav  --}}
        <div class="bt_direct">
            {{-- Pre --}}
            @if ($paginator->onFirstPage())
            <a href="{{ $paginator->previousPageUrl() }}" class="bt_left float-left btn disabled">
                <i class="fa fa-long-arrow-alt-left">{{ __('label.paginator.prev') }}</i>
            </a>
            @else
            <a href="{{ $paginator->previousPageUrl() }}" class="bt_left float-left btn">
                <i class="fa fa-long-arrow-alt-left">{{ __('label.paginator.prev') }}</i>
            </a>
            @endif
            {{-- Next --}}
            @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="bt_right float-right btn">
                <i class="nav-icon fas fa-long-arrow-alt-right">{{ __('label.paginator.next') }}</i>
            </a>
            @else
            <a href="{{ $paginator->nextPageUrl() }}" class="bt_right float-right btn disabled">
                <i class="nav-icon fas fa-long-arrow-alt-right">{{ __('label.paginator.next') }}</i>
            </a>
            @endif
        </div>
    </div>
</div>
@endif