<div class="mb-4 page-info">
    <div>
        <h3 class="mb-2 text-uppercase">{{ __('tables.frameworkTypes') }} </h3>
        {{-- @include('components.active-inactive') --}}
    </div>
    <div class="mt-3">
        <a href="{{ route('framework-table.create') }}?type=framework-type" class="btn button">{{ __('comon.addNew') }} +</a>
        @include('components.table-button')
    </div>
</div>
<div class="card small-table">
    <div class="card-body">
        <div class="table-responsive full-width-table">
            @include('components.table-search', ['label' => __('tables.frameworkTypes'), 'count' => @$count])
            <div id="dataTable">
                @include('table.framework.framework-type.table', ['frameworkTypes' => $frameworkTypes])
            </div>
        </div>
        <div class="lising d-none">
            @include('components.table-search', ['label' => __('tables.frameworkTypes'), 'count' => $count])
            <div id="accordion">
                @include('table.framework.framework-type.accordion', ['frameworkTypes' => $frameworkTypes])
            </div>
        </div>
    </div>
</div>
