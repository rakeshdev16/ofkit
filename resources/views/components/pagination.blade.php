@if ($paginate->hasPages())
    <ul class="pagination">
        @if ($paginate->onFirstPage())
            <li class="paginate_button page-item previous disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                <a class="page-link" aria-hidden="true">{{ __('comon.previous') }}</a>
            </li>
        @else
            <li class="paginate_button page-item previous">
                <button data-page="{{ $paginate->previousPageUrl() }}" class="page-link paginationBtn" rel="prev" aria-label="@lang('pagination.previous')">{{ __('comon.previous') }}</button>
            </li>
        @endif

        @php
            $currentPage = request()->page ?? $paginate->currentPage();
            $lastPage = $paginate->lastPage();
            $start = max($currentPage - 1, 1);
            $end = min($currentPage + 1, $lastPage);
        @endphp

        @if ($start > 1)
            <li class="paginate_button page-item">
                <button data-page="{{ $paginate->url(1) }}" class="page-link paginationBtn">1</button>
            </li>
            @if ($start > 2)
                <li class="paginate_button page-item disabled" aria-disabled="true">
                    <a class="page-link">...</a>
                </li>
            @endif
        @endif

        @for ($i = $start; $i <= $end; $i++)
            @if ($i == $currentPage)
                <li class="paginate_button page-item active" aria-current="page">
                    <a class="page-link">{{ $i }}</a>
                </li>
            @else
                <li class="paginate_button page-item">
                    <button data-page="{{ $paginate->url($i) }}" class="page-link paginationBtn">{{ $i }}</button>
                </li>
            @endif
        @endfor

        @if ($end < $lastPage)
            @if ($end < $lastPage - 1)
                <li class="paginate_button page-item disabled" aria-disabled="true">
                    <a class="page-link">...</a>
                </li>
            @endif
            <li class="paginate_button page-item">
                <button data-page="{{ $paginate->url($lastPage) }}" class="page-link paginationBtn">{{ $lastPage }}</button>
            </li>
        @endif

        @if ($paginate->hasMorePages())
            <li class="paginate_button page-item next">
                <button data-page="{{ $paginate->nextPageUrl() }}" class="page-link paginationBtn" rel="next" aria-label="@lang('pagination.next')">{{ __('comon.next') }}</button>
            </li>
        @else
            <li class="paginate_button page-item next disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                <a class="page-link" aria-hidden="true">{{ __('comon.next') }}</a>
            </li>
        @endif
    </ul>
@endif
