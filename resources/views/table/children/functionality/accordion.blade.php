<div class="mx-3 p-1">
    {{-- <input type="checkbox" class="mainCheckbox">&nbsp;&nbsp;&nbsp; --}}
</div>
@foreach ($functionalities as $functionality)
    <div class="accordion accordion-flush tr-{{ $functionality->id }}" id="accordion{{ $loop->iteration }}">
        <div class="accordion-item">
            <h2 class="accordion-header" id="staff-listing-{{ $loop->iteration }}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapse{{ $loop->iteration }}" aria-expanded="false"
                    aria-controls="flush-collapse{{ $loop->iteration }}">
                    @include('components.accordion-label', [
                        'id' => $functionality->id,
                        'name' => $functionality->name,
                        'edit' => route('children-table.edit', $functionality->id).'?type=functionality',
                    ])
                </button>
            </h2>
            <div id="flush-collapse{{ $loop->iteration }}" class="accordion-collapse collapse"
                aria-labelledby="staff-listing-{{ $loop->iteration }}"
                data-bs-parent="#accordion{{ $loop->iteration }}" style="">
                <div class="accordion-body">
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">Name</div>
                        <div class="w-50">{{ $functionality->name }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
<div class="dataTables_paginate paging_simple_numbers mt-2" id="paginate">
    @include('components.pagination', ['paginate' => $functionalities])
</div>
