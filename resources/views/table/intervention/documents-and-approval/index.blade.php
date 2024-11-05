<div class="mb-4 page-info">
    <div>
        <h3 class="mb-0 text-uppercase">{{ __('tables.documentsAndApproval') }} </h3>
    </div>
    <div class="mt-3">
        <a href="{{ route('intervention.create') }}?type=documents-and-approval" class="btn button">{{ __('cluster.addBtnText') }} +</a>
        <button class="btn button moveToArchive" data-type="documents-and-approval">{{ __('comon.moveToArchive') }}</button>
    </div>
</div>
<div class="card small-table">
    <div class="card-body">
        <div class="table-responsive full-width-table">
            @include('components.table-search', ['label' => __('tables.documentsAndApproval'), 'count' => @$documentCount])
            <div id="dataTable">
                @include('table.intervention.documents-and-approval.table', ['documents' => $documents])
            </div>
        </div>
        <div class="lising d-none">
            @include('components.table-search', ['label' => __('tables.documentsAndApproval'), 'count' => $documentCount])
            <div id="accordion">
                @include('table.intervention.documents-and-approval.accordion', ['documents' => $documents])
            </div>
        </div>
    </div>
</div>
