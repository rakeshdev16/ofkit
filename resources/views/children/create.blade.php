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
                        <div class="breadcrumb-title pe-3">{{ __('comon.addNew') }}</div>
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
                                                        <h5 class="mb-0 steper-title">{{ __('children.personalInfo') }}</h5>
                                                        <p class="mb-0 steper-sub-title">{{ __('children.personalDetail') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bs-stepper-line"></div>
                                            <div class="step" data-target="#test-l-2">
                                                <div class="step-trigger" role="tab" id="stepper1trigger2"
                                                    aria-controls="test-l-2" aria-selected="false" disabled="disabled">
                                                    <div class="bs-stepper-circle">2</div>
                                                    <div class="">
                                                        <h5 class="mb-0 steper-title">{{ __('children.parentsDetail') }}</h5>
                                                        <p class="mb-0 steper-sub-title">{{ __('children.enterParentsDetail') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bs-stepper-line"></div>
                                            <div class="step active" data-target="#test-l-3">
                                                <div class="step-trigger" role="tab" id="stepper1trigger3"
                                                    aria-controls="test-l-3" aria-selected="true">
                                                    <div class="bs-stepper-circle">3</div>
                                                    <div class="">
                                                        <h5 class="mb-0 steper-title">{{ __('children.medicalInfo') }}</h5>
                                                        <p class="mb-0 steper-sub-title">{{ __('children.enterMedicalDetail') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="bs-stepper-content">
                                            <form action="{{ route('children.store') }}" method="POST">
                                                @csrf
                                                <div id="test-l-1" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper1trigger1">
                                                    <div class="row g-3">
                                                        <div class="col-md-12 text-center upload-photo">
                                                            <img src="https://placehold.co/150x150" id="previewImage" alt="">
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
                                                        <input type="hidden" id="type" value="add">
                                                        <input type="hidden" id="url" value="{{ route('uploadChildrenProfile') }}">
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.name'),
                                                                'name' => 'name',
                                                                'icon' => 'network-chart',
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' =>  __('children.familyName'),
                                                                'name' => 'family_name',
                                                                'icon' => 'network-chart',
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.select-input', [
                                                                'label' =>  __('children.gender'), 
                                                                'name' => 'gender', 
                                                                'icon' => 'buildings', 
                                                                'options' => [['key' => 'male', 'value' => 'Male'],['key' => 'female', 'value' => 'Female']],
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.date-input', [
                                                                'label' =>  __('children.dob'),
                                                                'name' => 'dob',
                                                                'max' => date('Y-m-d')
                                                            ])
                                                        </div>
                                                        <div class="col-md-12">
                                                            @include('components.select-input', [
                                                                'label' =>  __('children.kindergarten'),
                                                                'name' => 'kindergarten_id',
                                                                'icon' => 'buildings',
                                                                'options' => $kindergartens,
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' =>  __('children.address'),
                                                                'name' => 'address',
                                                                'icon' => 'network-chart',
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.select-input', [
                                                                'label' =>  __('children.functionality'),
                                                                'name' => 'functionality_id',
                                                                'icon' => 'buildings',
                                                                'options' => $functionalities,
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.select-input', [
                                                                'label' =>  __('children.diagnosis'),
                                                                'name' => 'diagnosis_id',
                                                                'icon' => 'buildings',
                                                                'options' => $dianioses,
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.select-input', [
                                                                'label' =>  __('children.status'),
                                                                'name' => 'status_id',
                                                                'icon' => 'buildings',
                                                                'options' => $statuses,
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.date-input', [
                                                                'label' => __('children.tabamServicesStart'),
                                                                'name' => 'service_start_date',
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.select-input', [
                                                                'label' =>  __('children.hmo'),
                                                                'name' => 'hmo_id',
                                                                'icon' => 'buildings',
                                                                'options' => $hmos,
                                                            ])
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <button type="button" class="btn button px-4" onclick="stepper1.next()">
                                                                {{ __('comon.next') }}
                                                                <i class="bx bx-right-arrow-alt ms-2"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
        
                                                <div id="test-l-2" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper1trigger2">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.fatherName'),
                                                                'name' => 'father_name',
                                                                'icon' => 'network-chart',
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.fatherTelephone'),
                                                                'name' => 'father_telephone',
                                                                'class' => 'numbers',
                                                                'icon' => 'network-chart',
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.motherName'),
                                                                'name' => 'mother_name',
                                                                'icon' => 'network-chart',
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.motherTelephone'),
                                                                'name' => 'mother_telephone',
                                                                'class' => 'numbers',
                                                                'icon' => 'network-chart',
                                                            ])
                                                        </div>
                                                        <div class="col-md-12">
                                                            @include('components.select-input', [
                                                                'label' => __('children.familyStatus'),
                                                                'name' => 'family_status',
                                                                'icon' => 'buildings',
                                                                'options' => $parentsStatus,
                                                            ])
                                                        </div>
                                                        <div class="col-md-12"><h4> {{ __('children.additionalContacts') }}</h4></div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.emergencyName'),
                                                                'name' => 'emergency_name',
                                                                'icon' => 'network-chart',
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.relationship'),
                                                                'name' => 'emergency_relationship',
                                                                'icon' => 'network-chart',
                                                            ])
                                                        </div>
                                                        <div class="col-md-12">
                                                            @include('components.text-input', [
                                                                'label' => __('children.telephone'),
                                                                'name' => 'emergency_telephone',
                                                                'class' => 'numbers',
                                                                'icon' => 'network-chart',
                                                            ])
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="d-flex align-items-center gap-3">
                                                                <button type="button" class="btn btn-outline-secondary px-4" onclick="stepper1.previous()">
                                                                    <i class="bx bx-left-arrow-alt me-2"></i>{{ __('comon.previous') }}
                                                                </button>
                                                                <button type="button" class="btn button px-4" onclick="stepper1.next()">{{ __('comon.next') }}
                                                                    <i class="bx bx-right-arrow-alt ms-2"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
        
                                                <div id="test-l-3" role="tabpanel" class="bs-stepper-pane active dstepper-block"
                                                    aria-labelledby="stepper1trigger3">
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
                                                            ])
                                                        </div>
                                                        <div class="col-md-12 allergieDetail" style="display: {{ old('food_allergie') == 'yes' ? 'block' : 'none' }};">
                                                            @include('components.textarea-input', [
                                                                'label' => __('children.foodAllergieDetail'),
                                                                'name' => 'food_allergie_detail',
                                                                'icon' => 'network-chart',
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
                                                            ])
                                                        </div>
                                                        <div class="col-md-12 medicineDetail" style="display: {{ old('medicine') == 'yes' ? 'block' : 'none' }}">
                                                            @include('components.textarea-input', [
                                                                'label' => __('children.medicineDetail'),
                                                                'name' => 'medicine_detail',
                                                                'icon' => 'network-chart',
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.medicineName'),
                                                                'name' => 'medicine_name',
                                                                'icon' => 'network-chart',
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
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.dosageAndTiming'),
                                                                'name' => 'dosage_and_timing',
                                                                'icon' => 'network-chart',
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.select-input', [
                                                                'label' => __('children.where'),
                                                                'name' => 'where',
                                                                'icon' => 'buildings',
                                                                'options' => [
                                                                    ['key' => 'kindergarten', 'value' => __('children.kindergarten')],
                                                                    ['key' => 'home', 'value' => __('children.home')]
                                                                ],
                                                            ])
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="d-flex align-items-center gap-3">
                                                                <button type="button" class="btn btn-outline-secondary px-4" onclick="stepper1.previous()">
                                                                    <i class="bx bx-left-arrow-alt me-2"></i>{{ __('comon.previous') }}
                                                                </button>
                                                                <button type="submit" class="btn button px-4">{{ __('comon.submit') }}
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
