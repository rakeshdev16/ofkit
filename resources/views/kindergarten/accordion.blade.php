<div class="mx-2" style="display: {{ count($kindergartens) > 0 ? 'block' : 'none' }}">
    <input type="checkbox" class="mainAccordionCheckbox">&nbsp;&nbsp;&nbsp;
</div>
@forelse ($kindergartens as $kindergarten)
    <div class="accordion accordion-flush tr-{{ $kindergarten->id }}" id="accordion{{ $loop->iteration }}">
        <div class="accordion-item">
            <h2 class="accordion-header" id="staff-listing-{{ $loop->iteration }}">
                <button class="accordion-button accordion-screen collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapse{{ $loop->iteration }}" aria-expanded="false"
                    aria-controls="flush-collapse{{ $loop->iteration }}">
                    @include('components.accordion-label', [
                        'id' => $kindergarten->id,
                        'name' => $kindergarten->name,
                        'edit' => route('kindergarten.edit', $kindergarten->id),
                        'dataName' => $kindergarten->is_assign ? $kindergarten->name.' has assigned to children or staff' : ''
                    ])
                </button>
            </h2>
            <div id="flush-collapse{{ $loop->iteration }}" class="accordion-collapse collapse"
                aria-labelledby="staff-listing-{{ $loop->iteration }}"
                data-bs-parent="#accordion{{ $loop->iteration }}" style="">
                <div class="accordion-body">
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('kindergarten.nameTh') }}</div>
                        <div class="w-50">{{ $kindergarten->name }}</div>
                    </div><hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('kindergarten.symbolTh') }}</div>
                        <div class="w-50">{{ $kindergarten->symbol }}</div>
                    </div><hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('kindergarten.frameworkTh') }}</div>
                        <div class="w-50">{{ $kindergarten->framework_type }}</div>
                    </div><hr>
                    {{-- <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('kindergarten.typeTh') }}</div>
                        <div class="w-50">{{ $kindergarten->kindergarten_type }}</div>
                    </div><hr> --}}
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('kindergarten.clusterTh') }}</div>
                        <div class="w-50">{{ @$kindergarten->cluster->cluster ?? '-' }}</div>
                    </div><hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('kindergarten.clusterManagerTh') }}</div>
                        <div class="w-50">{{ @getUserNameById($kindergarten->cluster->manager_id) ?? '-' }}</div>
                    </div><hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('kindergarten.kindergartenManagerTh') }}</div>
                        <div class="w-50">{{ @getUserNameById($kindergarten->kindergartenUser->user_id) ?? '-' }}</div>
                    </div><hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('kindergarten.addressTh') }}</div>
                        <div class="w-50">{{ $kindergarten->address }}</div>
                    </div><hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('kindergarten.telephoneTh') }}</div>
                        <div class="w-50">{{ $kindergarten->telephone }}</div>
                    </div>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('kindergarten.createdAt') }}</div>
                        <div class="w-50">{{ date('d/m/Y', strtotime($kindergarten->created_at)) }}</div>
                    </div>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('kindergarten.updatedAt') }}</div>
                        <div class="w-50">{{ date('d/m/Y', strtotime($kindergarten->updated_at)) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="text-center"> {{ __('comon.emptyTableMsg') }} </div>
@endforelse
<div class="dataTables_paginate paging_simple_numbers mt-2" id="paginate">
    @include('components.pagination', ['paginate' => $kindergartens])
</div>
