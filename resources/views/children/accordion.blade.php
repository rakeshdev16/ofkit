<div class="mx-3 p-1" style="display: {{ count($childrens) > 0 ? 'block' : 'none' }}">
    <input type="checkbox" class="mainCheckbox">&nbsp;&nbsp;&nbsp;
</div>
@forelse ($childrens as $children)
    <div class="accordion accordion-flush tr-{{ $children->id }}" id="accordion{{ $loop->iteration }}">
        <div class="accordion-item">
            <h2 class="accordion-header" id="staff-listing-{{ $loop->iteration }}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapse{{ $loop->iteration }}" aria-expanded="false"
                    aria-controls="flush-collapse{{ $loop->iteration }}">
                    @include('components.accordion-label', [
                        'id' => $children->id,
                        'name' => $children->name,
                        'edit' => route('children.edit', $children->id),
                        'show' => route('children.show', $children->id),
                    ])
                </button>
            </h2>
            
            <div id="flush-collapse{{ $loop->iteration }}" class="accordion-collapse collapse"
                aria-labelledby="staff-listing-{{ $loop->iteration }}"
                data-bs-parent="#accordion{{ $loop->iteration }}" style="">
                <div class="accordion-body">
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('children.name') }}</div>
                        <div class="w-50">{{ $children->name }}</div>
                    </div><hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('children.familyName') }}</div>
                        <div class="w-50">{{ $children->family_name }}</div>
                    </div><hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('children.identification') }}</div>
                        <div class="w-50">{{ $children->identification }}</div>
                    </div><hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('children.dob') }}</div>
                        <div class="w-50">{{ $children->dob }}</div>
                    </div><hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('children.address') }}</div>
                        <div class="w-50">{{ $children->address }}</div>
                    </div><hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('children.kindergarten') }}</div>
                        <div class="w-50">{{ @getKindergartenNameById($children->kindergarten_id) ?? '-' }}</div>
                    </div>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('children.createdAt') }}</div>
                        <div class="w-50">{{ date('d/m/Y', strtotime($children->created_at)) }}</div>
                    </div>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('children.updatedAt') }}</div>
                        <div class="w-50">{{ date('d/m/Y', strtotime($children->updated_at)) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="text-center"> {{ __('comon.emptyTableMsg') }} </div>
@endforelse
<div class="dataTables_paginate paging_simple_numbers mt-2" id="paginate">
    @include('components.pagination', ['paginate' => $childrens])
</div>
