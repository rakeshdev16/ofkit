<div class="mb-4 page-info">
    <div>
        <h3 class="mb-0 text-uppercase">Framework Types ({{ __('comon.'.Auth::user()->getRoleNames()->first()) }})</h3>
    </div>
    <div class="">
        <a href="{{ route('framework-table.create') }}?type=framework-type" class="btn button">Add New</a>
        <button class="btn button moveToArchive" data-type="framework-type">Move to Archive</button>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <div class="table-search">
                <label>Search: <input type="search" class="search" value="{{ request()->search }}" placeholder=""></label>
            </div>
            <div id="dataTable">
                @include('table.framework.framework-type.table', ['frameworkTypes' => $frameworkTypes])
            </div>
        </div>
        <div class="lising d-none" id="accordion">
            @include('table.framework.framework-type.accordion', ['frameworkTypes' => $frameworkTypes])
        </div>
    </div>
</div>