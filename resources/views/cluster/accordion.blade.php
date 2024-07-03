<div class="mx-3 p-1">
    <input type="checkbox" class="mainCheckbox">&nbsp;&nbsp;&nbsp;
    {{-- <label for="">{{ __('cluster.mainCheckboxLabel') }}</label> --}}
</div>
@foreach ($clusters as $cluster)
    <div class="accordion accordion-flush tr-{{ $cluster->id }}" id="accordion{{ $loop->iteration }}">
        <div class="accordion-item">
            <h2 class="accordion-header" id="staff-listing-{{ $loop->iteration }}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapse{{ $loop->iteration }}" aria-expanded="false"
                    aria-controls="flush-collapse{{ $loop->iteration }}">
                    <input type="checkbox" value="{{ $cluster->id }}" class="checkbox">&nbsp;&nbsp;
                    {{ @$cluster->manager->name ?? '-' }}
                </button>
            </h2>
            <div id="flush-collapse{{ $loop->iteration }}" class="accordion-collapse collapse"
                aria-labelledby="staff-listing-{{ $loop->iteration }}"
                data-bs-parent="#accordion{{ $loop->iteration }}" style="">
                <div class="accordion-body">
                    <div class="d-flex">
                        <div class="w-50">{{ __('cluster.clusterTh') }}</div>
                        <div class="w-50">{{ $cluster->cluster }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-50">{{ __('cluster.managerTh') }}</div>
                        <div class="w-50">{{ @$cluster->manager->name ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
<div class="dataTables_paginate paging_simple_numbers mt-2" id="paginate">
    @include('components.pagination', ['paginate' => $clusters])
</div>
