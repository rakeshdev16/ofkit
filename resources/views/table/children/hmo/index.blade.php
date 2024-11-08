<div class="mb-4 page-info">
    <div>
        <h3 class="mb-2 text-uppercase">{{ __('tables.ChildrenHMO') }} </h3>
        @include('components.active-inactive')
    </div>
    <div class="mt-3">
        <a href="{{ route('children-table.create') }}?type=hmo" class="btn button">{{ __('cluster.addBtnText') }} +</a>
        @include('components.table-button')
    </div>
</div>
<div class="card small-table">
    <div class="card-body">
        <div class="table-responsive full-width-table">
            @include('components.table-search', ['label' => __('tables.ChildrenHMO'), 'count' => @$hmoCount])
            <div id="dataTable">
                @include('table.children.hmo.table', ['hmos' => $hmos])
            </div>
        </div>
        <div class="lising d-none">
            @include('components.table-search', ['label' => __('tables.ChildrenHMO'), 'count' => $hmoCount])
            <div id="accordion">
                @include('table.children.hmo.accordion', ['hmos' => $hmos])
            </div>
        </div>
    </div>
</div>
@push('customScript')
    <script></script>
@endpush
