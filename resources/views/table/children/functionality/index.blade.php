<div class="mb-4 page-info">
    <div>
        <h3 class="mb-2 text-uppercase">{{ __('tables.childrenFunctionality') }} </h3>
        {{-- @include('components.active-inactive') --}}
    </div>
    <div class="mt-3">
        <a href="{{ route('children-table.create') }}?type=functionality" class="btn button">{{ __('cluster.addBtnText') }} +</a>
        @include('components.table-button')
    </div>
</div>
<div class="card small-table">
    <div class="card-body">
        <div class="table-responsive full-width-table">
            @include('components.table-search', ['label' => __('tables.childrenFunctionality'), 'count' => @$functionalityCount])
            <div id="dataTable">
                @include('table.children.functionality.table', ['functionalities' => $functionalities])
            </div>
        </div>
        <div class="lising d-none">
            @include('components.table-search', ['label' => __('tables.childrenFunctionality'), 'count' => $functionalityCount])
            <div id="accordion">
                @include('table.children.functionality.accordion', ['functionalities' => $functionalities])
            </div>
        </div>
    </div>
</div>
@push('customScript')
    <script></script>
@endpush
