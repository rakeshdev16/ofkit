<div class="mx-3 p-1">
    <input type="checkbox" class="mainCheckbox">&nbsp;&nbsp;&nbsp;
    {{-- <label for="">{{ __('children.mainCheckboxLabel') }}</label> --}}
</div>
@foreach ($childrens as $children)
    <div class="accordion accordion-flush tr-{{ $children->id }}" id="accordion{{ $loop->iteration }}">
        <div class="accordion-item">
            <h2 class="accordion-header" id="staff-listing-{{ $loop->iteration }}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapse{{ $loop->iteration }}" aria-expanded="false"
                    aria-controls="flush-collapse{{ $loop->iteration }}">
                    <input type="checkbox" value="{{ $children->id }}" class="checkbox">&nbsp;&nbsp;
                    {{ $children->kindergarten }}
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
                        <div class="w-50">{{ __('children.birthDateTh') }}</div>
                        <div class="w-50">{{ $children->dob }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-50">{{ __('children.addressTh') }}</div>
                        <div class="w-50">{{ $children->address }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-50">{{ __('children.telephoneTh') }}</div>
                        <div class="w-50">{{ $children->telephone }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-50">{{ __('children.emailTh') }}</div>
                        <div class="w-50">{{ $children->email }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-50">{{ __('children.professionTh') }}</div>
                        <div class="w-50">{{ $children->profession }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-50">{{ __('children.licenceNumberTh') }}</div>
                        <div class="w-50">{{ $children->licence_number }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-50">{{ __('children.roleTh') }}</div>
                        <div class="w-50">{{ $children->licence_number }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-50">{{ __('children.kindergartenTh') }}</div>
                        <div class="w-50">{{ $children->kindergarten }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
<div class="dataTables_paginate paging_simple_numbers mt-2" id="paginate">
    @include('components.pagination', ['paginate' => $childrens])
</div>
