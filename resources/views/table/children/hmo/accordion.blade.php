<div class="mx-2">
    <input type="checkbox" class="mainAccordionCheckbox">&nbsp;&nbsp;&nbsp;
</div>
@forelse ($hmos as $hmo)
    <div class="accordion accordion-flush tr-{{ $hmo->id }}" id="accordion{{ $loop->iteration }}">
        <div class="accordion-item">
            <h2 class="accordion-header" id="staff-listing-{{ $loop->iteration }}">
                <button class="accordion-button accordion-screen collapsed {{$hmo->status == 'inactive' ? $hmo->status : ''}}" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse{{ $loop->iteration }}" aria-expanded="false" aria-controls="flush-collapse{{ $loop->iteration }}">
                    @include('components.accordion-label', [
                        'id' => $hmo->id,
                        'name' => $hmo->name,
                        'edit' => route('children-table.edit', $hmo->id) . '?type=hmo',
                        'dataName' => $hmo->is_assign ? $hmo->name . ' has assigned to children' : '',
                        'checkClass' => "check-".$hmo->id
                    ])
                </button>
            </h2>
            <div id="flush-collapse{{ $loop->iteration }}" class="accordion-collapse collapse" aria-labelledby="staff-listing-{{ $loop->iteration }}" data-bs-parent="#accordion{{ $loop->iteration }}" style="">
                <div class="accordion-body">
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('tables.name') }}</div>
                        <div class="w-50">{{ $hmo->name }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="text-center"> {{ __('comon.emptyTableMsg') }} </div>
@endforelse
<div class="dataTables_paginate paging_simple_numbers mt-2" id="paginate">
    @include('components.pagination', ['paginate' => $hmos])
</div>
