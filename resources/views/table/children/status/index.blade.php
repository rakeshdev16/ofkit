<div class="mb-4 page-info">
    <div>
        <h3 class="mb-0 text-uppercase">Children Status </h3>
    </div>
    <div class="mt-3">
        <a href="{{ route('children-table.create') }}?type=status" class="btn button">{{ __('cluster.addBtnText') }} +</a>
        <button class="btn button moveToArchive" data-type="status">{{ __('cluster.moveBtnText') }}</button>
    </div>
</div>
<div class="card small-table">
    <div class="card-body">
        <div class="table-responsive full-width-table">
            @include('components.table-search', ['label' => 'Children Status', 'count' => @$statusCount])
            <div id="dataTable">
                @include('table.children.status.table', ['statuses' => $statuses])
            </div>
        </div>
        <div class="lising d-none">
            @include('components.table-search', ['label' => "Children Parent's Status", 'count' => @$statusCount])
            <div id="accordion">
                @include('table.children.status.accordion', ['statuses' => $statuses])
            </div>
        </div>
    </div>
</div>
@push('customScript')
    <script></script>
@endpush
