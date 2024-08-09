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
                                    <div class="card-body">
                                        <div class="bs-stepper-content">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="mb-4 steper-title">{{ __('children.personalInfo') }}</h5>
                                            </div>
                                            <form action="{{ route('children.store') }}" method="POST">
                                                @csrf
                                                <div class="bs-stepper-pane active" aria-labelledby="stepper1trigger1">
                                                    <div class="row g-3">
                                                        @include('components.upload-profile', [
                                                            'src' => asset('assets/images/avatars/dummy-image.webp'),
                                                            'is_uploaded' => '',
                                                            'type' => 'add',
                                                            'updateUrl' => route('uploadChildrenProfile'),
                                                            'deleteUrl' => route('deleteChildrenProfile'),
                                                        ])

                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.name'),
                                                                'name' => 'name',
                                                                'icon' => 'id-card',
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' =>  __('children.familyName'),
                                                                'name' => 'family_name',
                                                                'icon' => 'id-card',
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' =>  __('children.ID'),
                                                                'name' => 'identification',
                                                                'icon' => 'search-alt',
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
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' =>  'Age',
                                                                'name' => 'age',
                                                                'class' => 'age',
                                                                'icon' => 'buildings', 
                                                                'readonly' => true,
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.select-input', [
                                                                'label' =>  __('children.kindergarten'),
                                                                'name' => 'kindergarten_id',
                                                                'class' => 'selectedKindergarten',
                                                                'icon' => 'buildings',
                                                                'options' => $kindergartens,
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => 'Kindergarten Manager',
                                                                'name' => '',
                                                                'class' => 'kindergartenManager',
                                                                'icon' => 'user',
                                                                'readonly' => true
                                                            ])
                                                        </div>
                                                        <div class="col-md-12">
                                                            @include('components.textarea-input', [
                                                                'label' =>  __('children.address'),
                                                                'name' => 'address',
                                                                'icon' => 'current-location',
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
                                                                'label' =>  __('children.status'),
                                                                'name' => 'status_id',
                                                                'icon' => 'buildings',
                                                                'options' => $statuses,
                                                                ])
                                                        </div>
                                                        <div class="col-md-12">
                                                            @include('components.multi-select-input', [
                                                                'label' =>  __('children.diagnosis'),
                                                                'name' => 'diagnosis_id[]',
                                                                'class' => 'diagnosis',
                                                                'icon' => 'buildings',
                                                                'options' => $dianioses,
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
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="bs-stepper-pane active" aria-labelledby="stepper1trigger2">
                                                    <div class="">
                                                        <h5 class="my-3 steper-title">{{ __('children.parentsDetail') }}</h5>
                                                    </div>
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.fatherName'),
                                                                'name' => 'father_name',
                                                                'icon' => 'user',
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.fatherEmail'),
                                                                'name' => 'father_email',
                                                                'icon' => 'envelope',
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.fatherTelephone'),
                                                                'name' => 'father_telephone',
                                                                'class' => 'numbers',
                                                                'icon' => 'phone',
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.fatherWork'),
                                                                'name' => 'father_work',
                                                                'icon' => 'briefcase',
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
                                                                'label' => __('children.motherEmail'),
                                                                'name' => 'mother_email',
                                                                'icon' => 'envelope',
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.motherTelephone'),
                                                                'name' => 'mother_telephone',
                                                                'class' => 'numbers',
                                                                'icon' => 'phone',
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.motherWork'),
                                                                'name' => 'mother_work',
                                                                'icon' => 'briefcase',
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.select-input', [
                                                                'label' => __('children.familyStatus'),
                                                                'name' => 'family_status',
                                                                'icon' => 'buildings',
                                                                'options' => $parentsStatus,
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.siblings'),
                                                                'name' => 'siblings',
                                                                'class' => 'numbers',
                                                                'icon' => 'user',
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.disabilities'),
                                                                'name' => 'disabilities',
                                                                'icon' => 'user',
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.multi-select-input', [
                                                                'label' => __('children.spokenLanguages'),
                                                                'name' => 'spoken_language[]',
                                                                'class' => 'spkoenLanguages',
                                                                'icon' => 'notepad',
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
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="bs-stepper-pane active dstepper-block" aria-labelledby="stepper1trigger3">
                                                    <div class="">
                                                        <h5 class="my-3 steper-title">{{ __('children.medicalInfo') }}</h5>
                                                    </div>
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
                                                            <div class="row mt-1">
                                                                <div class="col-md-12 d-flex justify-content-between">
                                                                    <button type="button" class="btn button addMoreMedicine">+</button>
                                                                </div>
                                                            </div>
                                                            @foreach (old('medicine_dosage', []) as $medicine)
                                                                @include('components.medicine-detail', ['index' => $loop->iteration, 'data' => $medicine])
                                                            @endforeach
                                                            {{-- @php
                                                                $indexes = Session::get('medicineDosageKey') ?? [];
                                                            @endphp
                                                            @foreach ($indexes as $index)
                                                                @include('components.medicine-detail', ['index' => $index])
                                                            @endforeach --}}
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="d-flex align-items-center gap-3">
                                                                <button type="submit" class="btn button submitBtn px-4">{{ __('comon.submit') }}
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
        @include('components.cropper-script')
        @include('children.script')
        <script>
            $(document).ready(function() {
                $('.kindergarten').select2();
            });
            $(document).on('click', '.button', function() {
                $(this).attr('disabled', false);
            });
            
            $(document).on('change', '.date-of-birth', function() {
                var start = moment(new Date($(this).val()));
                var end = moment(new Date());
                var years = end.diff(start, 'years');
                start.add(years, 'years');
                var months = end.diff(start, 'months');
                start.add(months, 'months');
                var age = years + '.' + months;
                // var age = years;
                $('.age').val(age);
            });

            $(document).on('click', '.removeMedicine', function() {
                $(this).parent().parent().parent().remove();
                var index = $('.medicineRow').length;
                if (index == 0) {
                    $('.medicineDetail').hide();
                    $('.medicineRow').remove();
                    $('.medicine').val('no');
                }
            });
            
        </script>
    @endpush
