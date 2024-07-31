<div class="mx-3 p-1">
    <input type="checkbox" class="mainAccordionCheckbox">&nbsp;&nbsp;&nbsp;
</div>
@forelse ($frameworkTypes as $frameworkType)
    <div class="accordion accordion-flush tr-{{ $frameworkType->id }}" id="accordion{{ $loop->iteration }}">
        <div class="accordion-item">
            <h2 class="accordion-header" id="staff-listing-{{ $loop->iteration }}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapse{{ $loop->iteration }}" aria-expanded="false"
                    aria-controls="flush-collapse{{ $loop->iteration }}">
                    @include('components.accordion-label', [
                        'id' => $frameworkType->id,
                        'name' => $frameworkType->name,
                        'edit' => route('framework-table.edit', $frameworkType->id).'?type=framework-type',
                        'dataName' => $frameworkType->is_assign ? $frameworkType->name.' has assigned to kindergarten' : ''
                    ])
                </button>
            </h2>
            <div id="flush-collapse{{ $loop->iteration }}" class="accordion-collapse collapse"
                aria-labelledby="staff-listing-{{ $loop->iteration }}"
                data-bs-parent="#accordion{{ $loop->iteration }}" style="">
                <div class="accordion-body">
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">Name</div>
                        <div class="w-50">{{ $frameworkType->name }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="text-center"> {{ __('comon.emptyTableMsg') }} </div>
@endforelse
<div class="dataTables_paginate paging_simple_numbers mt-2" id="paginate">
    @include('components.pagination', ['paginate' => $frameworkTypes])
</div>
