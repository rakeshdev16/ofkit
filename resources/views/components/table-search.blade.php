<div class="table-search">
    <div class="d-flex">
        <label> {{ $label }}: <b class="totalCount">{{ $count }}</b></label>
        <div class="form-check form-check-warning ms-3">
            <input class="form-check-input status" type="checkbox" name="status" id="flexCheckChecked" {{request()->status == 'inactive' ? 'checked' : ''}}>
            <label class="form-check-label" for="flexCheckChecked">
                Show Inactive
            </label>
        </div>
    </div>

    <div class="d-flex">
        <button class="btn print-button mx-2">Print</button>

        <label class="d-flex">
            <input type="search" class="search" value="{{ request()->search }}" placeholder="">
            <button class="btn search-button">{{ __('comon.search') }}</button>
        </label>
    </div>
</div>
