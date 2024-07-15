<div class="mx-3 p-1">
    {{-- <input type="checkbox" class="mainCheckbox">&nbsp;&nbsp;&nbsp; --}}
</div>
@foreach ($documents as $document)
    <div class="accordion accordion-flush tr-{{ $document->id }}" id="accordion{{ $loop->iteration }}">
        <div class="accordion-item">
            <h2 class="accordion-header" id="staff-listing-{{ $loop->iteration }}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapse{{ $loop->iteration }}" aria-expanded="false"
                    aria-controls="flush-collapse{{ $loop->iteration }}">
                    {{-- <input type="checkbox" value="{{ $document->id }}" class="checkbox">&nbsp;&nbsp; --}}
                    {{ @$document->name ?? '-' }}&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="{{ route('intervention.edit', $document->id) }}?type=intervention-type" class=""><i class="bx bx-edit icon"></i></a>
                </button>
            </h2>
            <div id="flush-collapse{{ $loop->iteration }}" class="accordion-collapse collapse"
                aria-labelledby="staff-listing-{{ $loop->iteration }}"
                data-bs-parent="#accordion{{ $loop->iteration }}" style="">
                <div class="accordion-body">
                    <div class="d-flex">
                        <div class="w-50">Name</div>
                        <div class="w-50">{{ $document->name }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
<div class="dataTables_paginate paging_simple_numbers mt-2" id="paginate">
    @include('components.pagination', ['paginate' => $documents])
</div>
