<div class="mb-4 page-info">
    <div>
        <h3 class="mb-2 text-uppercase">{{ __('tables.occurrenceInterventionType') }} </h3>
        {{-- @include('components.active-inactive') --}}
    </div>
    <div class="mt-3">
        <a href="{{ route('intervention.create') }}?type=intervention-type" class="btn button">{{ __('cluster.addBtnText') }} +</a>
        @include('components.table-button')
    </div>
</div>
<div class="card small-table">
    <div class="card-body">
        <div class="table-responsive full-width-table">
            @include('components.table-search', ['label' => __('tables.occurrenceInterventionType'), 'count' => @$interventionCount])
            <div id="dataTable">
                @include('table.intervention.intervention-type.table', ['interventionTypes' => $interventionTypes])
            </div>
        </div>
        <div class="lising d-none">
            @include('components.table-search', ['label' => __('tables.occurrenceInterventionType'), 'count' => $interventionCount])
            <div id="accordion">
                @include('table.intervention.intervention-type.accordion', ['interventionTypes' => $interventionTypes])
            </div>
        </div>
    </div>
</div>
