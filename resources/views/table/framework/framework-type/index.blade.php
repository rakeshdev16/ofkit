<div class="mb-4 page-info">
    <div>
        <h3 class="mb-0 text-uppercase">Framework Types ({{ __('comon.'.Auth::user()->getRoleNames()->first()) }})</h3>
    </div>
    <div class="mt-3">
        <a href="{{ route('framework-table.create') }}?type=framework-type" class="btn button">{{ __('cluster.addBtnText') }} +</a>
        <button class="btn button moveToArchive" data-type="framework-type">Move to Archive</button>
    </div>
</div>
<div class="card small-table">
    <div class="card-body">
        <div class="table-responsive full-width-table">
            @include('components.table-search', ['label' => 'Framework Types', 'count' => @$count])
            <div id="dataTable">
                @include('table.framework.framework-type.table', ['frameworkTypes' => $frameworkTypes])
            </div>
        </div>
        <div class="lising d-none" id="accordion">
            @include('table.framework.framework-type.accordion', ['frameworkTypes' => $frameworkTypes])
        </div>
    </div>
</div>