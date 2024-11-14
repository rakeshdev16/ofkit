<div class="mx-2" style="display: {{ count($clusters) > 0 ? 'block' : 'none' }}">
    <input type="checkbox" class="mainAccordionCheckbox">&nbsp;&nbsp;&nbsp;
</div>
@forelse ($clusters as $cluster)
    <div class="accordion accordion-flush tr-{{ $cluster->id }}" id="accordion{{ $loop->iteration }}">
        <div class="accordion-item">
            <h2 class="accordion-header" id="staff-listing-{{ $loop->iteration }}">
                <button class="accordion-button accordion-screen collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapse{{ $loop->iteration }}" aria-expanded="false"
                    aria-controls="flush-collapse{{ $loop->iteration }}">
                    @include('components.accordion-label', [
                        'id' => $cluster->id,
                        'name' => @$cluster->manager->name,
                        'edit' => route('cluster.edit', $cluster->id),
                        'dataName' => $cluster->is_assign ? $cluster->name.' has assigned to kindergarten' : ''
                    ])
                </button>
            </h2>
            <div id="flush-collapse{{ $loop->iteration }}" class="accordion-collapse collapse"
                aria-labelledby="staff-listing-{{ $loop->iteration }}"
                data-bs-parent="#accordion{{ $loop->iteration }}" style="">
                <div class="accordion-body">
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('cluster.clusterTh') }}</div>
                        <div class="w-50">{{ $cluster->cluster }}</div>
                    </div><hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('cluster.kindergartens') }}</div>
                        <div class="w-50">
                            @forelse ($cluster->kindergartens->take(2) as $kindergarten)
                                {{ getKindergartenNameById($kindergarten->kindergarten_id) }}{{ !$loop->last ? ', ' : '' }}
                            @empty
                                -
                            @endforelse
                            {{ count($cluster->kindergartens) > 2 ? '...' : '' }}
                        </div>
                    </div><hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('cluster.managerTh') }}</div>
                        <div class="w-50">{{ @$cluster->manager->name ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="text-center"> {{ __('comon.emptyTableMsg') }} </div>
@endforelse
<div class="dataTables_paginate paging_simple_numbers mt-2" id="paginate">
    @include('components.pagination', ['paginate' => $clusters])
</div>
