@extends('layout.master')
@push('customLink')
    <link rel="stylesheet" href="{{ asset('assets/css/bs-stepper.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @error('therapist_id')
        <style>
            .therapists-select2 .select2-selection.select2-selection--multiple {
                border-color: #fd3550 !important;
            }
        </style>
    @enderror
    @error('children_ids')
        <style>
            .childrens-select2 .select2-selection.select2-selection--multiple {
                border-color: #fd3550 !important;
            }
        </style>
    @enderror
    
@endpush
@section('section')
    <div class="wrapper">
        <div class="header-wrapper">
            <div class="page-wrapper">
                <div class="page-content">
                    <div class="page-breadcrumb d-flex align-items-center mb-3">
                        <div class="breadcrumb-title pe-3">{{ __('comon.edit') }}</div>
                        <div class="ps-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 p-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('children.index') }}">
                                            <img class="p-1" src="{{ asset('assets/icons/fi_2887367.png') }}" />
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ __('children.children') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="ms-auto">
                            @php
                                // if ($document && $document->id) {
                                //     $back = route('children-documentation.show', [$children->id, $document->id]);
                                // } else {
                                    $back = route('children.show', Request::segment(3));
                                // }
                            @endphp
                            <div class="">
                                <button data-url="{{ $back }}" class="btn button exit">{{ __('comon.back') }}</button>
                                {{-- <a href="{!! URL::previous() !!}" class="btn button">{{ __('comon.back') }}</a> --}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 mx-auto">
                            <div id="" class="">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="bs-stepper-content">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="mb-4 steper-title">{{ ucfirst(str_replace('-', ' ', Request::segment(2))) }}</h5>
                                            </div>
                                            <form action="{{ route('children-documentation.store', ['staff-meeting', $children->id]) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row g-3">
                                                    @php
                                                        if (isset($document->staffMeetingTherapist) && count($document->staffMeetingTherapist) > 0) {
                                                            $therapistIds = $document->staffMeetingTherapist->pluck('therapist_id')->toArray();
                                                        } else {
                                                            $therapistIds = [];
                                                        }
                                                        if (isset($document->staffMeetingChildren) && count($document->staffMeetingChildren) > 0) {
                                                            $childrenIds = $document->staffMeetingChildren->pluck('children_id')->toArray();
                                                        } else {
                                                            $childrenIds = [];
                                                        }
                                                    @endphp
                                                    <div class="col-md-6">
                                                        @include('components.date-input', [
                                                            'label' =>__('children.date'),
                                                            'name' => 'date',
                                                            'value' => @$document->date,
                                                            'max' => date('Y-m-d')
                                                        ])
                                                    </div>
                                                    <div class="col-md-3">
                                                        @include('components.time-input', [
                                                            'label' => __('children.startTime'),
                                                            'name' => 'start_time',
                                                            'class' => 'startTime',
                                                            'value' => @$document->start_time,
                                                        ])
                                                    </div>
                                                    <div class="col-md-3">
                                                        @include('components.time-input', [
                                                            'label' => __('children.endTime'),
                                                            'name' => 'end_time',
                                                            'class' => 'endTime',
                                                            'value' => @$document->end_time,
                                                        ])
                                                    </div>
                                                    <div class="col-md-12">
                                                        @include('components.text-input', [
                                                            'label' => __('children.kindergarten'),
                                                            'name' => 'kindergarten',
                                                            'icon' => 'user',
                                                            'value' => getKindergartenNameById($children->kindergarten_id),
                                                            'disabled' => 'disabled',
                                                        ])
                                                    </div>
                                                    <div class="col-md-12"> 
                                                        @include('components.multi-select-input', [
                                                            'label' => 'Add Therapist',
                                                            'name' => 'therapist_id[]',
                                                            'class' => 'therapists',
                                                            'icon' => 'user',
                                                            'options' => $therapist,
                                                            'value' => old('therapist_id') ?? $therapistIds,
                                                        ])
                                                    </div>
                                                    <div class="col-md-12 therapistTabSec" style="display:flex; flex-wrap: wrap;">
                                                        @if (old('therapist_id') && count(old('therapist_id')) > 0)
                                                            @foreach (old('therapist_id') as $therapistId)
                                                                <span class="child-tab therapistTab{{$therapistId}} mx-1">{{ getUserNameById($therapistId) }}</span>
                                                            @endforeach
                                                        @else
                                                            @if (isset($therapistIds) && count($therapistIds) > 0)
                                                                @foreach ($therapistIds as $therapistId)
                                                                    <span class="child-tab therapistTab{{$therapistId}} mx-1">{{ getUserNameById($therapistId) }}</span>
                                                                @endforeach
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <div class="col-md-12">
                                                        @include('components.radio-input', [
                                                            'label' => __('children.occured'),
                                                            'name' => 'occured',
                                                            'class' => 'occured',
                                                            'icon' => 'user',
                                                            'value' => @$document->occured,
                                                        ])
                                                    </div>
                                                    <div class="col-md-12 occuredReason" style="display: {{ (old('occured') ?? @$document->occured) == '0' ? 'block' : 'none' }};">
                                                        @include('components.select-input', [
                                                            'label' => __('children.occuredReason'),
                                                            'name' => 'occured_reason',
                                                            'icon' => 'buildings',
                                                            'value' => @$document->occured_reason,
                                                            'options' => [
                                                                ['key' => 'Child absent', 'value' => 'Child absent'],
                                                                ['key' => 'Therapist absent', 'value' => 'Therapist absent'],
                                                                ['key' => 'Kindergarten closed', 'value' => 'Kindergarten closed'],
                                                                ['key' => 'Other', 'value' => 'Other'],
                                                            ]
                                                        ])
                                                    </div>
                                                    <div class="col-md-12 occuredDescription" style="display: {{ (old('occured') ?? @$document->occured) == '1' ? 'block' : 'none' }};">
                                                        @include('components.textarea-input', [
                                                            'label' => __('children.description'),
                                                            'name' => 'occured_description',
                                                            'icon' => 'network-chart',
                                                            'value' => @$document->occured_description,
                                                        ])
                                                    </div>
                                                    <div class="col-md-12">
                                                        @include('components.multi-select-input', [
                                                                'label' => 'Add Another Child',
                                                                'name' => 'children_ids[]',
                                                                'class' => 'childrens',
                                                                'icon' => 'user',
                                                                'options' => $childrens,
                                                                'value' => old('children_ids') ?? $childrenIds,
                                                            ])
                                                    </div>
                                                    <div class="col-md-12 childrenTabSec" style="display:flex; flex-wrap: wrap;">
                                                        <span class="child-tab mx-1 childTab">{{ $children->name }}</span>
                                                        @if (old('children_ids') && count(old('children_ids')) > 0)
                                                            @foreach (old('children_ids') as $childrenId)
                                                                <span class="child-tab childTab{{$childrenId}} mx-1">{{ getChildrenNameById($childrenId) }}</span>
                                                            @endforeach
                                                        @else
                                                            @if (isset($childrenIds) && count($childrenIds) > 0)
                                                                @foreach ($childrenIds as $childrenId)
                                                                    <span class="child-tab childTab{{ $childrenId }} mx-1">{{ @getChildrenNameById($childrenId) }}</span>
                                                                @endforeach
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <div class="col-md-12 topicSection">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                @include('components.textarea-input', [
                                                                    'label' =>  'Add Topic',
                                                                    'name' => 'topic',
                                                                    'icon' => 'notepad',
                                                                    'value' => @$document->staffMeeting->topic,
                                                                ])
                                                            </div>
                                                            <div class="col-md-12">
                                                                @include('components.textarea-input', [
                                                                    'label' =>  'Add Discussion',
                                                                    'name' => 'discussion',
                                                                    'icon' => 'group',
                                                                    'value' => @$document->staffMeeting->discussion,
                                                                ])
                                                            </div>
                                                            <div class="col-md-12">
                                                                @include('components.textarea-input', [
                                                                    'label' =>  'Add Decisions',
                                                                    'name' => 'decisions',
                                                                    'icon' => 'user-check',
                                                                    'value' => @$document->staffMeeting->decisions,
                                                                ])
                                                            </div>
                                                            <div class="col-md-12">
                                                                @include('components.file-input', [
                                                                    'label' => __('children.file'),
                                                                    'name' => 'child_file',
                                                                    'class' => 'file',
                                                                    'id' => 'file',
                                                                    'icon' => 'file',
                                                                    'value' => old('file'),
                                                                    'value' => @$document->file,
                                                                ])
                                                                <div class="d-flex mt-2 choosenFile" style="flex-wrap: wrap;">
                                                                    @if (isset($document->file) && $document->file != NULL)
                                                                        <div class="document mt-1">
                                                                            <a href="{{ $document->file }}" target="_blank" rel="noopener noreferrer">{{ $document->file_name }}</a>
                                                                            <i class="bx bx-x childDocument" data-file-name="{{ $document->file }}"></i>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="staff_meeting_id" value="{{ @$document->staffMeeting->id }}">
                                                    <input type="hidden" name="kindergarten_id" value="{{ @$children->kindergarten_id }}">
                                                    <input type="hidden" name="id" value="{{ @$document->id }}">
                                                    <input type="hidden" name="delete_file" class="deleteFile">
                                                    <div class="col-12">
                                                        <div class="d-flex align-items-center gap-3">
                                                            <input type="hidden" name="form_changed" id="formChanged" value="{{ old('form_changed') }}">
                                                            <button type="submit" class="btn button px-4">{{ __('comon.submit') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @push('customScript')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        @include('children.document.script')
        <script>
            $(document).ready(function() {
                $('.childrens').select2();
                $('.therapists').select2();

                $('.childrens').next('.select2-container').addClass('childrens-select2');
                $('.therapists').next('.select2-container').addClass('therapists-select2');

                var value = "{{ old('occured') ?? @$document->occured }}";
                setChildDisabled(value)
            });

            $(document).on('change', '.occured', function() {
                var value = $(this).val();
                setChildDisabled(value);
            });

            function setChildDisabled(value) {
                if (value == 0) {
                    $('.topicSection').find('textarea').attr('disabled', true);
                    $('.topicSection').find('textarea').val('');
                    $('.file').attr('disabled', true);
                    $('.file').val('');
                    $('.choosenFile > .document').remove();
                } else {
                    $('.topicSection').find('textarea').attr('disabled', false);
                    $('.file').attr('disabled', false);
                }
            }

            $('.childrens').on('select2:select', function(e) {
                var id = e.params.data.id;
                var name = e.params.data.text;
                $('.childrenTabSec').append('<span class="child-tab childTab'+id+' mx-1">'+name+'</span>');
            });

            $('.childrens').on('select2:unselect', function(e) {
                var id = e.params.data.id;
                console.log(id);
                
                $('.childTab' + id).remove();
            });
            
            $('.therapists').on('select2:select', function(e) {
                var id = e.params.data.id;
                var name = e.params.data.text;
                $('.therapistTabSec').append('<span class="child-tab therapistTab'+id+' mx-1">'+name+'</span>');
            });

            $('.therapists').on('select2:unselect', function(e) {
                var id = e.params.data.id;
                $('.therapistTab' + id).remove();
            });
        </script>
    @endpush
