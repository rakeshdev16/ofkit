<div class="mx-3 p-1">
    <input type="checkbox" class="mainCheckbox">&nbsp;&nbsp;&nbsp;
    {{-- <label for="">{{ __('staff.mainCheckboxLabel') }}</label> --}}
</div>
@foreach ($members as $member)
    <div class="accordion accordion-flush tr-{{ $member->id }}" id="accordion{{ $loop->iteration }}">
        <div class="accordion-item">
            <h2 class="accordion-header" id="staff-listing-{{ $loop->iteration }}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapse{{ $loop->iteration }}" aria-expanded="false"
                    aria-controls="flush-collapse{{ $loop->iteration }}">
                    <input type="checkbox" value="{{ $member->id }}" class="checkbox">&nbsp;&nbsp;
                    {{ @$member->kindergarten->name ?? '-' }}
                </button>
            </h2>
            <div id="flush-collapse{{ $loop->iteration }}" class="accordion-collapse collapse"
                aria-labelledby="staff-listing-{{ $loop->iteration }}"
                data-bs-parent="#accordion{{ $loop->iteration }}" style="">
                <div class="accordion-body">
                    <div class="d-flex">
                        <div class="w-50">{{ __('staff.nameTh') }}</div>
                        <div class="w-50">{{ $member->name }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-50">{{ __('staff.birthDateTh') }}</div>
                        <div class="w-50">{{ $member->dob }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-50">{{ __('staff.addressTh') }}</div>
                        <div class="w-50">{{ $member->address }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-50">{{ __('staff.telephoneTh') }}</div>
                        <div class="w-50">{{ $member->telephone }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-50">{{ __('staff.emailTh') }}</div>
                        <div class="w-50">{{ $member->email }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-50">{{ __('staff.professionTh') }}</div>
                        <div class="w-50">{{ $member->profession }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-50">{{ __('staff.licenceNumberTh') }}</div>
                        <div class="w-50">{{ $member->licence_number }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-50">{{ __('staff.roleTh') }}</div>
                        <div class="w-50">{{ $member->getRoleNames()->first() }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-50">{{ __('staff.kindergartenTh') }}</div>
                        <div class="w-50">{{ @$member->kindergarten->name ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
<div class="dataTables_paginate paging_simple_numbers mt-2" id="paginate">
    @include('components.pagination', ['paginate' => $members])
</div>
