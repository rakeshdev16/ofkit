<div class="mx-3 p-1">
    <input type="checkbox" class="mainCheckbox">&nbsp;&nbsp;&nbsp;
</div>
@foreach ($clusters as $cluster)
    <div class="accordion accordion-flush tr-{{ $cluster->id }}" id="accordion{{ $loop->iteration }}">
        <div class="accordion-item">
            <h2 class="accordion-header" id="staff-listing-{{ $loop->iteration }}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapse{{ $loop->iteration }}" aria-expanded="false"
                    aria-controls="flush-collapse{{ $loop->iteration }}">
                    <div class="row">
                        <div class="col-2">
                            <input type="checkbox" value="{{ $cluster->id }}" class="checkbox">&nbsp;&nbsp;
                        </div>
                        <div class="col-8">{{ @$cluster->manager->name ?? '-' }}</div>
                        <div class="col-2 d-flex justify-content-center">
                            <a href="{{ route('cluster.edit', $cluster->id) }}" class=""><i class="bx bx-edit"></i></a>
                        </div>
                    </div>
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
