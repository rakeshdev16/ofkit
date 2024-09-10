<div class="table-search">
    <label> {{ $label }} <b class="totalCount">{{ $count }}</b> :{{ __('comon.total') }}</label>
    <label class="d-flex">
        <input type="search" class="search" value="{{ request()->search }}" placeholder="">
        <button class="btn search-button">{{ __('comon.search') }}</button>
    </label>
</div>
