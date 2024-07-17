<div class="mx-3 p-1">
    <input type="checkbox" class="mainCheckbox">&nbsp;&nbsp;&nbsp;
</div>
@foreach ($childrens as $children)
    <div class="accordion accordion-flush tr-{{ $children->id }}" id="accordion{{ $loop->iteration }}">
        <div class="accordion-item">
            <h2 class="accordion-header" id="staff-listing-{{ $loop->iteration }}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapse{{ $loop->iteration }}" aria-expanded="false"
                    aria-controls="flush-collapse{{ $loop->iteration }}">
                    <div class="row">
                        <div class="col-2">
                            <input type="checkbox" value="{{ $children->id }}" class="checkbox">&nbsp;&nbsp;
                        </div>
                        <div class="col-8">{{ @$children->name ?? '-' }}</div>
                        <div class="col-2 d-flex justify-content-center">
                            <a href="{{ route('children.edit', $children->id) }}" class=""><i class="bx bx-edit icon"></i></a>
                        </div>
                    </div>
                </button>
            </h2>
            <div id="flush-collapse{{ $loop->iteration }}" class="accordion-collapse collapse"
                aria-labelledby="staff-listing-{{ $loop->iteration }}"
                data-bs-parent="#accordion{{ $loop->iteration }}" style="">
                <div class="accordion-body">
                    <div class="d-flex">
                        <div class="w-50">{{ __('children.nameTh') }}</div>
                        <div class="w-50">{{ $children->name }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-50">{{ __('children.familyNameTh') }}</div>
                        <div class="w-50">{{ $children->family_name }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-50">{{ __('children.identificationTh') }}</div>
                        <div class="w-50">{{ $children->identification }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-50">{{ __('children.dobTh') }}</div>
                        <div class="w-50">{{ $children->dob }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-50">{{ __('children.addressTh') }}</div>
                        <div class="w-50">{{ $children->address }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-50">Kindergarten</div>
                        <div class="w-50">{{ $children->kindergarten_id }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
<div class="dataTables_paginate paging_simple_numbers mt-2" id="paginate">
    @include('components.pagination', ['paginate' => $childrens])
</div>
