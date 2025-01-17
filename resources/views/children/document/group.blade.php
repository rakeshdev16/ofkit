@extends('layout.master')
@push('customLink')
    <link rel="stylesheet" href="{{ asset('assets/css/bs-stepper.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @error('children_ids')
        <style>
            span.select2-selection.select2-selection--multiple {
                border-color: #fd3550 !important;
            }
        </style>
    @enderror
    <style>
        .select2-container--default .select2-results__option--selected {
            pointer-events: none !important;
        }
    </style>
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
                                    <li class="breadcrumb-item active" aria-current="page">{{ __('comon.detail') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="ms-auto">
                            @php
                                // if ($document && $document->id) {
                                //     $back = route('children-documentation.show', [$children->id, $document->id]);
                                // } else {
                                // $back = route('children.show', Request::segment(3));
                                if (Request::segment(4)) {
                                    $back = route('children-documentations.get', Request::segment(3));
                                } else {
                                    $back = route('children.show', $children->id);
                                }
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
                                                <h5 class="mb-4 steper-title">{{ __('children.' . Request::segment(2)) }}</h5>
                                            </div>
                                            <form action="{{ route('children-documentation.store', ['group', $children->id]) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row g-3">
                                                    @if (Auth::user()->hasRole('admin'))
                                                        <div class="col-md-6">
                                                            @include('components.multi-select-input', [
                                                                'label' => __('children.therapist'),
                                                                'name' => 'therapist_id[]',
                                                                'class' => 'therapists',
                                                                'icon' => 'buildings',
                                                                'options' => $allTherapists,
                                                                // 'value' => old('therapist_id') ? old('therapist_id') : @$therapist,
                                                                'value' => old('therapist_id', $therapist ?? []),
                                                            ])
                                                        </div>
                                                    @else
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.therapist'),
                                                                'name' => '',
                                                                'icon' => 'user',
                                                                'value' => Auth::user()->name,
                                                                'disabled' => 'disabled',
                                                            ])
                                                            <input type="hidden" name="therapist_id" value="{{ Auth::id() }}">
                                                        </div>
                                                    @endif
                                                    <div class="col-md-6">
                                                        @include('components.date-input', [
                                                            'label' => __('children.date'),
                                                            'name' => 'date',
                                                            'value' => @$document->date,
                                                        ])
                                                    </div>
                                                    <div class="col-md-6">
                                                        @include('components.time-input', [
                                                            'label' => __('children.startTime'),
                                                            'name' => 'start_time',
                                                            'class' => 'startTime',
                                                            'value' => @$document->start_time,
                                                        ])
                                                    </div>
                                                    <div class="col-md-6">
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
                                                            'name' => 'Kindergarten',
                                                            'icon' => 'user',
                                                            'value' => getKindergartenNameById($children->kindergarten_id),
                                                            'disabled' => 'disabled',
                                                        ])
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
                                                    <div class="col-md-12">
                                                        @include('components.text-input', [
                                                            'label' => __('children.groupName'),
                                                            'name' => 'group_name',
                                                            'icon' => 'network-chart',
                                                            'value' => @$document->group_name,
                                                        ])
                                                    </div>
                                                    <div class="col-md-12 occuredReason" style="{{old('occured',@$document->occured) ?? '1' == '1' ? 'display: none' : 'display: block' }}">
                                                        @include('components.select-input', [
                                                            'label' => __('children.occuredReason'),
                                                            'name' => 'occured_reason',
                                                            'icon' => 'buildings',
                                                            'value' => @$document->occured_reason,
                                                            'options' => [['key' => 'Child absent', 'value' => __('children.childAbsent')], ['key' => 'Therapist absent', 'value' => __('children.therapistAbsent')], ['key' => 'Kindergarten closed', 'value' => __('children.kindergartenClosed')], ['key' => 'Parent absent', 'value' => __('children.parentAbsent')], ['key' => 'Other', 'value' => __('children.other')]],
                                                        ])
                                                    </div>
                                                    <div class="col-md-12 occuredDescription">
                                                        <div>

                                                        </div>
                                                        <div class="mt-3">
                                                            @include('components.textarea-input', [
                                                                'label' => __('children.reasonDescription'),
                                                                'name' => 'occured_description',
                                                                'icon' => 'network-chart',
                                                                'value' => @$document->occured_description,
                                                            ])
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="col-md-12">
                                                        @php
                                                            if (isset($document->groupChildrens) && count($document->groupChildrens) > 0) {
                                                                $groupChildrens = $document->groupChildrens->pluck('children_id')->toArray();
                                                            } else {
                                                                $groupChildrens = [];
                                                            }
                                                        @endphp
                                                        @include('components.multi-select-input', [
                                                            'label' => __('children.addAnotherChild'),
                                                            'name' => 'children_ids[]',
                                                            'class' => 'childrens',
                                                            'icon' => 'user',
                                                            'value' => old('children_ids', $groupChildrens),
                                                            'options' => $childrens,
                                                        ])
                                                        @error('children_ids')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{$message}}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-12 childrenTabSec" style="display:flex; flex-wrap: wrap;">
                                                        @if (!Request::segment(4))
                                                            <span class="child-tab mx-1 childTab">
                                                                {{ @getChildrenNameById($children->id) }}
                                                            </span>
                                                        @endif
                                                        @if (old('children_ids'))
                                                            @foreach (old('children_ids') as $id)
                                                                <span class="child-tab mx-1 childTab{{ @$id }}">{{ @getChildrenNameById($id) }}</span>
                                                            @endforeach
                                                        @endif
                                                        @foreach ($groupChildrens as $id)
                                                            <span class="child-tab mx-1 childTab{{ @$id }}">{{ @getChildrenNameById($id) }}</span>
                                                        @endforeach
                                                    </div>
                                                    <div class="col-md-12 childrenSec">
                                                        @if (!Request::segment(4) && empty(old('children_ids')))
                                                            @include('components.children-participated', [
                                                                'index' => 0,
                                                                'name' => @getChildrenNameById($children->id),
                                                                'data' => $groupChildrens,
                                                                'child_id' => $children->id,
                                                            ])
                                                        @endif
                                                        @if (old('children_ids'))
                                                            @php
                                                                $oldChildrenIds = old('children_ids');
                                                                if (!is_array($oldChildrenIds)) {
                                                                    $oldChildrenIds = [];
                                                                }
                                                                array_unshift($oldChildrenIds, $children->id);
                                                                $selectedChildrenIds = $oldChildrenIds;
                                                            @endphp
                                                            @foreach ($selectedChildrenIds as $id)
                                                                @include('components.children-participated', [
                                                                    'index' => $loop->index,
                                                                    'name' => @getChildrenNameById($id),
                                                                    'child_id' => $id,
                                                                    'data' => getGroupChildrens(Request::segment(4), $children->id),
                                                                ])
                                                            @endforeach
                                                        @else
                                                            @foreach ($document->groupChildrens ?? [] as $data)
                                                                @include('components.children-participated', [
                                                                    'index' => $loop->index,
                                                                    'name' => @getChildrenNameById($data['children_id']),
                                                                    'id' => $data['id'],
                                                                    'data' => $data,
                                                                    'child_id' => $data['children_id'],
                                                                ])
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    <div class="col-md-12">
                                                        @include('components.file-input', [
                                                            'label' => __('children.file'),
                                                            'name' => 'child_file',
                                                            'class' => 'file',
                                                            'id' => 'file',
                                                            'icon' => 'file',
                                                            'value' => old('file'),
                                                        ])
                                                        <div class="d-flex mt-2 choosenFile" style="flex-wrap: wrap;">
                                                            @if (old('file'))
                                                                @php
                                                                    $fileName = explode('child-document/', old('file'))[1];
                                                                @endphp
                                                                <div class="document mt-1">
                                                                    <a href="{{ asset('storage/' . old('file')) }}" target="_blank" rel="noopener noreferrer">{{ $fileName }}</a>
                                                                    <i class="bx bx-x childDocument" data-file-name="{{ $fileName }}"></i>
                                                                </div>
                                                                <input type="hidden" name="file" value="{{ old('file') }}">
                                                            @else
                                                                @if (isset($document->file) && $document->file != null)
                                                                    <div class="document mt-1">
                                                                        <a href="{{ $document->file }}" target="_blank" rel="noopener noreferrer">{{ $document->file_name }}</a>
                                                                        <i class="bx bx-x childDocument" data-file-name="{{ $document->file }}"></i>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @if (isset($document->status))
                                                        @include('components.active-inactive-toggle',['statusCheck' => @$document, 'dataName' => ""])
                                                    @endif
                                                    <input type="hidden" name="kindergarten_id" value="{{ $children->kindergarten_id }}">
                                                    <input type="hidden" name="id" value="{{ @$document->id }}">
                                                    <input type="hidden" name="doc_id" value="{{ Request::segment(4) }}">
                                                    <input type="hidden" name="delete_file" class="deleteFile">
                                                    <div class="col-12">
                                                        <div class="d-flex align-items-center gap-3">
                                                            <input type="hidden" name="form_changed" id="formChanged" value="{{ old('form_changed') }}">
                                                            <button type="submit" class="btn docSubmitBtn button px-4">{{ __('comon.submit') }}</button>
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
                // var value = "{{ old('occured') ?? @$document->occured }}";
                // setChildDisabled(value)
            });

            $(document).on('change', '.occured', function() {
                var value = $(this).val();
                // setChildDisabled(value);
            });

            function setChildDisabled(value) {
                if (value == 0) {
                    // $('.childrens').attr('disabled', true).val(null).trigger('change');
                    $('.file').attr('disabled', true);
                    $('.file').val('');
                    $('.child-tab').not(':first').remove();
                    $('.accordion').not(':first').remove();
                    $('.accordion').find('input, select, textarea').attr('disabled', true);
                    $('.accordion').find('input, select, textarea').val('');
                    $('.choosenFile > .document').remove();
                } else {
                    if ($('.childrenTabSec > .childTab').length == 0) {
                        $('.childrenTabSec').append(`<span class="child-tab mx-1 childTab">{{ @getChildrenNameById($children->id) }}</span>`);
                    }
                    if ($('.childrenSec > .accordion').length == 0) {
                        $('.childrenSec').append(`@include('components.children-participated', [
                            'index' => 0,
                            'name' => @getChildrenNameById($children->id),
                            'child_id' => $children->id,
                        ])`);
                    }
                    // $('.childrens').attr('disabled', false);
                    $('.accordion').find('input, select, textarea').attr('disabled', false);
                    $('.file').attr('disabled', false);
                }
            }

            function childParticipated(radio) {
                const row = $(radio).closest('.row');
                const reasonSelect = row.find('.participatedReason select');
                const descriptionTextarea = row.find('.participatedDescription textarea');
                if (radio.value === '0') {
                    reasonSelect.val('');
                    reasonSelect.closest('.col-md-12').show();
                    descriptionTextarea.closest('.col-md-12').hide();
                } else {
                    descriptionTextarea.val('');
                    descriptionTextarea.closest('.col-md-12').show();
                    reasonSelect.closest('.col-md-12').hide();
                }
            }


            $('.childrens').on('select2:select', function(e) {
                var id = e.params.data.id;
                var name = e.params.data.text;
                var index = $('.childrenSec .accordion').length;
                // $(this).find('option[value="' + id + '"]').prop('disabled', true);
                // $(this).trigger('change.select2');
                $('.childrenTabSec').append('<span class="child-tab childTab' + id + ' mx-1">' + name + '</span>');
                var html = `@include('components.children-participated', [
                    'index' => '${index}',
                    'name' => '${name}',
                    'id' => '${id}',
                    'child_id' => '${id}',
                ])`;
                var $component = $(html);
                setInputsValEmpty($component);
                $('.childrenSec').append($component);
            });

            $('.childrens').on('select2:unselect', function(e) {
                var id = e.params.data.id;
                var index = $('.childrenSec .accordion').length;
                // $(this).find('option[value="' + id + '"]').prop('disabled', false);
                // $(this).trigger('change.select2');
                $('.childTab' + id).remove();
                $('.fileSec' + id).remove();
                updateIndexes();
            });

            function updateIndexes() {
                $('.childrenSec .accordion').each(function(index, element) {
                    $(this).find('input[name^="participated"]').each(function() {
                        var name = $(this).attr('name').replace(/\[\d+\]/, '[' + index + ']');
                        $(this).attr('name', name);
                    });
                    $(this).find('select[name^="participated"]').each(function() {
                        var name = $(this).attr('name').replace(/\[\d+\]/, '[' + index + ']');
                        $(this).attr('name', name);
                    });
                    $(this).find('textarea[name^="participated"]').each(function() {
                        var name = $(this).attr('name').replace(/\[\d+\]/, '[' + index + ']');
                        $(this).attr('name', name);
                    });
                });
            }

            function setInputsValEmpty($component) {
                $component.find('input:radio').prop('checked', false);
                $component.find('input:file').val('');
                $component.find('textarea').val('');
                $component.find('select').val('');
                $component.find('.document').remove();
                $component.find('#previewImage').remove();
            }
        </script>
    @endpush
