<div class="mb-4 page-info">
    <div>
        <h3 class="mb-0 text-uppercase">Documents And Approval ({{ __('comon.'.Auth::user()->getRoleNames()->first()) }})</h3>
    </div>
    <div class="mt-3">
        <a href="{{ route('intervention.create') }}?type=documents-and-approval" class="btn button">{{ __('cluster.addBtnText') }} +</a>
        {{-- <button class="btn button moveToArchive" data-type="documents-and-approval">Move to Archive</button> --}}
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            @include('components.table-search')
            <div id="dataTable">
                @include('table.intervention.documents-and-approval.table', ['documents' => $documents])
            </div>
        </div>
        <div class="lising d-none" id="accordion">
            @include('table.intervention.documents-and-approval.accordion', ['documents' => $documents])
        </div>
    </div>
</div>