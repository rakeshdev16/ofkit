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
                        <div class="col-md-6"><label for=""><b>{{ __('children.childName') }}:</b></label> {{ $mainChildren->name }}</div>
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
                                        <h4>{{ __('children.' . $document->type) }}</h4>
                                        <a href="{{ route('children-documentation.get', [$document->type, Request::segment(2), $document->id]) }}" class="btn button">{{ __('comon.edit') }}</a>
                                    </div>
                                    <hr class="my-4">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                            <h6 class="mb-0">{{ __('children.date') }}</h6>
                                            <span class="text-secondary">{{ @date('d/m/Y', strtotime($document->date)) ?? '-' }}</span>
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
                                            <span class="text-secondary">{{ @$document->occured == 1 ? 'Yes' : 'No' }}</span>
                                        </li>
                                        @if ($document->occured == 1 && $document->type == 'group')
                                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                <h6 class="mb-0">{{ __('children.groupName') }}</h6>
                                                <span class="text-secondary">{{ @$document->group_name ? $document->group_name : '-' }}</span>
                                            </li>
                                        @endif
                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                            <h6 class="mb-0">{{ __('children.description') }}</h6>
                                            <span class="text-secondary doc-desc">{{ @$document->occured_description ? $document->occured_description : $document->occured_reason }}</span>
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
                                                                <th width="15%">{{ __('children.name') }}</th>
                                                                <th width="10%">{{ __('children.participated') }}</th>
                                                                <th width="70%">{{ __('children.description') }}</th>
                                                                <th width="5%">{{ __('children.attactedFile') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="selected-kindergarten">
                                                            @if ($document->groupChildrens->isEmpty())
                                                                <td class="text-center" colspan="5">{{ __('children.noChildrenFound') }}</td>
                                                            @else
                                                                @php
                                                                    $children = $document->groupChildrens->where('children_id', $mainChildren->id)->first();
                                                                @endphp
                                                                {{-- @foreach ($document->groupChildrens as $child) --}}
                                                                <tr>
                                                                    <td>{{ $children->child->name }}</td>
                                                                    <td>{{ $children->participated == 1 ? 'Yes' : 'No' }}</td>
                                                                    <td>
                                                                        <span class="wrap-desc" style="width: 90%; display: inline-block; white-space: normal;">
                                                                            {{ $children->description ?? $children->reason }}
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        @if (!empty($children->file))
                                                                            <a href="{{ asset('storage/' . $children->file) }}" target="_blank">
                                                                                <h4><i class="bx bx-file"></i></h4>
                                                                            </a>
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                {{-- @endforeach --}}
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
                                                                    <th width="5%">{{ __('children.children') }}</th>
                                                                    <th width="15%">{{ __('children.topic') }}</th>
                                                                    <th width="10%">{{ __('children.discussion') }}</th>
                                                                    <th width="70%">{{ __('children.decisions') }}</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="selected-kindergarten">
                                                                @if ($document->staffMeeting->isEmpty())
                                                                    <td class="text-center" colspan="5">{{ __('children.noChildrenFound') }}</td>
                                                                @else
                                                                    @foreach ($document->staffMeeting as $staffMeeting)
                                                                        <tr>
                                                                            <td>{{ getChildrenNameById($staffMeeting->children_id) ?? '-' }}</td>
                                                                            <td>{{ $staffMeeting->topic ?? '-' }}</td>
                                                                            <td>{{ $staffMeeting->discussion ?? '-' }}</td>
                                                                            <td>{{ $staffMeeting->decisions ?? '-' }}</td>
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
                                                                        {{ @$therapist->therapist }}
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
