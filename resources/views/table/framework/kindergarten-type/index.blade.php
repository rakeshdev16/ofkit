<div class="mb-4 page-info">
    <div>
        <h3 class="mb-0 text-uppercase">kindergarten Types ({{ __('comon.'.Auth::user()->getRoleNames()->first()) }})</h3>
    </div>
    <div class="">
        <a href="{{ route('framework-table.create') }}?type=kindergarten-type" class="btn button">Add New</a>
        <button class="btn button moveToArchive" data-type="kindergarten-type">Move to Archive</button>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive" id="dataTable">
            @include('table.framework.kindergarten-type.table', ['kindergartenTypes' => $kindergartenTypes])
        </div>
        <div class="lising d-none" id="accordion">
            @include('table.framework.kindergarten-type.accordion', ['kindergartenTypes' => $kindergartenTypes])
        </div>
    </div>
</div>