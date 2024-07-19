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
                                    <img class="p-1" src="{{ asset('assets/icons/fi_2887367.png') }}"/>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">User</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="">
                        <a href="{{ route('profile.index') }}" class="btn button">{{ __('staff.back') }}</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 mx-auto">
                    <div class="card">
                        <div class="card-body p-4">
                            <h5 class="mb-4">Update Profile Detail</h5>
                            <form class="row g-3" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="col-md-12 text-center upload-photo">
                                    <img src="{{ @$user->photo }}" id="previewImage" alt="">
                                    <div class="cam-icom">
                                        <i class="bx bx-camera"></i>
                                    </div>
                                    @error('member_photo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <input type="file" style="visibility: hidden" name="member_photo" id="imgInp" accept="image/png, image/gif, image/jpeg">
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
                                        'readonly' => true
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
                                        'value' => date('Y-m-d', strtotime($user->dob)),
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
@endsection
@push('customScript')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery.validate.js') }}"></script>
    <script>
        $(document).on('click', '#previewImage', function() {
            $('#imgInp').click();
        });
    </script>
@endpush
