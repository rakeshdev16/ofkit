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
                        <div class="breadcrumb-title pe-3">Update</div>
                        <div class="ps-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 p-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('profile.index') }}">
                                            <img class="p-1" src="{{ asset('assets/icons/fi_2887367.png') }}" />
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">User</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="ms-auto">
                            <div class="">
                                <button data-url="{{ route('profile.index') }}" class="btn button exit">{{ __('comon.back') }}</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 mx-auto">
                            <div class="card">
                                <div class="card-body p-4">
                                    <h5 class="mb-4">Update Profile Detail</h5>
                                    <form class="row g-3" action="{{ route('profile.update') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @include('components.upload-profile', [
                                            'src' => @$user->profile,
                                            'is_uploaded' => @$user->photo,
                                            'userId' => @$user->id,
                                            'type' => 'update',
                                            'updateUrl' => route('userProfile.update'),
                                            'deleteUrl' => route('delete.user-photo'),
                                        ])
                                        <div class="col-md-6">
                                            @include('components.text-input', [
                                                'label' => __('staff.nameTh'),
                                                'name' => 'name',
                                                'icon' => 'user',
                                                'value' => $user->name,
                                            ])
                                        </div>
                                        <div class="col-md-6">
                                            @include('components.text-input', [
                                                'label' => __('staff.addressTh'),
                                                'name' => 'address',
                                                'icon' => 'current-location',
                                                'value' => $user->address,
                                            ])
                                        </div>
                                        <div class="col-md-6">
                                            @include('components.text-input', [
                                                'label' => __('staff.emailTh'),
                                                'name' => 'email',
                                                'icon' => 'envelope',
                                                'value' => $user->email,
                                                'readonly' => true,
                                            ])
                                        </div>
                                        <div class="col-md-6">
                                            @include('components.text-input', [
                                                'label' => __('staff.telephoneTh'),
                                                'name' => 'telephone',
                                                'class' => 'numbers',
                                                'icon' => 'phone',
                                                'value' => $user->telephone,
                                            ])
                                        </div>
                                        <div class="col-md-6">
                                            @include('components.text-input', [
                                                'label' => __('staff.licenceNumberTh'),
                                                'name' => 'licence_number',
                                                'class' => 'numbers',
                                                'icon' => 'credit-card',
                                                'value' => $user->licence_number,
                                            ])
                                        </div>
                                        <div class="col-md-6">
                                            @include('components.date-input', [
                                                'label' => __('staff.birthDateTh'),
                                                'name' => 'dob',
                                                'max' => date('Y-m-d'),
                                                'value' => $user->dob ? date('Y-m-d', strtotime($user->dob)) : '',
                                            ])
                                        </div>
                                        <div class="col-md-12">
                                            <div class="d-md-flex d-grid align-items-center gap-3">
                                                <button type="submit" class="btn button px-4">Update</button>
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
        <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>

        @include('components.cropper-script')
        <script>
            $(document).on('click', '#previewImage', function() {
                $('#imgInp').click();
            });
        </script>
    @endpush
