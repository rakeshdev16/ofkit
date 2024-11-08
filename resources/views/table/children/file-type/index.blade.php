<div class="mb-4 page-info">
    <div>
        <h3 class="mb-2 text-uppercase">{{ __('tables.fileType') }} </h3>
        @include('components.active-inactive')
    </div>
    <div class="mt-3">
        <a href="{{ route('children-table.create') }}?type=file-type" class="btn button">{{ __('cluster.addBtnText') }} +</a>
        @include('components.table-button')
    </div>
</div>
<div class="card small-table">
    <div class="card-body">
        <div class="table-responsive full-width-table">
            @include('components.table-search', ['label' => __('tables.fileType'), 'count' => @$fileTypesCount])
            <div id="dataTable">
                @include('table.children.file-type.table', ['fileTypes' => $fileTypes])
            </div>
        </div>
        <div class="lising d-none">
            @include('components.table-search', ['label' => __('tables.fileType'), 'count' => $fileTypesCount])
            <div id="accordion">
                @include('table.children.file-type.accordion', ['fileTypes' => $fileTypes])
            </div>
        </div>
    </div>
</div>
@push('customScript')
    <script></script>
@endpush
