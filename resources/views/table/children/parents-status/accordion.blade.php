<div class="mx-2">
    <input type="checkbox" class="mainAccordionCheckbox">&nbsp;&nbsp;&nbsp;
</div>
@forelse ($parentsStatus as $status)
    <div class="accordion accordion-flush tr-{{ $status->id }}" id="accordion{{ $loop->iteration }}">
        <div class="accordion-item">
            <h2 class="accordion-header" id="staff-listing-{{ $loop->iteration }}">
                <button class="accordion-button accordion-screen collapsed {{$status->status == 'inactive' ? $status->status : ''}}" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapse{{ $loop->iteration }}" aria-expanded="false"
                    aria-controls="flush-collapse{{ $loop->iteration }}">
                    @include('components.accordion-label', [
                        'id' => $status->id,
                        'name' => $status->name,
                        'edit' => route('children-table.edit', $status->id).'?type=parents-status',
                        'dataName' => $status->is_assign ? $status->name.' has assigned to children' : '',
                        'checkClass' => "check-".$status->id
                    ])
                </button>
            </h2>
            <div id="flush-collapse{{ $loop->iteration }}" class="accordion-collapse collapse"
                aria-labelledby="staff-listing-{{ $loop->iteration }}"
                data-bs-parent="#accordion{{ $loop->iteration }}" style="">
                <div class="accordion-body">
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">Name</div>
                        <div class="w-50">{{ $status->name }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="text-center"> {{ __('comon.emptyTableMsg') }} </div>
@endforelse
<div class="dataTables_paginate paging_simple_numbers mt-2" id="paginate">
    @include('components.pagination', ['paginate' => $parentsStatus])
</div>
