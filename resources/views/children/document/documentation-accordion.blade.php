<div class="mx-2" style="display: {{ count($documentations) > 0 ? 'block' : 'none' }}">
    <input type="checkbox" class="mainCheckbox">&nbsp;&nbsp;&nbsp;
</div>
@forelse ($documentations as $documentation)
    @php
        $truncatedDesc = \Str::limit($documentation->occured_description, 80, '...');
        $groupChildDetail = getDocGroupChildDetail($documentation->id, $children->id);
        $therapist_ids = $documentation->groupTherapist->pluck('therapist_id')->toArray();
    @endphp
    <div class="accordion accordion-flush tr-{{ $documentation->id }}" id="accordion{{ $loop->iteration }}">
        <div class="accordion-item">
            <h2 class="accordion-header" id="staff-listing-{{ $loop->iteration }}">
                <button class="accordion-button accordion-screen collapsed {{$documentation->status == 'inactive' ? $documentation->status : ''}}" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse{{ $loop->iteration }}" aria-expanded="false" aria-controls="flush-collapse{{ $loop->iteration }}">
                    @php
                        $data = [
                            'id' => $documentation->id,
                            'name' => ucfirst(str_replace('-', ' ', $documentation->type ? __('children.'.$documentation->type) : '-')),
                            'show' => route('children-documentation.show', [$documentation->children_id, $documentation->id, Request::segment(2)]),
                        ];

                        $authKindergartens = Auth::user()->staffKindergartens->pluck('kindergarten_id')->toArray();
                        if (Auth::user()->hasRole('manager') && $documentation->created_at->diffInHours() < 24 && in_array($documentation->kindergarten_id, $authKindergartens)){
                            $data['edit'] = route('children-documentation.get', [$documentation->type, Request::segment(2), $documentation->id]);
                        }

                        if (Auth::user()->hasRole('therapist') && Auth::id() == $documentation->therapist_id && $documentation->created_at->diffInHours() < 24){
                            $data['edit'] = route('children-documentation.get', [$documentation->type, Request::segment(2), $documentation->id]);
                        }

                        if (Auth::user()->hasRole('admin')){
                            $data['edit'] = route('children-documentation.get', [$documentation->type, Request::segment(2), $documentation->id]);
                        }

                    @endphp
                    @include('components.accordion-label', $data)
                </button>
            </h2>

            <div id="flush-collapse{{ $loop->iteration }}" class="accordion-collapse collapse" aria-labelledby="staff-listing-{{ $loop->iteration }}" data-bs-parent="#accordion{{ $loop->iteration }}" style="">
                <div class="accordion-body">
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('children.date') }}</div>
                        <div class="w-50">{{ @date('d/m/Y', strtotime($documentation->date)) ?? '-' }}</div>
                    </div>
                    <hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('children.therapist') }}</div>
                        {{-- <div class="w-50">{{ @$documentation->therapist->name ?? '-' }}</div> --}}
                        <div class="w-50">
                            @if ($documentation->therapist != null)
                                {{ $documentation->therapist->name ?? '-' }}
                            @else
                                {!! description(getUserNameByIds($therapist_ids), 80) !!}
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('children.profession') }}</div>
                        <div class="w-50">{{ @$documentation->therapist->profession->name ?? '-' }}</div>
                    </div>
                    <hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('children.intervention') }}</div>
                        <div class="w-50">{{ ucfirst(str_replace('-', ' ', $documentation->type ? __('children.'.$documentation->type) : '-')) }}</div>
                    </div>
                    <hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('children.occurred') }}</div>
                        <div class="w-50">
                            @if ($documentation->type == 'group' && $groupChildDetail)
                                {{ $groupChildDetail->participated == 1 ? __('comon.yes') : __('comon.no') }}
                            @else
                                {{ $documentation->occured == 1 ? __('comon.yes') : __('comon.no') }}
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('children.description') }}</div>
                        <div class="w-50">
                            {{-- @if ($documentation->occured == 1)
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
                            @endif --}}
                            {{-- @if (!empty($documentation->group_name))
                                @php
                                    if (isset($groupChildDetail) && isset($groupChildDetail->participated) && $groupChildDetail->participated == 1) {
                                        $description = $groupChildDetail->description;
                                    } else {
                                        $description = @$groupChildDetail->reason;
                                    }
                                @endphp
                                {!! description($description, 80) !!} :{{ $documentation->group_name }}
                            @else
                                @if ($documentation->occured == 1)
                                    {!! description($documentation->occured_description, 80) !!}
                                @else
                                    {{ $documentation->occured_reason }}
                                @endif
                            @endif --}}
                            @if (!empty($documentation->group_name))
                                @php
                                    if (isset($groupChildDetail) && isset($groupChildDetail->participated) && $groupChildDetail->participated == 1 || $documentation->occured_description) {
                                        $description = $groupChildDetail->description ? $groupChildDetail->description : $documentation->occured_description;
                                    } else {
                                        $description = @$groupChildDetail->reason;
                                    }
                                @endphp
                                {!! description($description, 80) !!} :{{ $documentation->group_name }}
                            @else
                                {{-- @if ($documentation->occured == 1) --}}
                                {{-- {{$documentation->occured_description}} ? {!! description($documentation->occured_description, 80) !!} : {{ $documentation->occured_reason }} --}}
                                {!! $documentation->occured_description ? description($documentation->occured_description, 80) : $documentation->occured_reason !!}

                                {{-- @else
                                    {{ $documentation->occured_reason }}
                                @endif --}}
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('children.attactedFile') }}</div>
                        <div class="w-50">
                            <!-- @if (!empty($documentation->file))
                                <a href="{{ $documentation->file }}" target="_blank">
                                    <h4><i class="bx bx-file"></i></h4>
                                </a>
                            @else
                                -
                            @endif -->
                            @php
                                $docExt = pathinfo($documentation->file, PATHINFO_EXTENSION);
                                $groupDocExt = pathinfo(@$groupChildDetail->file, PATHINFO_EXTENSION);
                            @endphp
                            @if ($documentation->file)
                                @if ($docExt == 'xlsx' || $docExt == 'docx' || $docExt == 'odt')

                                @else
                                    <a href="{{ $documentation->file }}" target="_blank">
                                        <h4><i class="bx bx-file"></i></h4>
                                    </a>
                                @endif
                            @endif
                            @if ($groupChildDetail && $groupChildDetail->file)
                                @if ($docExt == 'xlsx' || $docExt == 'docx' || $docExt == 'odt')

                                @else
                                    <a href="{{ asset('storage/' . @$groupChildDetail->file) }}" target="_blank">
                                        <h4><i class="bx bx-file"></i></h4>
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                    <hr>
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
