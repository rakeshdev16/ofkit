<div class="mb-4 page-info">
    <div>
        <h3 class="mb-0 text-uppercase">Association ({{ __('comon.'.Auth::user()->getRoleNames()->first()) }})</h3>
    </div>
    <div class="">
        <a href="{{ route('staff-table.create') }}?type=association" class="btn button">Add New</a>
        <button class="btn button moveToArchive" data-type="association">Move to Archive</button>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive" id="dataTable">
            @include('table.staff.association.table', ['associations' => $associations])
        </div>
        <div class="lising d-none" id="accordion">
            @include('table.staff.association.accordion', ['associations' => $associations])
        </div>
    </div>
</div>