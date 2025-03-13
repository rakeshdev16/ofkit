@extends('layout.master')
@push('customLink')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('section')
    <div class="wrapper">
        <div class="header-wrapper">
            <div class="page-wrapper">
                <div class="page-content">
                    <div class="page-breadcrumb d-flex align-items-center mb-3">
                        <div class="breadcrumb-title pe-3">{{ __('staff.addBtnText') }}</div>
                        <div class="ps-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 p-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('staff.index') }}">
                                            <img class="p-1" src="{{ asset('assets/icons/fi_2887367.png') }}" />
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ __('staff.staff') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="ms-auto">
                            <div class="">
                                <button data-url="{{ route('staff.index') }}" class="btn button exit">{{ __('comon.back') }}</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 mx-auto">
                            <div class="card">
                                <div class="card-body p-4">
                                    <h5 class="mb-4">{{ __('staff.addStaffDetail') }}</h5>
                                    <form class="row g-3" action="{{ route('staff.store') }}" method="POST" id="addStaffForm" enctype="multipart/form-data">
                                        @csrf
                                        @include('components.upload-profile', [
                                            'src' => asset('assets/images/avatars/dummy-image.webp'),
                                            'is_uploaded' => '',
                                            'type' => 'add',
                                            'updateUrl' => route('uploadStaffProfile'),
                                            'deleteUrl' => route('delete.user-photo'),
                                        ])
                                        <div class="col-md-6">
                                            @include('components.text-input', [
                                                'label' => __('staff.nameTh'),
                                                'name' => 'first_name',
                                                'icon' => 'user',
                                            ])
                                            <span id="first_name"></span>
                                        </div>
                                        <div class="col-md-6">
                                            @include('components.text-input', [
                                                'label' => __('staff.familyName'),
                                                'name' => 'family_name',
                                                'icon' => 'user',
                                            ])
                                        </div>
                                        <div class="col-md-6">
                                            @include('components.text-input', ['label' => __('staff.addressTh'), 'name' => 'address', 'icon' => 'current-location'])
                                        </div>
                                        <div class="col-md-6">
                                            @include('components.text-input', ['label' => __('staff.emailTh'), 'name' => 'email', 'icon' => 'envelope'])
                                            <span id="email"></span>
                                        </div>
                                        <div class="col-md-6">
                                            @include('components.text-input', [
                                                'label' => __('staff.idTh'),
                                                'name' => 'identification',
                                                'icon' => 'search-alt',
                                            ])
                                            <span id="identification"></span>
                                        </div>
                                        <div class="col-md-6">
                                            @include('components.text-input', [
                                                'label' => __('staff.telephoneTh'),
                                                'name' => 'telephone',
                                                'class' => 'numbers',
                                                'icon' => 'phone',
                                            ])
                                            <span id="telephone"></span>
                                        </div>
                                        <div class="col-md-6">
                                            @include('components.text-input', [
                                                'label' => __('staff.licenceNumberTh'),
                                                'name' => 'licence_number',
                                                'class' => 'numbers',
                                                'icon' => 'credit-card',
                                            ])
                                        </div>
                                        <div class="col-md-6">
                                            @include('components.select-input', [
                                                'label' => __('staff.professionTh'),
                                                'name' => 'profession_id',
                                                'icon' => 'user-circle',
                                                'options' => $professions,
                                            ])
                                        </div>
                                        <div class="col-md-6">
                                            @include('components.date-input', [
                                                'label' => __('staff.birthDateTh'),
                                                'name' => 'dob',
                                            ])
                                        </div>
                                        <div class="col-md-6">
                                            @include('components.select-input', [
                                                'label' => __('staff.roleTh'),
                                                'name' => 'role',
                                                'id' => 'roles',
                                                'icon' => 'user-check',
                                                'options' => $roles,
                                            ])
                                            <span id="role"></span>
                                        </div>
                                        <div class="col-md-6">
                                            @include('components.date-input', [
                                                'label' => __('staff.workStartDate'),
                                                'name' => 'work_start_date',
                                            ])
                                        </div>
                                        <div class="col-md-6">
                                            @include('components.file-input', [
                                                'label' => __('staff.document'),
                                                'name' => 'documents[]',
                                                'class' => 'documents',
                                                'id' => 'documents',
                                                'fileType' => 'document',
                                                'icon' => 'file',
                                                'value' => old('documents'),
                                                'multiple' => 'multiple',
                                            ])
                                            {{-- <div class="d-flex mt-2 choosenDocument" style="flex-wrap: wrap;"></div> --}}
                                        </div>
                                        <div class="col-md-12 document-section mt-4" style="display: none">
                                            <div class="time-table p-4">
                                                <h4 class="text-center">{{ __('staff.document') }}</h4>
                                                <div class="bg-white p-2 choosenDocument">
                                                    {{-- @include('components.document-detail') --}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            @include('components.multi-select-input', [
                                                'label' => __('staff.kindergartenTh'),
                                                'name' => 'kindergarten_id[]',
                                                'class' => 'kindergarten',
                                                'icon' => 'buildings',
                                                'options' => $kindergartens,
                                                'value' => old('kindergarten_id'),
                                            ])
                                        </div>
                                        @php
                                            $kindergartenCount = count(old('kindergarten_id', []));
                                        @endphp
                                        <div class="col-md-12 kindergarten-section" style="display: {{ @$kindergartenCount > 0 ? 'block' : 'none' }}">
                                            <div class="time-table">
                                                <h4 class="text-center">{{ __('staff.kindergartenTh') }}</h4>
                                                <div class="table-responsive selected-kindergarten" style="display: block !important;">
                                                    {{-- <table class="table table-borderd" style="width:100%;"> --}}
                                                        {{-- <thead>
                                                            <tr>
                                                                <th>{{ __('staff.name') }}</th>
                                                                <th>{{ __('staff.professionalRole') }}</th>
                                                                <th>{{ __('staff.association') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="selected-kindergarten">
                                                            @if ($kindergartenCount > 0)
                                                                @for ($i = 0; $i < $kindergartenCount; $i++)
                                                                    @include('components.kindergarten-tr', [
                                                                        'id' => @old('kindergarten_id', [])[$i],
                                                                        'index' => $i,
                                                                        'professions' => $professions,
                                                                        'memberRoles' => $memberRoles,
                                                                    ])
                                                                @endfor
                                                            @endif
                                                        </tbody> --}}
                                                    {{-- </table> --}}
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-12">
                                            <div class="time-table">
                                                <h4 class="text-center">{{ __('staff.scheduleHeading') }}</h4>
                                                @php
                                                    $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                                                @endphp

                                                <div class="bg-white p-2">
                                                    <div class="row">
                                                        <div class="col-md-2"><h5>Day</h5></div>
                                                        <div class="col-md-10"><h5>Kindergarten</h5></div>
                                                    </div>
                                                    @foreach ($days as $day)
                                                        @php
                                                            $index = $loop->index;
                                                            $startTime = 'schedule.' . $index . '.start_time';
                                                            $endTime = 'schedule.' . $index . '.end_time';
                                                        @endphp
                                                        <div class="row my-2">
                                                            <div class="col-md-2"><h6 class="pt-2">{{ __('staff.' . $day) }}</h6></div>
                                                            <div class="col-md-10">
                                                                @include('components.multi-select-input', [
                                                                    'name' => "weekly[$day][]",
                                                                    'class' => 'scheduleKindergarten',
                                                                    'icon' => 'buildings',
                                                                    'value' => old('weekly.'.$day),
                                                                    'dataName' => $day
                                                                ])
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="col-md-12 my-2 {{ $day }}-section" style="display: none">
                                                                    <div class="time-table">
                                                                        <div class="table-responsive" style="display: block !important;">
                                                                            <table class="table table-borderd" style="width:100%;">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>{{ __('staff.name') }}</th>
                                                                                        <th>{{ __('staff.start') }}</th>
                                                                                        <th>{{ __('staff.end') }}</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody class="{{ $day }}-body">
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div> --}}
                                        <div class="col-md-12">
                                            <div class="d-md-flex d-grid align-items-center gap-3">
                                                <input type="hidden" name="form_changed" id="formChanged" value="{{ old('form_changed') }}">
                                                <button type="submit" class="btn button px-4">{{ __('staff.addBtnText') }}</button>
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
        @include('components.cropper-modal')
    @endsection
    @push('customScript')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script type="text/javascript" src="{{ asset('assets/js/jquery.validate.js') }}"></script>
        <script src="{{ asset('assets/js/cropper.min.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('assets/css/cropper.min.css') }}" />
        @include('components.cropper-script')
        @include('staff.script')
        @include('components.time-range-js')
        <script>
        </script>
    @endpush
