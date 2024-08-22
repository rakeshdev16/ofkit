<div class="mb-4 page-info">
    <div>
        <h3 class="mb-0 text-uppercase">Children Status ({{ __('comon.'.Auth::user()->getRoleNames()->first()) }})</h3>
    </div>
    <div class="mt-3">
        <a href="{{ route('children-table.create') }}?type=status" class="btn button">{{ __('cluster.addBtnText') }} +</a>
        <button class="btn button moveToArchive" data-type="role">{{ __('cluster.moveBtnText') }}</button>
    </div>
</div>
<div class="card small-table">
    <div class="card-body">
        <div class="table-responsive">
            @include('components.table-search', ['label' => "Children Status", 'count' => @$statusCount])
            <div id="dataTable">
                @include('table.children.status.table', ['statuses' => $statuses])
            </div>
        </div>
        <div class="lising d-none" id="accordion">
            @include('table.children.status.accordion', ['statuses' => $statuses])
        </div>
    </div>
</div>
@push('customScript')
    <script>
        
    </script>
@endpush