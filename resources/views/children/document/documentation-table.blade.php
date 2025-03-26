<table id="staffTable" class="table table-style table-bordered" style="width:100%">
    <thead>
        @php
            $date = ['label' => __('children.date')];
            $therapist = ['label' => __('children.therapist')];
            $profession = ['label' => __('children.profession')];
            $intervention = ['label' => __('children.intervention')];
            $occurred = ['label' => __('children.occurred')];
            $description = ['label' => __('children.description')];
            $attactedFile = ['label' => __('children.attactedFile'), 'width' => '20px'];
            $action = ['label' => __('comon.action'), 'width' => '20px'];
            if (Auth::user()->hasRole('admin')) {
                $date['key'] = 'date';
                $therapist['key'] = 'therapist_id';
                $intervention['key'] = 'type';
                $occurred['key'] = 'occured';
            }
        @endphp
        <tr>
            <th><input type="checkbox" class="mainCheckbox"></th>
            @include('components.table-heading', $date)
            @include('components.table-heading', $therapist)
            @include('components.table-heading', $profession)
            @include('components.table-heading', $intervention)
            @include('components.table-heading', $occurred)
            @include('components.table-heading', $description)
            @include('components.table-heading', $attactedFile)
            @include('components.table-heading', $action)
        </tr>
    </thead>
    <tbody>
        @forelse ($documentations as $documentation)
            @php
                $therapist_ids = $documentation->groupTherapist->pluck('therapist_id')->toArray();
                $groupChildDetail = getDocGroupChildDetail($documentation->id, $children->id);
            @endphp
            <tr class="tr-{{ $documentation->id }} {{$documentation->status == 'inactive' ? $documentation->status : ''}}">
                <td><input type="checkbox" name="id[]" value="{{ $documentation->id }}" class="checkbox check-{{ $documentation->id }}" data-class="check-{{ $documentation->id }}"></td>
                <td>{{ $documentation->date }}</td>
                <td>
                    @if ($documentation->therapist != null)
                        {{ $documentation->therapist->name ?? '-' }}
                    @else
                        {!! description(getUserNameByIds($therapist_ids), 80) !!}
                    @endif
                </td>
                <td>{{ $documentation->therapist->profession->name ?? '-' }}</td>
                <td>{{ ucfirst(str_replace('-', ' ', $documentation->type ? __('children.'.$documentation->type) : '-')) }}</td>
                <td>
                    @if ($documentation->type == 'group' && $groupChildDetail)
                        {{ $groupChildDetail->participated == 1 ? __('comon.yes') : __('comon.no') }}
                    @else
                        {{ $documentation->occured == 1 ? __('comon.yes') : __('comon.no') }}
                    @endif
                </td>
                <td class="{{ $documentation->occured == 1 ? 'address-column' : '' }}">
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
                        {{$documentation->occured == 0 ? $documentation->occured_reason : ''}}@if ($documentation->occured == 0 && $documentation->occured_description != null): @endif {!! $documentation->occured_description ? description($documentation->occured_description, 80) : '' !!}
                    @endif
                </td>
                <td class="">
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
                </td>
                <td>
                    <a href="{{ route('children-documentation.show', [$documentation->children_id, $documentation->id, Request::segment(2)]) }}" data-toggle="tooltip" data-placement="bottom" title="{{ __('comon.view') }}">
                        <i class="bx bx-show icon"></i>
                    </a>
                    @php
                        $authKindergartens = Auth::user()->staffKindergartens->pluck('kindergarten_id')->toArray();
                    @endphp
                    @if (Auth::user()->hasRole('manager') && $documentation->created_at->diffInHours() < 24 && in_array($documentation->kindergarten_id, $authKindergartens))
                        <a href="{{ route('children-documentation.get', [$documentation->type, Request::segment(2), $documentation->id]) }}" data-toggle="tooltip" data-placement="bottom" title="Edit">
                            <i class="bx bx-edit icon"></i>
                        </a>
                    @endif

                    @if (Auth::user()->hasRole('therapist') && Auth::id() == $documentation->therapist_id && $documentation->created_at->diffInHours() < 24)
                        <a href="{{ route('children-documentation.get', [$documentation->type, Request::segment(2), $documentation->id]) }}" data-toggle="tooltip" data-placement="bottom" title="Edit">
                            <i class="bx bx-edit icon"></i>
                        </a>
                    @endif

                    @if (Auth::user()->hasRole('admin'))
                        <a href="{{ route('children-documentation.get', [$documentation->type, Request::segment(2), $documentation->id]) }}" data-toggle="tooltip" data-placement="bottom" title="Edit">
                            <i class="bx bx-edit icon"></i>
                        </a>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">{{ __('comon.emptyTableMsg') }}</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="dataTables_paginate paging_simple_numbers mt-3" id="paginate">
    @include('components.pagination', ['paginate' => $documentations])
</div>
