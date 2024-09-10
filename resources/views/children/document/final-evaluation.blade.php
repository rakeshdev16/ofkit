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
                                $back = route('children-documentations.get', Request::segment(3));
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
                                            <form action="{{ route('children-documentation.store', ['final-evaluation', $children->id]) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row g-3">
                                                    @if (Auth::user()->hasRole('admin'))
                                                        <div class="col-md-6">
                                                            @include('components.select-input', [
                                                                'label' => __('children.therapist'),
                                                                'name' => 'therapist_id',
                                                                'icon' => 'buildings',
                                                                'options' => $allTherapists,
                                                                'value' => old('therapist_id') ? old('therapist_id') : @$document->therapist_id,
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
                                                            'max' => date('Y-m-d'),
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
                                                    <div class="col-md-4">
                                                        @include('components.text-input', [
                                                            'label' => __('children.kindergarten'),
                                                            'name' => 'Kindergarten',
                                                            'icon' => 'user',
                                                            'value' => getKindergartenNameById($children->kindergarten_id),
                                                            'disabled' => 'disabled',
                                                        ])
                                                    </div>
                                                    <div class="col-md-4">
                                                        @include('components.text-input', [
                                                            'label' => __('children.childName'),
                                                            'name' => 'name',
                                                            'icon' => 'user',
                                                            'value' => $children->name,
                                                            'disabled' => 'disabled',
                                                        ])
                                                    </div>
                                                    <div class="col-md-4">
                                                        @include('components.text-input', [
                                                            'label' => __('children.childFamilyName'),
                                                            'name' => 'family_name',
                                                            'icon' => 'user',
                                                            'value' => $children->family_name,
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
                                                    <div class="col-md-12 occuredReason" style="display: {{ (old('occured') ?? @$document->occured) == '0' ? 'block' : 'none' }};">
                                                        @include('components.select-input', [
                                                            'label' => __('children.occuredReason'),
                                                            'name' => 'occured_reason',
                                                            'icon' => 'buildings',
                                                            'value' => @$document->occured_reason,
                                                            'options' => [['key' => 'Child absent', 'value' => 'Child absent'], ['key' => 'Therapist absent', 'value' => 'Therapist absent'], ['key' => 'Kindergarten closed', 'value' => 'Kindergarten closed'], ['key' => 'Other', 'value' => 'Other']],
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
                                                        @include('components.file-input', [
                                                            'label' => __('children.file'),
                                                            'name' => 'child_file',
                                                            'class' => 'file',
                                                            'id' => 'file',
                                                            'icon' => 'file',
                                                            'value' => old('file'),
                                                        ])
                                                        <div class="d-flex mt-2 choosenFile" style="flex-wrap: wrap;">
                                                            @if (isset($document->file) && $document->file != null)
                                                                <div class="document mt-1">
                                                                    <a href="{{ $document->file }}" target="_blank" rel="noopener noreferrer">{{ $document->file_name }}</a>
                                                                    <i class="bx bx-x childDocument" data-file-name="{{ $document->file }}"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="kindergarten_id" value="{{ $children->kindergarten_id }}">
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
        @include('children.document.script')
        <script></script>
    @endpush
