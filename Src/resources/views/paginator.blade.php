@if ($paginator->total() > 0 && $paginator->currentPage() <= $paginator->lastPage())
<div class="clearfix text-center" style="margin-top: 1rem;margin-bottom: 1rem">
    @php
        $total = $paginator->total();
        $from = (($paginator->currentPage() - 1) * $paginator->perPage()) + 1;
        if($paginator->hasMorePages()){
            $to = $paginator->currentPage() * $paginator->perPage();
        } else {
            $to = $total;
        }
    @endphp
    {{ __('label.paginator_show_result_label',['total' => $total, 'from' => $from, 'to' => $to]) }}
    </div>
@else
    <div class="clearfix p-0 text-center pb-3">
        <div class="pager-wrap" style="margin-top: 1rem">
            {{ __('msg.no_data') }}
        </div>
    </div>
@endif

@if ($paginator->hasPages())
<div class="clearfix p-0 text-center pb-3">
    <div class="pager-wrap">
        {{-- Main Nav  --}}
        <ul class="pagination pagination-sm pager_custom ">
            @if ($paginator->onFirstPage())
            {{-- First --}}
            <li class="page-item disabled" aria-disabled="true" title="{{ __('label.paginator_first') }}">
                <span class="page-link" aria-hidden="true">«</span>
            </li>
            {{-- Prev --}}
            <li class="page-item disabled" aria-disabled="true" title="{{ __('label.paginator_prev') }}">
                <span class="page-link" aria-hidden="true">‹</span>
            </li>
            @else
            <li class="page-item" title="{{ __('label.paginator_first') }}"><a class="page-link" href="{{ $paginator->url(1) }}">«</a></li>
            <li class="page-item" title="{{ __('label.paginator_prev') }}"><a class="page-link" href="{{ $paginator->previousPageUrl() }}">‹</a></li>
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

            {{-- Last --}}
            @if ($paginator->hasMorePages())
            <li class="page-item" title="{{ __('label.paginator_next') }}"><a class="page-link" href="{{ $paginator->nextPageUrl() }}">›</a></li>
            <li class="page-item" title="{{ __('label.paginator_last') }}"><a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}">»</a></li>
            @else
            <li class="page-item disabled" aria-disabled="true" title="{{ __('label.paginator_next') }}">
                <span class="page-link" aria-hidden="true">›</span>
            </li>
            <li class="page-item disabled" aria-disabled="true" title="{{ __('label.paginator_last') }}">
                <span class="page-link" aria-hidden="true">»</span>
            </li>
            @endif
        </ul>

        {{-- Sub Nav  --}}
        {{-- <div class="bt_direct"> --}}
            {{-- Pre --}}
            {{-- @if ($paginator->onFirstPage()) --}}
            {{-- <a href="{{ $paginator->previousPageUrl() }}" class="bt_left float-left btn disabled">
                <i class="nav-icon fa fa-long-arrow-alt-left"></i>
                {{ __('label.paginator_prev') }}
            </a> --}}
            {{-- @else --}}
            {{-- <a href="{{ $paginator->previousPageUrl() }}" class="bt_left float-left btn">
                <i class="nav-icon fa fa-long-arrow-alt-left"></i>
                {{ __('label.paginator_prev') }}
            </a> --}}
            {{-- @endif --}}
            {{-- Next --}}
            {{-- @if ($paginator->hasMorePages()) --}}
            {{-- <a href="{{ $paginator->nextPageUrl() }}" class="bt_right float-right btn">
                {{ __('label.paginator_next') }}
                <i class="nav-icon fas fa-long-arrow-alt-right"></i>
            </a> --}}
            {{-- @else --}}
            {{-- <a href="{{ $paginator->nextPageUrl() }}" class="bt_right float-right btn disabled">
                {{ __('label.paginator_next') }}
                <i class="nav-icon fas fa-long-arrow-alt-right"></i>
            </a> --}}
            {{-- @endif --}}
        {{-- </div> --}}
    </div>
</div>
@else
{{-- @if ($paginator->total() === 0)
<div class="clearfix p-0 text-center pb-3">
    <div class="pager-wrap" style="margin-top: 1rem">
        {{ __('msg.no_data') }}
    </div>
</div>
@endif --}}
@endif