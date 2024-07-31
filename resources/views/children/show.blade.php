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
                                    <li class="breadcrumb-item active" aria-current="page">{{ __('children.children') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="ms-auto">
                            <div class="">
                                <a href="{{ route('children.index') }}" class="btn button">{{ __('comon.back') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div class="main-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <ul class="list-group list-group-flush child-menu">
                                                <li class="list-group-item text-center">
                                                    <h6 class="mb-0">Weekly Therapy Schedule</h6>
                                                </li>
                                                <li class="list-group-item text-center">
                                                    <h6 class="mb-0"><a href="{{ route('children-documentations.get', $children->id) }}">View Documentation of intervation</a></h6>
                                                </li>
                                                <li class="list-group-item text-center">
                                                    <h6 class="mb-0">Professional / Staff Meeting Discussion</h6>
                                                </li>
                                                <li class="list-group-item text-center">
                                                    <h6 class="mb-0">Document and approvals</h6>
                                                </li>
                                                <li class="list-group-item text-center">
                                                    <h6 class="mb-0" data-bs-toggle="modal" data-bs-target="#exampleSmallModal">New Documantation</h6>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="bs-stepper-content">
                                                <div class="d-flex justify-content-between">
                                                    <h5 class="mb-4 steper-title">{{ __('children.personalInfo') }}</h5>
                                                    <a href="{{ route('children.edit', $children->id) }}" class=""><i class="bx bx-edit icon"></i></a>
                                                </div>
                                                <div>
                                                    <div class="row g-3">
                                                        <div class="col-md-12 mb-4 text-center upload-photo">
                                                            <img src="{{ $children->photo }}" id="previewImage" alt="">
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.name'),
                                                                'name' => 'name',
                                                                'icon' => 'id-card',
                                                                'value' => $children->name,
                                                                'disabled' => 'disabled'
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.familyName'),
                                                                'name' => 'family_name',
                                                                'icon' => 'id-card',
                                                                'value' => $children->family_name,
                                                                'disabled' => 'disabled'
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' =>  __('children.ID'),
                                                                'name' => 'identification',
                                                                'icon' => 'search-alt',
                                                                'value' => $children->identification,
                                                                'disabled' => 'disabled'
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.select-input', [
                                                                'label' => __('children.gender'), 
                                                                'name' => 'gender', 
                                                                'icon' => 'buildings', 
                                                                'options' => [['key' => 'male', 'value' => 'Male'],['key' => 'female', 'value' => 'Female']],
                                                                'value' => $children->gender,
                                                                'disabled' => 'disabled'
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.date-input', [
                                                                'label' => __('children.dob'),
                                                                'name' => 'dob',
                                                                'max' => date('Y-m-d'),
                                                                'value' => date('Y-m-d', strtotime($children->dob)),
                                                                'disabled' => 'disabled',
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' =>  'Age',
                                                                'name' => 'age',
                                                                'class' => 'age',
                                                                'icon' => 'buildings', 
                                                                'disabled' => 'disabled',
                                                                'value' => $children->age,
                                                            ])
                                                        </div>
                                                        <div class="col-md-12">
                                                            @include('components.select-input', [
                                                                'label' => __('children.kindergarten'),
                                                                'name' => 'kindergarten_id',
                                                                'icon' => 'buildings',
                                                                'options' => $kindergartens,
                                                                'value' => $children->kindergarten_id,
                                                                'disabled' => 'disabled'
                                                            ])
                                                        </div>
                                                        <div class="col-md-12">
                                                            @include('components.textarea-input', [
                                                                'label' => __('children.address'),
                                                                'name' => 'address',
                                                                'icon' => 'network-chart',
                                                                'value' => $children->address,
                                                                'disabled' => 'disabled'
                                                            ])
                                                        </div>
                                                        <div class="col-md-4">
                                                            @include('components.select-input', [
                                                                'label' => __('children.functionality'),
                                                                'name' => 'functionality_id',
                                                                'icon' => 'buildings',
                                                                'options' => $functionalities,
                                                                'value' => $children->functionality_id,
                                                                'disabled' => 'disabled'
                                                            ])
                                                        </div>
                                                        <div class="col-md-4">
                                                            @include('components.select-input', [
                                                                'label' => __('children.diagnosis'),
                                                                'name' => 'diagnosis_id',
                                                                'icon' => 'buildings',
                                                                'options' => $dianioses,
                                                                'value' => $children->diagnosis_id,
                                                                'disabled' => 'disabled'
                                                            ])
                                                        </div>
                                                        <div class="col-md-4">
                                                            @include('components.select-input', [
                                                                'label' => __('children.status'),
                                                                'name' => 'status_id',
                                                                'icon' => 'buildings',
                                                                'options' => $statuses,
                                                                'value' => $children->status_id,
                                                                'disabled' => 'disabled'
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.date-input', [
                                                                'label' => __('children.tabamServicesStart'),
                                                                'name' => 'service_start_date',
                                                                'value' => $children->service_start_date,
                                                                'disabled' => 'disabled'
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.select-input', [
                                                                'label' => __('children.hmo'),
                                                                'name' => 'hmo_id',
                                                                'icon' => 'buildings',
                                                                'options' => $hmos,
                                                                'value' => $children->hmo_id,
                                                                'disabled' => 'disabled'
                                                            ])
                                                        </div>
                                                    </div>
                                                </div>

                                                <div>
                                                    <div class="">
                                                        <hr>
                                                        <h5 class="my-3 steper-title">{{ __('children.parentsDetail') }}</h5>
                                                    </div>
                                                    @php
                                                        $parent = $children->parent;
                                                    @endphp
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.fatherName'),
                                                                'name' => 'father_name',
                                                                'icon' => 'network-chart',
                                                                'value' => @$parent->father_name,
                                                                'disabled' => 'disabled'
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.fatherTelephone'),
                                                                'name' => 'father_telephone',
                                                                'icon' => 'network-chart',
                                                                'value' => @$parent->father_telephone,
                                                                'disabled' => 'disabled'
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.motherName'),
                                                                'name' => 'mother_name',
                                                                'icon' => 'network-chart',
                                                                'value' => @$parent->mother_name,
                                                                'disabled' => 'disabled'
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.motherTelephone'),
                                                                'name' => 'mother_telephone',
                                                                'icon' => 'network-chart',
                                                                'value' => @$parent->mother_telephone,
                                                                'disabled' => 'disabled'
                                                            ])
                                                        </div>
                                                        <div class="col-md-12">
                                                            @include('components.select-input', [
                                                                'label' => __('children.familyStatus'),
                                                                'name' => 'family_status',
                                                                'icon' => 'buildings',
                                                                'options' => $parentsStatus,
                                                                'value' => @$parent->family_status,
                                                                'disabled' => 'disabled'
                                                            ])
                                                        </div>
                                                        <div class="col-md-12"><h4> {{ __('children.additionalContacts') }}</h4></div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.emergencyName'),
                                                                'name' => 'emergency_name',
                                                                'icon' => 'network-chart',
                                                                'value' => @$parent->name,
                                                                'disabled' => 'disabled'
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.relationship'),
                                                                'name' => 'emergency_relationship',
                                                                'icon' => 'network-chart',
                                                                'value' => @$parent->relationship,
                                                                'disabled' => 'disabled'
                                                            ])
                                                        </div>
                                                        <div class="col-md-12">
                                                            @include('components.text-input', [
                                                                'label' => __('children.telephone'),
                                                                'name' => 'emergency_telephone',
                                                                'icon' => 'network-chart',
                                                                'value' => @$parent->telephone,
                                                                'disabled' => 'disabled'
                                                            ])
                                                        </div>
                                                    </div>
                                                </div>

                                                <div>
                                                    <div class="">
                                                        <hr>
                                                        <h5 class="my-3 steper-title">{{ __('children.medicalInfo') }}</h5>
                                                    </div>
                                                    @php
                                                        $medical = $children->medicalInformation;
                                                    @endphp
                                                    <div class="row g-3">
                                                        <div class="col-md-12">
                                                            @include('components.select-input', [
                                                                'label' => __('children.foodAllergie'),
                                                                'name' => 'food_allergie',
                                                                'class' => 'foodAllergie',
                                                                'icon' => 'buildings',
                                                                'options' => [
                                                                    ['key' => 'yes', 'value' => 'Yes'],
                                                                    ['key' => 'no', 'value' => 'No']
                                                                ],
                                                                'value' => @$medical->food_allergie == 1 ? 'yes' : 'no',
                                                                'disabled' => 'disabled'
                                                            ])
                                                        </div>
                                                        <div class="col-md-12 allergieDetail"
                                                            style="display: {{ old('food_allergie') ?? (@$medical->food_allergie == 1 ? 'yes' : 'no') == 'yes' ? 'block' : 'none' }};"
                                                        >
                                                            @include('components.textarea-input', [
                                                                'label' => __('children.foodAllergieDetail'),
                                                                'name' => 'food_allergie_detail',
                                                                'icon' => 'network-chart',
                                                                'value' => @$medical->food_allergie_detail,
                                                                'disabled' => 'disabled'
                                                            ])
                                                        </div>
                                                        <div class="col-md-12">
                                                            @include('components.select-input', [
                                                                'label' => __('children.medicine'),
                                                                'name' => 'medicine',
                                                                'class' => 'medicine',
                                                                'icon' => 'buildings',
                                                                'options' => [
                                                                    ['key' => 'yes', 'value' => 'Yes'],
                                                                    ['key' => 'no', 'value' => 'No']
                                                                ],
                                                                'value' => @$medical->medicine == 1 ? 'yes' : 'no',
                                                                'disabled' => 'disabled'
                                                            ])
                                                        </div>
                                                        <div class="col-md-12 medicineDetail"
                                                            style="display: {{ old('medicine') ?? (@$medical->medicine == 1 ? 'yes' : 'no') == 'yes' ? 'block' : 'none' }}"
                                                        >
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    @include('components.text-input', [
                                                                        'label' => __('children.medicineName'),
                                                                        'name' => 'medicine_name',
                                                                        'icon' => 'network-chart',
                                                                        'value' => @$medical->medicine_name,
                                                                        'disabled' => 'disabled'
                                                                    ])
                                                                </div>
                                                                <div class="col-md-6">
                                                                    @include('components.select-input', [
                                                                        'label' => __('children.type'),
                                                                        'name' => 'type',
                                                                        'icon' => 'buildings',
                                                                        'options' => [
                                                                            ['key' => 'sos', 'value' => 'SOS'],
                                                                            ['key' => 'regular', 'value' => 'Regular']
                                                                        ],
                                                                        'value' => @$medical->type,
                                                                        'disabled' => 'disabled'
                                                                    ])
                                                                </div>
                                                                <div class="col-md-6">
                                                                    @include('components.text-input', [
                                                                        'label' => __('children.dosageAndTiming'),
                                                                        'name' => 'dosage_and_timing',
                                                                        'icon' => 'network-chart',
                                                                        'value' => @$medical->dosage_and_timing,
                                                                        'disabled' => 'disabled'
                                                                    ])
                                                                </div>
                                                                <div class="col-md-6">
                                                                    @include('components.select-input', [
                                                                        'label' => __('children.where'),
                                                                        'name' => 'where',
                                                                        'icon' => 'buildings',
                                                                        'options' => [
                                                                            ['key' => 'kindergarten', 'value' => 'Kindergarten'],
                                                                            ['key' => 'home', 'value' => 'Home']
                                                                        ],
                                                                        'value' => @$medical->where,
                                                                        'disabled' => 'disabled'
                                                                    ])
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="exampleSmallModal" tabindex="-1" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Documentation Type</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <ul style="list-style: none; margin-right: -26px;">
                                <li class="document my-2"><a href="{{ route('children-documentation.get', ['individual', Request::segment(2)]) }}">Individual</a></li>
                                <li class="document my-2"><a href="{{ route('children-documentation.get', ['group', Request::segment(2)]) }}">Group</a></li>
                                <li class="document my-2"><a href="{{ route('children-documentation.get', ['parental-guidance', Request::segment(2)]) }}">Parental Guidance</a></li>
                                <li class="document my-2"><a href="{{ route('children-documentation.get', ['staff-meeting', Request::segment(2)]) }}">Staff Meeting</a></li>
                                <li class="document my-2"><a href="{{ route('children-documentation.get', ['initial-evaluation', Request::segment(2)]) }}">Initial Evaluation</a></li>
                                <li class="document my-2"><a href="{{ route('children-documentation.get', ['final-evaluation', Request::segment(2)]) }}">Final Evaluation</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @push('customScript')
        {{-- <script src="{{ asset('assets/js/bs-stepper.min.js') }}"></script>
        <script src="{{ asset('assets/js/main.js') }}"></script> --}}
        <script>
            $(document).on('click', '.button', function() {
                $(this).attr('disabled', false);
            });
        </script>
    @endpush
