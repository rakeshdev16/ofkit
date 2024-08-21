<div class="mx-3 p-1" style="display: {{ count($documentations) > 0 ? 'block' : 'none' }}">
    <input type="checkbox" class="mainCheckbox">&nbsp;&nbsp;&nbsp;
</div>
@forelse ($documentations as $documentation)
    <div class="accordion accordion-flush tr-{{ $documentation->id }}" id="accordion{{ $loop->iteration }}">
        <div class="accordion-item">
            <h2 class="accordion-header" id="staff-listing-{{ $loop->iteration }}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapse{{ $loop->iteration }}" aria-expanded="false"
                    aria-controls="flush-collapse{{ $loop->iteration }}">
                    @include('components.accordion-label', [
                        'id' => $documentation->id,
                        'name' => $documentation->name,
                        'edit' => route('children-documentation.get', [$documentation->type, Request::segment(2), $documentation->id]),
                        'show' => route('children-documentation.show', [$documentation->children_id, $documentation->id]),
                    ])
                </button>
            </h2>
            
            <div id="flush-collapse{{ $loop->iteration }}" class="accordion-collapse collapse"
                aria-labelledby="staff-listing-{{ $loop->iteration }}"
                data-bs-parent="#accordion{{ $loop->iteration }}" style="">
                <div class="accordion-body">
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('children.name') }}</div>
                        <div class="w-50">{{ $documentation->name }}</div>
                    </div><hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('children.familyName') }}</div>
                        <div class="w-50">{{ $documentation->family_name }}</div>
                    </div><hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('children.identification') }}</div>
                        <div class="w-50">{{ $documentation->identification }}</div>
                    </div><hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('children.dob') }}</div>
                        <div class="w-50">{{ $documentation->dob }}</div>
                    </div><hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('children.address') }}</div>
                        <div class="w-50">{{ $documentation->address }}</div>
                    </div><hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('children.kindergarten') }}</div>
                        <div class="w-50">{{ @getKindergartenNameById($documentation->kindergarten_id) ?? '-' }}</div>
                    </div>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('children.createdAt') }}</div>
                        <div class="w-50">{{ date('d/m/Y', strtotime($documentation->created_at)) }}</div>
                    </div>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('children.updatedAt') }}</div>
                        <div class="w-50">{{ date('d/m/Y', strtotime($documentation->updated_at)) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="text-center"> {{ __('comon.emptyTableMsg') }} </div>
@endforelse
<div class="dataTables_paginate paging_simple_numbers mt-2" id="paginate">
    @include('components.pagination', ['paginate' => $documentations])
</div>
