<div class="table-search">
    <label> {{ $label }}: <b class="totalCount">{{ $count }}</b></label>

    <div class="d-flex">
        <button class="btn print-button mx-2">Print</button>

        <label class="d-flex">
            <input type="search" class="search" value="{{ request()->search }}" placeholder="">
            <button class="btn search-button">{{ __('comon.search') }}</button>
        </label>
    </div>
</div>
