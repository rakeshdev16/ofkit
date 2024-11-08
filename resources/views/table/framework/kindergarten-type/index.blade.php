<div class="mb-4 page-info">
    <div>
        <h3 class="mb-2 text-uppercase">{{ __('tables.kindergartenTypes') }}</h3>
        @include('components.active-inactive')
    </div>
    <div class="mt-3">
        <a href="{{ route('framework-table.create') }}?type=kindergarten-type" class="btn button">{{ __('comon.addNew') }} +</a>
        @include('components.table-button')
    </div>
</div>
<div class="card small-table">
    <div class="card-body">
        <div class="table-responsive full-width-table">
            @include('components.table-search', ['label' => __('tables.kindergartenTypes'), 'count' => @$count])
            <div id="dataTable">
                @include('table.framework.kindergarten-type.table', ['kindergartenTypes' => $kindergartenTypes])
            </div>
        </div>
        <div class="lising d-none">
            @include('components.table-search', ['label' => __('tables.kindergartenTypes'), 'count' => $count])
            <div id="accordion">
                @include('table.framework.kindergarten-type.accordion', ['kindergartenTypes' => $kindergartenTypes])
            </div>
        </div>
    </div>
</div>
