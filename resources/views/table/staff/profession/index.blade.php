<div class="mb-4 page-info">
    <div>
        <h3 class="mb-2 text-uppercase">{{ __('tables.staffAcademicProfession') }}</h3>
        {{-- @include('components.active-inactive') --}}
    </div>
    <div class="mt-3">
        <a href="{{ route('staff-table.create') }}?type=profession" class="btn button">{{ __('comon.addNew') }} +</a>
        @include('components.table-button')
    </div>
</div>
<div class="card small-table">
    <div class="card-body">
        <div class="table-responsive full-width-table">
            @include('components.table-search', ['label' => __('tables.staffAcademicProfessions'), 'count' => @$professionCount])
            <div id="dataTable">
                @include('table.staff.profession.table', ['professions' => $professions])
            </div>
        </div>
        <div class="lising d-none">
            @include('components.table-search', ['label' => __('tables.staffAcademicProfessions'), 'count' => $professionCount])
            <div id="accordion">
                @include('table.staff.profession.accordion', ['professions' => $professions])
            </div>
        </div>
    </div>
</div>
@push('customScript')
    <script></script>
@endpush
