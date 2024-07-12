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
                    {{-- <input type="checkbox" value="{{ $functionality->id }}" class="checkbox">&nbsp;&nbsp; --}}
                    {{ \Str::limit($functionality->name, 10, '...') ?? '-' }}&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="{{ route('children-table.edit', $functionality->id) }}?type=functionality" class=""><i class="bx bx-edit icon"></i></a>&nbsp;
                </button>
            </h2>
            <div id="flush-collapse{{ $loop->iteration }}" class="accordion-collapse collapse"
                aria-labelledby="staff-listing-{{ $loop->iteration }}"
                data-bs-parent="#accordion{{ $loop->iteration }}" style="">
                <div class="accordion-body">
                    <div class="d-flex">
                        <div class="w-50">Name</div>
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
