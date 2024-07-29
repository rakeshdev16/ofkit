<div class="mb-4 page-info">
    <div>
        <h3 class="mb-0 text-uppercase">Occurrence/Intervention Type ({{ __('comon.'.Auth::user()->getRoleNames()->first()) }})</h3>
    </div>
    <div class="mt-3">
        <a href="{{ route('intervention.create') }}?type=intervention-type" class="btn button">{{ __('cluster.addBtnText') }} +</a>
        {{-- <button class="btn button moveToArchive" data-type="framework-type">Move to Archive</button> --}}
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            @include('components.table-search')
            <div id="dataTable">
                @include('table.intervention.intervention-type.table', ['interventionTypes' => $interventionTypes])
            </div>
        </div>
        <div class="lising d-none" id="accordion">
            @include('table.intervention.intervention-type.accordion', ['interventionTypes' => $interventionTypes])
        </div>
    </div>
</div>