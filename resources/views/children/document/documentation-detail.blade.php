@extends('layout.master')
@push('customLink')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('section')
    <div class="wrapper">
        <div class="header-wrapper">
            <div class="page-wrapper">
                <div class="page-content">
                    <div class="page-breadcrumb d-flex align-items-center mb-3">
                        <div class="breadcrumb-title pe-3">{{ __('comon.detail') }}</div>
                        <div class="ps-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 p-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('children.index') }}">
                                            <img class="p-1" src="{{ asset('assets/icons/fi_2887367.png') }}" />
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ __('children.document') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="ms-auto">
                            <div class="">
                                <button data-url="{{ route('children-documentations.get', $mainChildren->id) }}" class="btn button exit">{{ __('comon.back') }}</button>
                                {{-- <a href="{!! URL::previous() !!}" class="btn button">{{ __('comon.back') }}</a> --}}
                            </div>
                        </div>
                    </div>
                    <div class="row my-2 mx-1 children-detail">
                        <div class="col-md-6"><label for=""><b>{{ __('children.childName') }}:</b></label> {{ $mainChildren->name.' '.$mainChildren->family_name }}</div>
                        <div class="col-md-6"><label for=""><b>{{ __('children.ID') }}:</b></label> {{ $mainChildren->identification }}</div>
                        <div class="col-md-6"><label for=""><b>{{ __('children.kindergarten') }}:</b></label> {{ getKindergartenNameById($mainChildren->kindergarten_id) }}</div>
                        <div class="col-md-6"><label for=""><b>{{ __('children.childBirthday') }}:</b></label> {{ $mainChildren->date_of_birth }}</div>
                        <div class="col-md-6"><label for=""><b>{{ __('children.childAge') }}:</b></label> {{ $mainChildren->age }}</div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 mx-auto">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mt-2 d-flex justify-content-between">
                                        @php
                                            $authKindergartens = Auth::user()->staffKindergartens->pluck('kindergarten_id')->toArray();
                                        @endphp
                                        <h4>{{ __('children.' . $document->type) }}</h4>
                                        @if (Auth::user()->hasRole('manager') && $document->created_at->diffInHours() < 24 && in_array($mainChildren->kindergarten_id, $authKindergartens))
                                            <a href="{{ route('children-documentation.get', [$document->type, Request::segment(2), $document->id]) }}" class="btn button">{{ __('comon.edit') }}</a>
                                        @endif
                                        @if (Auth::user()->hasRole('therapist') && Auth::id() == $document->therapist_id && $document->created_at->diffInHours() < 24)
                                            <a href="{{ route('children-documentation.get', [$document->type, Request::segment(2), $document->id]) }}" class="btn button">{{ __('comon.edit') }}</a>
                                        @endif
                                        @if (Auth::user()->hasRole('admin'))
                                            <a href="{{ route('children-documentation.get', [$document->type, Request::segment(2), $document->id]) }}" class="btn button">{{ __('comon.edit') }}</a>
                                        @endif
                                    </div>
                                    <hr class="my-4">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                            <h6 class="mb-0">{{ __('children.date') }}</h6>
                                            <span class="text-secondary">{{ $document->date ?? '-' }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                            <h6 class="mb-0">{{ __('children.therapist') }}</h6>
                                            @php
                                                $therapist_ids = $document->groupTherapist->pluck('therapist_id')->toArray();
                                            @endphp
                                            @if ($document->therapist != null)
                                                {{ $document->therapist->name ?? '-' }}
                                            @else
                                                {!! description(getUserNameByIds($therapist_ids), 80) !!}
                                            @endif
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                            <h6 class="mb-0">{{ __('children.profession') }}</h6>
                                            <span class="text-secondary">{{ $document->therapist->profession->name ?? '-' }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                            <h6 class="mb-0">{{ __('children.startTime') }}</h6>
                                            <span class="text-secondary">{{ @$document->start_time ?? '-' }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                            <h6 class="mb-0">{{ __('children.endTime') }}</h6>
                                            <span class="text-secondary">{{ @$document->end_time ?? '-' }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                            <h6 class="mb-0">{{ __('children.kindergarten') }}</h6>
                                            <span class="text-secondary">{{ @getKindergartenNameById($children->kindergarten_id) ?? '-' }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                            <h6 class="mb-0">{{ __('children.occured') }}</h6>
                                            <span class="text-secondary">{{ @$document->occured == 1 ? __('comon.yes') : __('comon.no') }}</span>
                                        </li>
                                        @if ($document->occured == 1 && $document->type == 'group')
                                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                <h6 class="mb-0">{{ __('children.groupName') }}</h6>
                                                <span class="text-secondary">{{ @$document->group_name ? $document->group_name : '-' }}</span>
                                            </li>
                                        @endif
                                        @if(@$document->occured == 0)
                                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                <h6 class="mb-0">{{ __('children.occuredReason') }}</h6>
                                                <span class="text-secondary">{{ @$document->occured_reason }}</span>
                                            </li>
                                        @endif
                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                            <h6 class="mb-0">{{ @$document->occured == 0 ? __('children.reasonDescription') : __('children.description') }}</h6>
                                            <span class="text-secondary doc-desc">{{ @$document->occured_description ? $document->occured_description : '-' }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                            <h6 class="mb-0">{{ __('children.attactedFile') }}</h6>
                                            <span class="text-secondary">
                                                @if (!empty($document->file))
                                                    <a href="{{ $document->file }}" target="_blank">
                                                        <h4><i class="bx bx-file"></i></h4>
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </span>
                                        </li>
                                    </ul>
                                    @if ($document->type == 'group')
                                        <div class="col-md-12 kindergarten-section">
                                            <div class="time-table">
                                                <h4 class="text-center">{{ __('children.children') }}</h4>
                                                <div class="table-responsive" style="display: block !important;">
                                                    <table class="table table-borderd" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('children.fullName') }}</th>
                                                                <th>{{ __('children.participated') }}</th>
                                                                <th>{{ __('children.description') }}</th>
                                                                <th>{{ __('children.attactedFile') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="selected-kindergarten">
                                                            @if ($document->groupChildrens->isEmpty())
                                                                <td class="text-center" colspan="5">{{ __('children.noChildrenFound') }}</td>
                                                            @else
                                                                @php
                                                                    $children = $document->groupChildrens->where('children_id', $mainChildren->id)->first();
                                                                @endphp
                                                                @foreach ($document->groupChildrens as $child)
                                                                <tr>
                                                                    <td>{{ getChildrenNameById($child->children_id) }}</td>
                                                                    <td>{{ $child->participated == 1 ? __('comon.yes') : __('comon.no') }}</td>
                                                                    <td class="address-column">
                                                                        @if ($child->description)
                                                                            {!! description($child->description, 80) !!}
                                                                        @else
                                                                            {{ $child->reason }}
                                                                        @endif
                                                                        {{-- <span class="wrap-desc" style="width: 500px; display: inline-block; white-space: normal;">
                                                                            {{ $child->description ?? $child->reason }}
                                                                        </span> --}}
                                                                    </td>
                                                                    <td>
                                                                        @if (!empty($child->file))
                                                                            <a href="{{ asset('storage/' . $child->file) }}" target="_blank">
                                                                                <h4><i class="bx bx-file"></i></h4>
                                                                            </a>
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($document->type == 'staff-meeting')
                                        <ul class="list-group list-group-flush" style="border-top: 1px solid #dfd8d8">
                                            {{-- <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                <h6 class="mb-0">{{ __('children.topic') }}</h6>
                                                <span class="text-secondary doc-desc">{{ @$document->staffMeeting->topic ?? '-' }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                <h6 class="mb-0">{{ __('children.discussion') }}</h6>
                                                <span class="text-secondary doc-desc">{{ @$document->staffMeeting->discussion ?? '-' }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                <h6 class="mb-0">{{ __('children.decisions') }}</h6>
                                                <span class="text-secondary doc-desc">{{ @$document->staffMeeting->decisions ?? '-' }}</span>
                                            </li> --}}

                                            <div class="col-md-12 kindergarten-section">
                                                <div class="time-table">
                                                    <h4 class="text-center">{{ __('children.children') }}</h4>
                                                    <div class="table-responsive" style="display: block !important;">
                                                        <table class="table table-borderd" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th width="10%">{{ __('children.children') }}</th>
                                                                    <th width="30%">{{ __('children.topic') }}</th>
                                                                    <th width="30%">{{ __('children.discussion') }}</th>
                                                                    <th width="30%">{{ __('children.decisions') }}</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="selected-kindergarten">
                                                                @if ($document->staffMeeting->isEmpty())
                                                                    <td class="text-center" colspan="5">{{ __('children.noChildrenFound') }}</td>
                                                                @else
                                                                    @foreach ($document->staffMeeting as $staffMeeting)
                                                                        {{-- @php
                                                                            $truncatedTopic = \Str::limit($staffMeeting->topic, 30, '...');
                                                                            $truncatedDesc = \Str::limit($staffMeeting->discussion, 30, '...');
                                                                            $truncatedDec = \Str::limit($staffMeeting->decisions, 30, '...');
                                                                        @endphp --}}
                                                                        <tr>
                                                                            <td>{{ getChildrenNameById($staffMeeting->children_id) ?? '-' }}</td>
                                                                            <td class="address-column">
                                                                                @if ($staffMeeting->topic)
                                                                                    {!! description($staffMeeting->topic, 30) !!}
                                                                                @else
                                                                                    -
                                                                                @endif
                                                                            </td>
                                                                            <td class="address-column">
                                                                                @if ($staffMeeting->discussion)
                                                                                    {!! description($staffMeeting->discussion, 30) !!}
                                                                                @else
                                                                                    -
                                                                                @endif
                                                                            </td>
                                                                            <td class="address-column">
                                                                                @if ($staffMeeting->decisions)
                                                                                    {!! description($staffMeeting->decisions, 30) !!}
                                                                                @else
                                                                                    -
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- <div class="col-md-12">
                                                <div class="time-table">
                                                    <h4 class="text-center">{{ __('children.children') }}</h4>
                                                    <div class="table-responsive" style="display: block !important;">
                                                        <div class="d-flex choosenDocument" style="flex-wrap: wrap;">
                                                            @if ($document->staffMeetingChildren->isEmpty())
                                                                {{ __('children.noChildrenFound') }}
                                                            @else
                                                                @foreach ($document->staffMeetingChildren as $child)
                                                                    <div class="document mt-1 doc14">
                                                                        {{ $child->child->name }}
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> --}}
                                            <div class="col-md-12">
                                                <div class="time-table">
                                                    <h4 class="text-center">{{ __('children.participant') }}</h4>
                                                    <div class="table-responsive" style="display: block !important;">
                                                        <div class="d-flex choosenDocument" style="flex-wrap: wrap;">
                                                            @forelse ($document->staffMeetingTherapist as $therapist)
                                                                <div class="document mt-1 doc14">
                                                                    <a href="{{ route('staff.show', $therapist->therapist_id) }}" target="_blank" rel="noopener noreferrer">
                                                                        {{ @$therapist->therapist->name.' ('.(@$therapist->therapist->profession->acronyms).')' }}
                                                                    </a>
                                                                </div>
                                                            @empty
                                                                {{ __('therapist.noTherapistsFound') }}
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .document {
                background: #fff;
            }

            .choosenDocument {
                background: #fff;
                border-radius: 5px;
                padding: 5px;
            }
        </style>
    @endsection
    @push('customScript')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script type="text/javascript" src="{{ asset('assets/js/jquery.validate.js') }}"></script>
        <script>
            $(document).on('click', '#previewImage', function() {
                $('#imgInp').click();
            });
        </script>
    @endpush
