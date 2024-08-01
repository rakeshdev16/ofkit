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
                                        <a href="{!! URL::previous() !!}">
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
                                <a href="{!! URL::previous() !!}" class="btn button">{{ __('comon.back') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 mx-auto">
                            <div id="stepper1" class="bs-stepper linear">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="bs-stepper-content">
                                            <form action="{{ route('children.update', $children->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div id="test-l-1" role="tabpanel" class="bs-stepper-pane active" aria-labelledby="stepper1trigger1">
                                                    <div class="row g-3">
                                                        @include('components.upload-profile', [
                                                            'src' => @$children->profile,
                                                            'is_uploaded' => @$children->photo,
                                                            'userId' => @$children->id,
                                                            'type' => 'update',
                                                            'updateUrl' => route('uploadChildrenProfile'),
                                                            'deleteUrl' => route('deleteChildrenProfile'),
                                                        ])
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.name'),
                                                                'name' => 'name',
                                                                'icon' => 'id-card',
                                                                'value' => $children->name
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.familyName'),
                                                                'name' => 'family_name',
                                                                'icon' => 'id-card',
                                                                'value' => $children->family_name
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' =>  __('children.ID'),
                                                                'name' => 'identification',
                                                                'icon' => 'search-alt',
                                                                'value' => $children->identification
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.select-input', [
                                                                'label' => __('children.gender'), 
                                                                'name' => 'gender', 
                                                                'icon' => 'buildings', 
                                                                'options' => [['key' => 'male', 'value' => 'Male'],['key' => 'female', 'value' => 'Female']],
                                                                'value' => $children->gender
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.date-input', [
                                                                'label' => __('children.dob'),
                                                                'name' => 'dob',
                                                                'max' => date('Y-m-d'),
                                                                'value' => $children->dob
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' =>  'Age',
                                                                'name' => 'age',
                                                                'class' => 'age',
                                                                'icon' => 'buildings', 
                                                                'readonly' => true,
                                                                'value' => $children->age
                                                            ])
                                                        </div>
                                                        <div class="col-md-12">
                                                            @include('components.select-input', [
                                                                'label' => __('children.kindergarten'),
                                                                'name' => 'kindergarten_id',
                                                                'icon' => 'buildings',
                                                                'options' => $kindergartens,
                                                                'value' => $children->kindergarten_id
                                                            ])
                                                        </div>
                                                        <div class="col-md-12">
                                                            @include('components.textarea-input', [
                                                                'label' => __('children.address'),
                                                                'name' => 'address',
                                                                'icon' => 'network-chart',
                                                                'value' => $children->address
                                                            ])
                                                        </div>
                                                        <div class="col-md-4">
                                                            @include('components.select-input', [
                                                                'label' => __('children.functionality'),
                                                                'name' => 'functionality_id',
                                                                'icon' => 'buildings',
                                                                'options' => $functionalities,
                                                                'value' => $children->functionality_id
                                                            ])
                                                        </div>
                                                        <div class="col-md-4">
                                                            @include('components.select-input', [
                                                                'label' => __('children.diagnosis'),
                                                                'name' => 'diagnosis_id',
                                                                'icon' => 'buildings',
                                                                'options' => $dianioses,
                                                                'value' => $children->diagnosis_id
                                                            ])
                                                        </div>
                                                        <div class="col-md-4">
                                                            @include('components.select-input', [
                                                                'label' => __('children.status'),
                                                                'name' => 'status_id',
                                                                'icon' => 'buildings',
                                                                'options' => $statuses,
                                                                'value' => $children->status_id
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.date-input', [
                                                                'label' => __('children.tabamServicesStart'),
                                                                'name' => 'service_start_date',
                                                                'value' => $children->service_start_date
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.select-input', [
                                                                'label' => __('children.hmo'),
                                                                'name' => 'hmo_id',
                                                                'icon' => 'buildings',
                                                                'options' => $hmos,
                                                                'value' => $children->hmo_id
                                                            ])
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="bs-stepper-pane active" aria-labelledby="stepper1trigger2">
                                                    @php
                                                        $parent = $children->parent;
                                                    @endphp
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.fatherName'),
                                                                'name' => 'father_name',
                                                                'icon' => 'network-chart',
                                                                'value' => @$parent->father_name
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.fatherTelephone'),
                                                                'name' => 'father_telephone',
                                                                'class' => 'numbers',
                                                                'icon' => 'network-chart',
                                                                'value' => @$parent->father_telephone
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.motherName'),
                                                                'name' => 'mother_name',
                                                                'icon' => 'network-chart',
                                                                'value' => @$parent->mother_name
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.motherTelephone'),
                                                                'name' => 'mother_telephone',
                                                                'class' => 'numbers',
                                                                'icon' => 'network-chart',
                                                                'value' => @$parent->mother_telephone
                                                            ])
                                                        </div>
                                                        <div class="col-md-12">
                                                            @include('components.select-input', [
                                                                'label' => __('children.familyStatus'),
                                                                'name' => 'family_status',
                                                                'icon' => 'buildings',
                                                                'options' => $parentsStatus,
                                                                'value' => @$parent->family_status
                                                            ])
                                                        </div>
                                                        <div class="col-md-12"><h4> {{ __('children.additionalContacts') }}</h4></div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.emergencyName'),
                                                                'name' => 'emergency_name',
                                                                'icon' => 'network-chart',
                                                                'value' => @$parent->name
                                                            ])
                                                        </div>
                                                        <div class="col-md-6">
                                                            @include('components.text-input', [
                                                                'label' => __('children.relationship'),
                                                                'name' => 'emergency_relationship',
                                                                'icon' => 'network-chart',
                                                                'value' => @$parent->relationship
                                                            ])
                                                        </div>
                                                        <div class="col-md-12">
                                                            @include('components.text-input', [
                                                                'label' => __('children.telephone'),
                                                                'name' => 'emergency_telephone',
                                                                'class' => 'numbers',
                                                                'icon' => 'network-chart',
                                                                'value' => @$parent->telephone
                                                            ])
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="bs-stepper-pane active dstepper-block"
                                                    aria-labelledby="stepper1trigger3">
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
                                                                'value' => @$medical->food_allergie == 1 ? 'yes' : 'no'
                                                            ])
                                                        </div>
                                                        <div class="col-md-12 allergieDetail"
                                                            style="display: {{ old('food_allergie') ?? (@$medical->food_allergie == 1 ? 'yes' : 'no') == 'yes' ? 'block' : 'none' }};"
                                                        >
                                                            @include('components.textarea-input', [
                                                                'label' => __('children.foodAllergieDetail'),
                                                                'name' => 'food_allergie_detail',
                                                                'icon' => 'network-chart',
                                                                'value' => @$medical->food_allergie_detail
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
                                                                'value' => @$medical->medicine == 1 ? 'yes' : 'no'
                                                            ])
                                                        </div>
                                                        <div class="col-md-12 medicineDetail" style="display: {{ old('medicine') ?? (@$medical->medicine == 1 ? 'yes' : 'no') == 'yes' ? 'block' : 'none' }}">
                                                            <div class="row mt-1">
                                                                <div class="col-md-12 d-flex justify-content-between">
                                                                    <button type="button" class="btn button addMoreMedicine">+</button>
                                                                </div>
                                                            </div>
                                                            @foreach ($children->medicine as $medicine)
                                                                @include('components.medicine-detail', ['index' => $loop->iteration, 'data' => $medicine])
                                                            @endforeach
                                                            @php
                                                                $indexes = Session::get('medicineDosageKey') ?? [];
                                                            @endphp
                                                            @foreach ($indexes as $index)
                                                                @include('components.medicine-detail', ['index' => $index])
                                                            @endforeach
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="d-flex align-items-center gap-3">
                                                                <button type="submit" class="btn button px-4">{{ __('comon.update') }}
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
                // var age = 'Years ' + years + '.' + months;
                var age = years;
                $('.age').val(age);
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
                    var index = parseInt($('.medicineRow').length);
                    index = index + 1;
                    $('.medicineDetail').append(`@include('components.medicine-detail', ['index' => '${index}'])`);
                    $('.medicineDetail').show();
                } else {
                    $('.medicineDetail').hide();
                    $('.medicineRow').remove();
                }
            });

            $(document).on('click', '.addMoreMedicine', function() {
                var index = $('.medicineRow').length;
                index = index + 1;
                $('.medicineDetail').append(`@include('components.medicine-detail', ['index' => '${index}'])`);
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
