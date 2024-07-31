<div class="mb-4 page-info">
    <div>
        <h3 class="mb-0 text-uppercase">Children HMO ({{ __('comon.'.Auth::user()->getRoleNames()->first()) }})</h3>
    </div>
    <div class="mt-3">
        <a href="{{ route('children-table.create') }}?type=hmo" class="btn button">{{ __('cluster.addBtnText') }} +</a>
        <button class="btn button moveToArchive" data-type="role">{{ __('cluster.moveBtnText') }}</button>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            @include('components.table-search')
            <div id="dataTable">
                @include('table.children.hmo.table', ['hmos' => $hmos])
            </div>
        </div>
        <div class="lising d-none" id="accordion">
            @include('table.children.hmo.accordion', ['hmos' => $hmos])
        </div>
    </div>
</div>
@push('customScript')
    <script>
        
    </script>
@endpush