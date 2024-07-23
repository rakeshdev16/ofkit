<div class="mb-4 page-info">
    <div>
        <h3 class="mb-0 text-uppercase">kindergarten Types ({{ __('comon.'.Auth::user()->getRoleNames()->first()) }})</h3>
    </div>
    <div class="mt-3">
        <a href="{{ route('framework-table.create') }}?type=kindergarten-type" class="btn button">{{ __('cluster.addBtnText') }} +</a>
        {{-- <button class="btn button moveToArchive" data-type="kindergarten-type">Move to Archive</button> --}}
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <div class="table-search">
                <label>Search: <input type="text" class="search" value="{{ request()->search }}" placeholder=""></label>
            </div>
            <div id="dataTable">
                @include('table.framework.kindergarten-type.table', ['kindergartenTypes' => $kindergartenTypes])
            </div>
        </div>
        <div class="lising d-none" id="accordion">
            @include('table.framework.kindergarten-type.accordion', ['kindergartenTypes' => $kindergartenTypes])
        </div>
    </div>
</div>