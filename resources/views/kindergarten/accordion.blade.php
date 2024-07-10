<div class="mx-3 p-1">
    <input type="checkbox" class="mainCheckbox">&nbsp;&nbsp;&nbsp;
    {{-- <label for="">{{ __('kindergarten.mainCheckboxLabel') }}</label> --}}
</div>
@foreach ($kindergartens as $kindergarten)
    <div class="accordion accordion-flush tr-{{ $kindergarten->id }}" id="accordion{{ $loop->iteration }}">
        <div class="accordion-item">
            <h2 class="accordion-header" id="staff-listing-{{ $loop->iteration }}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapse{{ $loop->iteration }}" aria-expanded="false"
                    aria-controls="flush-collapse{{ $loop->iteration }}">
                    <input type="checkbox" value="{{ $kindergarten->id }}" class="checkbox">&nbsp;&nbsp;
                    {{ $kindergarten->name }}
                </button>
            </h2>
            <div id="flush-collapse{{ $loop->iteration }}" class="accordion-collapse collapse"
                aria-labelledby="staff-listing-{{ $loop->iteration }}"
                data-bs-parent="#accordion{{ $loop->iteration }}" style="">
                <div class="accordion-body">
                    <div class="d-flex">
                        <div class="w-50">{{ __('kindergarten.telephoneTh') }}</div>
                        <div class="w-50">{{ $kindergarten->telephone }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-50">{{ __('kindergarten.addressTh') }}</div>
                        <div class="w-50">{{ $kindergarten->address }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-50">{{ __('kindergarten.kindergartenManagerTh') }}</div>
                        <div class="w-50">{{ @getUserNameById($kindergarten->kindergartenUser->user_id) ?? '-' }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-50">{{ __('kindergarten.clusterManagerTh') }}</div>
                        <div class="w-50">{{ @getUserNameById($kindergarten->cluster_manager_id) ?? '-' }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-50">{{ __('kindergarten.clusterTh') }}</div>
                        <div class="w-50">{{ @$kindergarten->cluster->cluster ?? '-' }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-50">{{ __('kindergarten.typeTh') }}</div>
                        <div class="w-50">{{ $kindergarten->kindergarten_type }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-50">{{ __('kindergarten.frameworkTh') }}</div>
                        <div class="w-50">{{ $kindergarten->framework_type }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-50">{{ __('kindergarten.symbolTh') }}</div>
                        <div class="w-50">{{ $kindergarten->symbol }}</div>
                    </div>
                    <div class="d-flex">
                        <div class="w-50">{{ __('kindergarten.nameTh') }}</div>
                        <div class="w-50">{{ $kindergarten->name }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
<div class="dataTables_paginate paging_simple_numbers mt-2" id="paginate">
    @include('components.pagination', ['paginate' => $kindergartens])
</div>
