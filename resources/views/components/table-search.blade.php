<div class="table-search">
    <label> {{ $label }} <b id="totalCount">{{ $count }}</b> :{{ __('comon.total') }}</label>
    <label>
        <input type="search" class="search" value="{{ request()->search }}" placeholder="">
        <button class="btn search-button">{{ __('comon.search') }}</button>
    </label>
</div>