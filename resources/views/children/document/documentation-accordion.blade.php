<div class="mx-3 p-1" style="display: {{ count($documentations) > 0 ? 'block' : 'none' }}">
    <input type="checkbox" class="mainCheckbox">&nbsp;&nbsp;&nbsp;
</div>
@forelse ($documentations as $documentation)
    @php
        $truncatedDesc = \Str::limit($documentation->occured_description, 80, '...');
        $groupChildDetail = getDocGroupChildDetail($documentation->id, $children->id);
    @endphp
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
                        'show' => route('children-documentation.show', [
                            $documentation->children_id,
                            $documentation->id,
                            Request::segment(2)
                        ]),
                    ])
                </button>
            </h2>
            
            <div id="flush-collapse{{ $loop->iteration }}" class="accordion-collapse collapse"
                aria-labelledby="staff-listing-{{ $loop->iteration }}"
                data-bs-parent="#accordion{{ $loop->iteration }}" style="">
                <div class="accordion-body">
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('children.date') }}</div>
                        <div class="w-50">{{ @date('d/m/Y', strtotime($documentation->date)) ?? '-' }}</div>
                    </div><hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('children.therapist') }}</div>
                        <div class="w-50">{{ @$documentation->therapist->name ?? '-' }}</div>
                    </div><hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('children.profession') }}</div>
                        <div class="w-50">{{ @$documentation->therapist->profession->name ?? '-' }}</div>
                    </div><hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('children.intervention') }}</div>
                        <div class="w-50">{{ @ucfirst(str_replace('-', ' ', $documentation->type)) }}</div>
                    </div><hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('children.occurred') }}</div>
                        <div class="w-50">
                            @if ($documentation->type == 'group' && $groupChildDetail)
                                {{ $groupChildDetail->participated == 1 ? 'Yes' : 'No' }}
                            @else
                                {{ $documentation->occured == 1 ? 'Yes' : 'No' }}
                            @endif
                        </div>
                    </div><hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('children.description') }}</div>
                        <div class="w-50">
                            {{ @$documentation->occured_description ? $documentation->occured_description : $documentation->occured_reason }}
                            @if ($documentation->occured == 1)
                                @if ($documentation->type == 'group')
                                    @if ($groupChildDetail)
                                        @php
                                            $truncatedGroupDesc = $groupChildDetail->participated == 1 ? \Str::limit($groupChildDetail->description, 80, '...') : $groupChildDetail->reason;
                                        @endphp
                                        <span data-toggle="tooltip" data-placement="bottom" title="{{ $groupChildDetail->description }}">{{ $truncatedGroupDesc }}</span>
                                    @else
                                        <span data-toggle="tooltip" data-placement="bottom" title="{{ $documentation->occured_description }}">{{ $documentation->group_name }}: <br> {{ $truncatedDesc }}</span>
                                    @endif
                                @else
                                    <span data-toggle="tooltip" data-placement="bottom" title="{{ $documentation->occured_description }}">{{ $truncatedDesc }}</span>
                                @endif
                            @else
                                {{ $documentation->occured_reason }}
                            @endif
                        </div>
                    </div><hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('children.attactedFile') }}</div>
                        <div class="w-50">
                            @if(!empty($documentation->file))
                                <a href="{{ $documentation->file }}" target="_blank">
                                    <h4><i class="bx bx-file"></i></h4>
                                </a>
                            @else
                                -
                            @endif
                        </div>
                    </div><hr>
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
