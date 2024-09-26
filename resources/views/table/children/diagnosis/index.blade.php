<div class="mb-4 page-info">
    <div>
        <h3 class="mb-0 text-uppercase">Children Diagnosis </h3>
    </div>
    <div class="mt-3">
        <a href="{{ route('children-table.create') }}?type=diagnosis" class="btn button">{{ __('cluster.addBtnText') }} +</a>
        <button class="btn button moveToArchive" data-type="diagnosis">{{ __('cluster.moveBtnText') }}</button>
    </div>
</div>
<div class="card small-table">
    <div class="card-body">
        <div class="table-responsive full-width-table">
            @include('components.table-search', ['label' => 'Children Diagnosis', 'count' => @$diagnosisCount])
            <div id="dataTable">
                @include('table.children.diagnosis.table', ['diagnosises' => $diagnosises])
            </div>
        </div>
        <div class="lising d-none">
            @include('components.table-search', ['label' => 'Children Diagnosis', 'count' => $diagnosisCount])
            <div id="accordion">
                @include('table.children.diagnosis.accordion', ['diagnosises' => $diagnosises])
            </div>
        </div>
    </div>
</div>
@push('customScript')
    <script></script>
@endpush
