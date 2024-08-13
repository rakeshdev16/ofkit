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
                                                <h5 class="mb-4 steper-title">{{ ucfirst(str_replace('-', ' ', Request::segment(2))) }}</h5>
                                            </div>
                                            <form action="{{ route('children-documentation.store', ['final-evaluation', $children->id]) }}" method="POST" enctype="multipart/form-data">
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
                                                    <div class="col-md-4">
                                                        @include('components.text-input', [
                                                            'label' => 'Kindergarten',
                                                            'name' => 'Kindergarten',
                                                            'icon' => 'user',
                                                            'value' => getKindergartenNameById($children->kindergarten_id),
                                                            'disabled' => 'disabled'
                                                        ])
                                                    </div>
                                                    <div class="col-md-4">
                                                        @include('components.text-input', [
                                                            'label' => "Child's Name",
                                                            'name' => 'name',
                                                            'icon' => 'user',
                                                            'value' => $children->name,
                                                            'disabled' => 'disabled'
                                                        ])
                                                    </div>
                                                    <div class="col-md-4">
                                                        @include('components.text-input', [
                                                            'label' => "Child's Family Name",
                                                            'name' => 'family_name',
                                                            'icon' => 'user',
                                                            'value' => $children->family_name,
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
                                                        @include('components.file-input', [
                                                            'label' => 'File',
                                                            'name' => 'child_file',
                                                            'class' => 'file',
                                                            'id' => 'file',
                                                            'icon' => 'file',
                                                            'value' => old('file'),
                                                        ])
                                                        <div class="d-flex mt-2 choosenFile" style="flex-wrap: wrap;"></div>
                                                    </div>
                                                    <input type="hidden" name="kindergarten_id" value="{{ $children->kindergarten_id }}">
                                                    <input type="hidden" name="id" value="{{ @$document->id }}">
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
        <script>
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

            $('.file').change(function(event) {
                const file = event.target.files[0];
                $('.choosenFile').append('<div class="document mt-1"><a href="#" target="_blank" rel="noopener noreferrer">'+ file.name +'</a><i class="bx bx-x childDocument" data-file-name="' + file.name + '"></i></div>');
            });

            $(document).on('click', '.childDocument', function() {
                $(this).parent().remove();
            })
        </script>
    @endpush
