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
                        'name' => ucfirst(str_replace('-', ' ', $documentation->type)),
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
                        <div class="w-50 label">Date</div>
                        <div class="w-50">{{ @date('d/m/Y', strtotime($documentation->date)) ?? '-' }}</div>
                    </div><hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">Start Time</div>
                        <div class="w-50">{{ @$documentation->start_time ?? '-' }}</div>
                    </div><hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">End Time</div>
                        <div class="w-50">{{ @$documentation->end_time ?? '-' }}</div>
                    </div><hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">Kindergarten</div>
                        <div class="w-50">{{ @getKindergartenNameById($children->kindergarten_id) ?? '-' }}</div>
                    </div><hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">Occured</div>
                        <div class="w-50">{{ @$documentation->occured == 1 ? 'Yes' : 'No' }}</div>
                    </div><hr>
                    @if ($documentation->occured == 1 && $documentation->type == 'group')
                        <div class="d-flex accordion-row">
                            <div class="w-50 label">Group Name</div>
                            <div class="w-50">{{ @$documentation->group_name ? $documentation->group_name : '-' }}</div>
                        </div><hr>
                    @endif
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">Description</div>
                        <div class="w-50">{{ @$documentation->occured_description ? $documentation->occured_description : $documentation->occured_reason }}</div>
                    </div>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">File</div>
                        <div class="w-50">
                            @if(!empty($documentation->file))
                                <a href="{{ $documentation->file }}" target="_blank">
                                    <h4><i class="bx bx-file"></i></h4>
                                </a>
                            @else
                                -
                            @endif
                        </div>
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
