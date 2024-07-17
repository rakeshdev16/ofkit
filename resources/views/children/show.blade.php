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
                    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                        <div class="breadcrumb-title pe-3">Edit</div>
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
                                <a href="{{ route('children.index') }}" class="btn button">{{ __('children.back') }}</a>
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
                                                <h5 class="mb-4 steper-title">Personal Information</h5>
                                                <a href="{{ route('children.edit', $children->id) }}" class=""><i class="bx bx-edit icon"></i></a>
                                            </div>
                                            <div>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        @include('components.text-input', [
                                                            'label' => 'First Name',
                                                            'name' => 'name',
                                                            'icon' => 'network-chart',
                                                            'value' => $children->name,
                                                            'disabled' => 'disabled'
                                                        ])
                                                    </div>
                                                    <div class="col-md-6">
                                                        @include('components.text-input', [
                                                            'label' => 'Family Name',
                                                            'name' => 'family_name',
                                                            'icon' => 'network-chart',
                                                            'value' => $children->family_name,
                                                            'disabled' => 'disabled'
                                                        ])
                                                    </div>
                                                    <div class="col-md-6">
                                                        @include('components.select-input', [
                                                            'label' => 'Gender', 
                                                            'name' => 'gender', 
                                                            'icon' => 'buildings', 
                                                            'options' => [['key' => 'male', 'value' => 'Male'],['key' => 'female', 'value' => 'Female']],
                                                            'value' => $children->gender,
                                                            'disabled' => 'disabled'
                                                        ])
                                                    </div>
                                                    <div class="col-md-6">
                                                        @include('components.date-input', [
                                                            'label' => 'Date of Birth',
                                                            'name' => 'dob',
                                                            'max' => date('Y-m-d'),
                                                            'value' => date('Y-m-d', strtotime($children->dob)),
                                                            'disabled' => 'disabled'
                                                        ])
                                                    </div>
                                                    <div class="col-md-12">
                                                        @include('components.select-input', [
                                                            'label' => __('staff.kindergartenTh'),
                                                            'name' => 'kindergarten_id',
                                                            'icon' => 'buildings',
                                                            'options' => $kindergartens,
                                                            'value' => $children->kindergarten_id,
                                                            'disabled' => 'disabled'
                                                        ])
                                                    </div>
                                                    <div class="col-md-6">
                                                        @include('components.text-input', [
                                                            'label' => 'Address',
                                                            'name' => 'address',
                                                            'icon' => 'network-chart',
                                                            'value' => $children->address,
                                                            'disabled' => 'disabled'
                                                        ])
                                                    </div>
                                                    <div class="col-md-6">
                                                        @include('components.select-input', [
                                                            'label' => 'Functioning',
                                                            'name' => 'functionality_id',
                                                            'icon' => 'buildings',
                                                            'options' => $functionalities,
                                                            'value' => $children->functionality_id,
                                                            'disabled' => 'disabled'
                                                        ])
                                                    </div>
                                                    <div class="col-md-6">
                                                        @include('components.select-input', [
                                                            'label' => 'Diagnosis',
                                                            'name' => 'diagnosis_id',
                                                            'icon' => 'buildings',
                                                            'options' => $dianioses,
                                                            'value' => $children->diagnosis_id,
                                                            'disabled' => 'disabled'
                                                        ])
                                                    </div>
                                                    <div class="col-md-6">
                                                        @include('components.select-input', [
                                                            'label' => 'Status',
                                                            'name' => 'status_id',
                                                            'icon' => 'buildings',
                                                            'options' => $statuses,
                                                            'value' => $children->status_id,
                                                            'disabled' => 'disabled'
                                                        ])
                                                    </div>
                                                    <div class="col-md-6">
                                                        @include('components.date-input', [
                                                            'label' => 'Tabam Services start',
                                                            'name' => 'service_start_date',
                                                            'value' => $children->service_start_date,
                                                            'disabled' => 'disabled'
                                                        ])
                                                    </div>
                                                    <div class="col-md-6">
                                                        @include('components.select-input', [
                                                            'label' => 'HMO',
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
                                                    <h5 class="my-3 steper-title">Parents Detail</h5>
                                                </div>
                                                @php
                                                    $parent = $children->parent;
                                                @endphp
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        @include('components.text-input', [
                                                            'label' => 'Father Name',
                                                            'name' => 'father_name',
                                                            'icon' => 'network-chart',
                                                            'value' => @$parent->father_name,
                                                            'disabled' => 'disabled'
                                                        ])
                                                    </div>
                                                    <div class="col-md-6">
                                                        @include('components.text-input', [
                                                            'label' => 'Telephone',
                                                            'name' => 'father_telephone',
                                                            'icon' => 'network-chart',
                                                            'value' => @$parent->father_telephone,
                                                            'disabled' => 'disabled'
                                                        ])
                                                    </div>
                                                    <div class="col-md-6">
                                                        @include('components.text-input', [
                                                            'label' => 'Mother Name',
                                                            'name' => 'mother_name',
                                                            'icon' => 'network-chart',
                                                            'value' => @$parent->mother_name,
                                                            'disabled' => 'disabled'
                                                        ])
                                                    </div>
                                                    <div class="col-md-6">
                                                        @include('components.text-input', [
                                                            'label' => 'Telephone',
                                                            'name' => 'mother_telephone',
                                                            'icon' => 'network-chart',
                                                            'value' => @$parent->mother_telephone,
                                                            'disabled' => 'disabled'
                                                        ])
                                                    </div>
                                                    <div class="col-md-12">
                                                        @include('components.select-input', [
                                                            'label' => 'Family Status',
                                                            'name' => 'family_status',
                                                            'icon' => 'buildings',
                                                            'options' => $parentsStatus,
                                                            'value' => @$parent->family_status,
                                                            'disabled' => 'disabled'
                                                        ])
                                                    </div>
                                                    <div class="col-md-12"><h4> Additional Contacts(For Emergencies)</h4></div>
                                                    <div class="col-md-6">
                                                        @include('components.text-input', [
                                                            'label' => 'Name',
                                                            'name' => 'emergency_name',
                                                            'icon' => 'network-chart',
                                                            'value' => @$parent->name,
                                                            'disabled' => 'disabled'
                                                        ])
                                                    </div>
                                                    <div class="col-md-6">
                                                        @include('components.text-input', [
                                                            'label' => 'Relationship',
                                                            'name' => 'emergency_relationship',
                                                            'icon' => 'network-chart',
                                                            'value' => @$parent->relationship,
                                                            'disabled' => 'disabled'
                                                        ])
                                                    </div>
                                                    <div class="col-md-12">
                                                        @include('components.text-input', [
                                                            'label' => 'Telephone',
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
                                                    <h5 class="my-3 steper-title">Medical Information</h5>
                                                </div>
                                                @php
                                                    $medical = $children->medicalInformation;
                                                @endphp
                                                <div class="row g-3">
                                                    <div class="col-md-12">
                                                        @include('components.select-input', [
                                                            'label' => 'Food Allergies',
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
                                                            'label' => 'Allergies Detail',
                                                            'name' => 'food_allergie_detail',
                                                            'icon' => 'network-chart',
                                                            'value' => @$medical->food_allergie_detail,
                                                            'disabled' => 'disabled'
                                                        ])
                                                    </div>
                                                    <div class="col-md-12">
                                                        @include('components.select-input', [
                                                            'label' => 'Medicine',
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
                                                        @include('components.textarea-input', [
                                                            'label' => 'Medicine Detail',
                                                            'name' => 'medicine_detail',
                                                            'icon' => 'network-chart',
                                                            'value' => @$medical->medicine_detail,
                                                            'disabled' => 'disabled'
                                                        ])
                                                    </div>
                                                    <div class="col-md-6">
                                                        @include('components.text-input', [
                                                            'label' => 'Medicine Name',
                                                            'name' => 'medicine_name',
                                                            'icon' => 'network-chart',
                                                            'value' => @$medical->medicine_name,
                                                            'disabled' => 'disabled'
                                                        ])
                                                    </div>
                                                    <div class="col-md-6">
                                                        @include('components.select-input', [
                                                            'label' => 'Type',
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
                                                            'label' => 'Dosage and Timing',
                                                            'name' => 'dosage_and_timing',
                                                            'icon' => 'network-chart',
                                                            'value' => @$medical->dosage_and_timing,
                                                            'disabled' => 'disabled'
                                                        ])
                                                    </div>
                                                    <div class="col-md-6">
                                                        @include('components.select-input', [
                                                            'label' => 'Where',
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
