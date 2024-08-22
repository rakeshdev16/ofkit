<div class="table-search">
    <label> {{ $label }} <b id="totalCount">{{ $count }}</b> :total</label>
    <label>
        <input type="search" class="search" value="{{ request()->search }}" placeholder="">
        <button class="btn search-button">Search</button>
    </label>
</div>