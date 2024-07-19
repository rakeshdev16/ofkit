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
                            <div id="stepper1" class="bs-stepper linear">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-lg-flex flex-lg-row align-items-lg-center justify-content-lg-between"
                                            role="tablist">
                                            <div class="step" data-target="#test-l-1">
                                                <div class="step-trigger" role="tab" id="stepper1trigger1"
                                                    aria-controls="test-l-1" aria-selected="false" disabled="disabled">
                                                    <div class="bs-stepper-circle">1</div>
                                                    <div class="">
                                                        <h5 class="mb-0 steper-title">Personal Information</h5>
                                                        <p class="mb-0 steper-sub-title">Enter Personal Details</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bs-stepper-line"></div>
                                            <div class="step" data-target="#test-l-2">
                                                <div class="step-trigger" role="tab" id="stepper1trigger2"
                                                    aria-controls="test-l-2" aria-selected="false" disabled="disabled">
                                                    <div class="bs-stepper-circle">2</div>
                                                    <div class="">
                                                        <h5 class="mb-0 steper-title">Parents Detail</h5>
                                                        <p class="mb-0 steper-sub-title">Enter Parents Details</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bs-stepper-line"></div>
                                            <div class="step active" data-target="#test-l-3">
                                                <div class="step-trigger" role="tab" id="stepper1trigger3"
                                                    aria-controls="test-l-3" aria-selected="true">
                                                    <div class="bs-stepper-circle">3</div>
                                                    <div class="">
                                                        <h5 class="mb-0 steper-title">Medical Information</h5>
                                                        <p class="mb-0 steper-sub-title">Enter Medical Information Details</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="bs-stepper-content">
                                            <form action="{{ route('children.update', $children->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div id="test-l-1" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper1trigger1">
                                                    <div class="row g-3">
                                                        <div class="col-md-12 text-center upload-photo">
                                                            <img src="{{ $children->photo }}" id="previewImage" alt="">
                                                            <div class="cam-icom">
                                                                <i class="bx bx-camera"></i>
                                                            </div>
                                                            @error('photo')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                        <input type="file" style="visibility: hidden" name="photo" id="profileInp">
                                                        <input type="hidden" name="user_id" id="userId" value="{{ $children->id }}">
                                                        <input type="hidden" id="type" value="update">
                                                        <input type="hidden" id="url" value="{{ route('uploadChildrenProfile') }}">
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => 'First Name',
                                                                'name' => 'name',
                                                                'icon' => 'network-chart',
                                                                'value' => $children->name
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => 'Family Name',
                                                                'name' => 'family_name',
                                                                'icon' => 'network-chart',
                                                                'value' => $children->family_name
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.select-input', [
                                                                'label' => 'Gender', 
                                                                'name' => 'gender', 
                                                                'icon' => 'buildings', 
                                                                'options' => [['key' => 'male', 'value' => 'Male'],['key' => 'female', 'value' => 'Female']],
                                                                'value' => $children->gender
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.date-input', [
                                                                'label' => 'Date of Birth',
                                                                'name' => 'dob',
                                                                'max' => date('Y-m-d'),
                                                                'value' => date('Y-m-d', strtotime($children->dob))
                                                            ])
                                                        </div>
                                                        <div class="col-md-12">
                                                            @include('components.select-input', [
                                                                'label' => __('staff.kindergartenTh'),
                                                                'name' => 'kindergarten_id',
                                                                'icon' => 'buildings',
                                                                'options' => $kindergartens,
                                                                'value' => $children->kindergarten_id
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => 'Address',
                                                                'name' => 'address',
                                                                'icon' => 'network-chart',
                                                                'value' => $children->address
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.select-input', [
                                                                'label' => 'Functioning',
                                                                'name' => 'functionality_id',
                                                                'icon' => 'buildings',
                                                                'options' => $functionalities,
                                                                'value' => $children->functionality_id
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.select-input', [
                                                                'label' => 'Diagnosis',
                                                                'name' => 'diagnosis_id',
                                                                'icon' => 'buildings',
                                                                'options' => $dianioses,
                                                                'value' => $children->diagnosis_id
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.select-input', [
                                                                'label' => 'Status',
                                                                'name' => 'status_id',
                                                                'icon' => 'buildings',
                                                                'options' => $statuses,
                                                                'value' => $children->status_id
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.date-input', [
                                                                'label' => 'Tabam Services start',
                                                                'name' => 'service_start_date',
                                                                'value' => $children->service_start_date
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.select-input', [
                                                                'label' => 'HMO',
                                                                'name' => 'hmo_id',
                                                                'icon' => 'buildings',
                                                                'options' => $hmos,
                                                                'value' => $children->hmo_id
                                                            ])
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <button type="button" class="btn button px-4" onclick="stepper1.next()">Next
                                                                <i class="bx bx-right-arrow-alt ms-2"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
        
                                                <div id="test-l-2" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper1trigger2">
                                                    @php
                                                        $parent = $children->parent;
                                                    @endphp
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => 'Father Name',
                                                                'name' => 'father_name',
                                                                'icon' => 'network-chart',
                                                                'value' => @$parent->father_name
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => 'Telephone',
                                                                'name' => 'father_telephone',
                                                                'class' => 'numbers',
                                                                'icon' => 'network-chart',
                                                                'value' => @$parent->father_telephone
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => 'Mother Name',
                                                                'name' => 'mother_name',
                                                                'icon' => 'network-chart',
                                                                'value' => @$parent->mother_name
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => 'Telephone',
                                                                'name' => 'mother_telephone',
                                                                'class' => 'numbers',
                                                                'icon' => 'network-chart',
                                                                'value' => @$parent->mother_telephone
                                                            ])
                                                        </div>
                                                        <div class="col-md-12">
                                                            @include('components.select-input', [
                                                                'label' => 'Family Status',
                                                                'name' => 'family_status',
                                                                'icon' => 'buildings',
                                                                'options' => $parentsStatus,
                                                                'value' => @$parent->family_status
                                                            ])
                                                        </div>
                                                        <div class="col-md-12"><h4> Additional Contacts(For Emergencies)</h4></div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => 'Name',
                                                                'name' => 'emergency_name',
                                                                'icon' => 'network-chart',
                                                                'value' => @$parent->name
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => 'Relationship',
                                                                'name' => 'emergency_relationship',
                                                                'icon' => 'network-chart',
                                                                'value' => @$parent->relationship
                                                            ])
                                                        </div>
                                                        <div class="col-md-12">
                                                            @include('components.text-input', [
                                                                'label' => 'Telephone',
                                                                'name' => 'emergency_telephone',
                                                                'class' => 'numbers',
                                                                'icon' => 'network-chart',
                                                                'value' => @$parent->telephone
                                                            ])
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="d-flex align-items-center gap-3">
                                                                <button type="button" class="btn btn-outline-secondary px-4" onclick="stepper1.previous()">
                                                                    <i class="bx bx-left-arrow-alt me-2"></i>Previous
                                                                </button>
                                                                <button type="button" class="btn button px-4" onclick="stepper1.next()">Next
                                                                    <i class="bx bx-right-arrow-alt ms-2"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
        
                                                <div id="test-l-3" role="tabpanel" class="bs-stepper-pane active dstepper-block"
                                                    aria-labelledby="stepper1trigger3">
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
                                                                'value' => @$medical->food_allergie == 1 ? 'yes' : 'no'
                                                            ])
                                                        </div>
                                                        <div class="col-md-12 allergieDetail"
                                                            style="display: {{ old('food_allergie') ?? (@$medical->food_allergie == 1 ? 'yes' : 'no') == 'yes' ? 'block' : 'none' }};"
                                                        >
                                                            @include('components.textarea-input', [
                                                                'label' => 'Allergies Detail',
                                                                'name' => 'food_allergie_detail',
                                                                'icon' => 'network-chart',
                                                                'value' => @$medical->food_allergie_detail
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
                                                                'value' => @$medical->medicine == 1 ? 'yes' : 'no'
                                                            ])
                                                        </div>
                                                        <div class="col-md-12 medicineDetail"
                                                            style="display: {{ old('medicine') ?? (@$medical->medicine == 1 ? 'yes' : 'no') == 'yes' ? 'block' : 'none' }}"
                                                        >
                                                            @include('components.textarea-input', [
                                                                'label' => 'Add Medicine Detail',
                                                                'name' => 'medicine_detail',
                                                                'icon' => 'network-chart',
                                                                'value' => @$medical->medicine_detail
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => 'Medicine Name',
                                                                'name' => 'medicine_name',
                                                                'icon' => 'network-chart',
                                                                'value' => @$medical->medicine_name
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
                                                                'value' => @$medical->type
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => 'Dosage and Timing',
                                                                'name' => 'dosage_and_timing',
                                                                'icon' => 'network-chart',
                                                                'value' => @$medical->dosage_and_timing
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
                                                                'value' => @$medical->where
                                                            ])
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="d-flex align-items-center gap-3">
                                                                <button type="button" class="btn btn-outline-secondary px-4" onclick="stepper1.previous()">
                                                                    <i class="bx bx-left-arrow-alt me-2"></i>Previous
                                                                </button>
                                                                <button type="submit" class="btn button px-4">Submit
                                                                    <i class="bx bx-right-arrow-alt ms-2"></i>
                                                                </button>
                                                            </div>
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
        @include('components.cropper-modal')
    @endsection
    @push('customScript')
        <script src="{{ asset('assets/js/bs-stepper.min.js') }}"></script>
        <script src="{{ asset('assets/js/main.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="{{ asset('assets/js/cropper.min.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('assets/css/cropper.min.css') }}" />
        @include('components.cropper-script')
        <script>
            $(document).ready(function() {
                $('.kindergarten').select2();
            });
            $(document).on('click', '.button', function() {
                $(this).attr('disabled', false);
            });

            $(document).on('change', '.foodAllergie', function() {
                if ($(this).val() == 'yes') {
                    $('.allergieDetail').show();
                } else {
                    $('.allergieDetail').hide();
                }
            });
            $(document).on('change', '.medicine', function() {
                if ($(this).val() == 'yes') {
                    $('.medicineDetail').show();
                } else {
                    $('.medicineDetail').hide();
                }
            });
        </script>
    @endpush
