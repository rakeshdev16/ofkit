<div class="mb-4 page-info">
    <div>
        <h3 class="mb-2 text-uppercase">{{ __('tables.staffProfessionalRole') }} </h3>
        @include('components.active-inactive')
    </div>
    <div class="mt-3">
        <a href="{{ route('staff-table.create') }}?type=role" class="btn button">{{ __('cluster.addBtnText') }} +</a>
        @include('components.table-button')
    </div>
</div>
<div class="card small-table">
    <div class="card-body">
        <div class="table-responsive full-width-table">
            @include('components.table-search', ['label' => __('tables.staffProfessionalRole'), 'count' => @$roleCount])
            <div id="dataTable">
                @include('table.staff.role.table', ['roles' => $roles])
            </div>
        </div>
        <div class="lising d-none">
            @include('components.table-search', ['label' => __('tables.staffProfessionalRole'), 'count' => $roleCount])
            <div id="accordion">
                @include('table.staff.role.accordion', ['roles' => $roles])
            </div>
        </div>
    </div>
</div>
@push('customScript')
    <script></script>
@endpush
