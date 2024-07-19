<div class="mx-3 p-1" style="display: {{ count($members) > 0 ? 'block' : 'none' }}">
    <input type="checkbox" class="mainCheckbox">&nbsp;&nbsp;&nbsp;
</div>
@forelse ($members as $member)
<div class="accordion accordion-flush tr-{{ $member->id }}" id="accordion{{ $loop->iteration }}">
    <div class="accordion-item">
        <h2 class="accordion-header" id="staff-listing-{{ $loop->iteration }}">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#flush-collapse{{ $loop->iteration }}" aria-expanded="false"
                aria-controls="flush-collapse{{ $loop->iteration }}">
                @include('components.accordion-label', [
                    'id' => $member->id,
                    'name' => $member->name,
                    'edit' => route('staff.edit', $member->id),
                    'show' => route('staff.show', $member->id),
                ])
            </button>
        </h2>
        <div id="flush-collapse{{ $loop->iteration }}" class="accordion-collapse collapse"
            aria-labelledby="staff-listing-{{ $loop->iteration }}"
            data-bs-parent="#accordion{{ $loop->iteration }}" style="">
            <div class="accordion-body">
                <div class="d-flex accordion-row">
                    <div class="w-50 label">{{ __('staff.nameTh') }}</div>
                    <div class="w-50">{{ $member->name ?? '-' }}</div>
                </div><hr>
                <div class="d-flex accordion-row">
                    <div class="w-50 label">{{ __('staff.birthDateTh') }}</div>
                    <div class="w-50">{{ $member->dob ?? '-' }}</div>
                </div><hr>
                <div class="d-flex accordion-row">
                    <div class="w-50 label">{{ __('staff.addressTh') }}</div>
                    <div class="w-50">{{ $member->address ?? '-' }}</div>
                </div><hr>
                <div class="d-flex accordion-row">
                    <div class="w-50 label">{{ __('staff.telephoneTh') }}</div>
                    <div class="w-50">{{ $member->telephone ?? '-' }}</div>
                </div><hr>
                <div class="d-flex accordion-row">
                    <div class="w-50 label">{{ __('staff.emailTh') }}</div>
                    <div class="w-50">{{ $member->email ?? '-' }}</div>
                </div><hr>
                <div class="d-flex accordion-row">
                    <div class="w-50 label">{{ __('staff.professionTh') }}</div>
                    <div class="w-50">{{ @$member->profession->name ?? '-' }}</div>
                </div><hr>
                <div class="d-flex accordion-row">
                    <div class="w-50 label">{{ __('staff.licenceNumberTh') }}</div>
                    <div class="w-50">{{ $member->licence_number ?? '-' }}</div>
                </div><hr>
                <div class="d-flex accordion-row">
                    <div class="w-50 label">{{ __('staff.roleTh') }}</div>
                    <div class="w-50">{{ $member->getRoleNames()->first() ?? '-' }}</div>
                </div><hr>
                <div class="d-flex accordion-row">
                    <div class="w-50 label">{{ __('staff.kindergartenTh') }}</div>
                    <div class="w-50">
                        @foreach ($member->staffKindergartens as $staffKindergarten)
                            {{ @$staffKindergarten->kindergartens->name }} {{ !$loop->last ? ',' : '' }}
                        @endforeach 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@empty
    <div class="text-center"> {{ __('comon.emptyTableMsg') }} </div>
@endforelse
<div class="dataTables_paginate paging_simple_numbers mt-2" id="paginate">
    @include('components.pagination', ['paginate' => $members])
</div>
