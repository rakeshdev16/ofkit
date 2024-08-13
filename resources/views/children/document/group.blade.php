@extends('layout.master')
@push('customLink')
    <link rel="stylesheet" href="{{ asset('assets/css/bs-stepper.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                            <div class="">
                                <a href="{!! URL::previous() !!}" class="btn button">{{ __('comon.back') }}</a>
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
                                                <h5 class="mb-4 steper-title">{{ ucfirst(Request::segment(2)) }}</h5>
                                            </div>
                                            <form action="{{ route('children-documentation.store', ['group', $children->id]) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        @include('components.date-input', [
                                                            'label' => 'Date',
                                                            'name' => 'date',
                                                            'value' => @$document->date,
                                                        ])
                                                    </div>
                                                    <div class="col-md-3">
                                                        @include('components.time-input', [
                                                            'label' => 'Start Time',
                                                            'name' => 'start_time',
                                                            'value' => @$document->start_time,
                                                        ])
                                                    </div>
                                                    <div class="col-md-3">
                                                        @include('components.time-input', [
                                                            'label' => 'End Time',
                                                            'name' => 'end_time',
                                                            'value' => @$document->end_time,
                                                        ])
                                                    </div>
                                                    <div class="col-md-12">
                                                        @include('components.text-input', [
                                                            'label' => 'Kindergarten',
                                                            'name' => 'Kindergarten',
                                                            'icon' => 'user',
                                                            'value' => getKindergartenNameById($children->kindergarten_id),
                                                            'disabled' => 'disabled'
                                                        ])
                                                    </div>
                                                    <div class="col-md-12">
                                                        @include('components.radio-input', [
                                                            'label' => "Occured",
                                                            'name' => 'occured',
                                                            'class' => 'occured',
                                                            'icon' => 'user',
                                                            'value' => @$document->occured,
                                                        ])
                                                    </div>
                                                    <div class="col-md-12 occuredReason" style="display: {{ (old('occured') ?? @$document->occured) == '0' ? 'block' : 'none' }};">
                                                        @include('components.select-input', [
                                                            'label' => 'Reason',
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
                                                            'label' => 'Description',
                                                            'name' => 'occured_description',
                                                            'icon' => 'network-chart',
                                                            'value' => @$document->occured_description,
                                                        ])
                                                    </div>
                                                    <div class="col-md-12">
                                                        @php
                                                            if (isset($document->groupChildrens) && count($document->groupChildrens) > 0) {
                                                                $groupChildrens = $document->groupChildrens->pluck('children_id')->toArray();
                                                            } else {
                                                                $groupChildrens = [];
                                                            }
                                                            
                                                        @endphp
                                                        @include('components.multi-select-input', [
                                                            'label' => 'Add Another Child',
                                                            'name' => 'children_ids[]',
                                                            'class' => 'childrens',
                                                            'icon' => 'user',
                                                            'value' => old('children_ids') ?? $groupChildrens,
                                                            'options' => $childrens,
                                                        ])
                                                    </div>
                                                    <div class="col-md-12 childrenTabSec" style="display:flex; flex-wrap: wrap;">
                                                        @if (!Request::segment(4))
                                                            <span class="child-tab mx-1 childTab">
                                                                {{ @getChildrenNameById($children->id) }}
                                                            </span>
                                                        @endif
                                                        @foreach ($groupChildrens as $id)
                                                            <span class="child-tab mx-1 childTab{{ @$id }}">{{ @getChildrenNameById($id) }}</span>
                                                        @endforeach
                                                    </div>
                                                    <div class="col-md-12 childrenSec">
                                                        @if (!Request::segment(4) && count(old('children_ids')) == 0)
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
                                                            'label' => 'File',
                                                            'name' => 'child_file',
                                                            'class' => 'file',
                                                            'id' => 'file',
                                                            'icon' => 'file',
                                                            'value' => old('file'),
                                                        ])
                                                    </div>
                                                    <input type="hidden" name="kindergarten_id" value="{{ $children->kindergarten_id }}">
                                                    <input type="hidden" name="id" value="{{ @$document->id }}">
                                                    <input type="hidden" name="doc_id" value="{{ Request::segment(4) }}">
                                                    <div class="col-12">
                                                        <div class="d-flex align-items-center gap-3">
                                                            <button type="submit" class="btn button px-4">Submit</button>
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
        <script>
            $(document).ready(function() {
                $('.childrens').select2();
            });
            $(document).on('click', '.button', function() {
                $(this).attr('disabled', false);
            });
            
            $(document).on('change', '.occured', function() {
                var value = $(this).val();
                if (value == 0) {
                    $('.occuredDescription').hide();
                    $('.occuredReason').show();
                } else {
                    $('.occuredReason').hide();
                    $('.occuredDescription').show();
                }
            });

            function childParticipated(element) {
                var value = element.value;
                var row = element.closest('.row');
                var reasonDiv = row.querySelector('.participatedReason');
                var descriptionDiv = row.querySelector('.participatedDescription');
                if (value == '1') {
                    descriptionDiv.style.display = 'block';
                    reasonDiv.style.display = 'none';
                } else {
                    descriptionDiv.style.display = 'none';
                    reasonDiv.style.display = 'block';
                }
            }

            $('.file').change(function(event) {
                const file = event.target.files[0];
                $('.choosenFile').append('<div class="document mt-1"><a href="#" target="_blank" rel="noopener noreferrer">'+ file.name +'</a><i class="bx bx-x childDocument" data-file-name="' + file.name + '"></i></div>');
            });

            $(document).on('click', '.childDocument', function() {
                $(this).parent().remove();
            })

            $('.childrens').on('select2:select', function(e) {
                var id = e.params.data.id;
                var name = e.params.data.text;                
                var index = $('.childrenSec .accordion').length;
                $('.childrenTabSec').append('<span class="child-tab childTab'+id+' mx-1">'+name+'</span>');
                var html = `@include('components.children-participated', [
                    'index' => '${index}',
                    'name' => '${name}',
                    'id' => '${id}',
                    'child_id' => '${id}'
                ])`;
                var $component = $(html);
                setInputsValEmpty($component);
                $('.childrenSec').append($component);
            });

            $('.childrens').on('select2:unselect', function(e) {
                var id = e.params.data.id;                
                var index = $('.childrenSec .accordion').length;
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
                $component.find('#previewImage').remove();
            }
        </script>
    @endpush
