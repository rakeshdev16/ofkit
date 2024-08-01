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
                <div class="breadcrumb-title pe-3">Profile</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('staff.index') }}">
                                    <img class="p-1" src="{{ asset('assets/icons/fi_2887367.png') }}"/>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">User</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="">
                        <a href="{!! URL::previous() !!}" class="btn button">{{ __('staff.back') }}</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center text-center">
                                <img src="{{ @$user->profile }}" alt="Admin" class="rounded-circle p-1 bg-primary staff-profile" width="110" height="110">
                                <div class="mt-3">
                                    <h4>{{ $user->name }}</h4>
                                    <p class="text-secondary mb-1">{{ @$user->email }}</p>
                                    <p class="text-secondary mb-1">{{ @$user->profession->name }}</p>
                                    <p class="text-muted font-size-sm">{{ @$user->address }}</p>
                                    <a href="{{ route('profile.edit') }}" class="btn button">Edit</a>
                                    <a href="{{ route('change-password.index') }}" class="btn button">Change Password</a>
                                </div>
                            </div>
                            <hr class="my-4">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <h6 class="mb-0"><i class="bx bx-mail-send"></i> {{ __('staff.emailTh') }}</h6>
                                    <span class="text-secondary">{{ @$user->email ?? '-' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <h6 class="mb-0"><i class="bx bx-phone-call"></i> {{ __('staff.telephoneTh') }}</h6>
                                    <span class="text-secondary">{{ @$user->telephone ?? '-' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <h6 class="mb-0"><i class="bx bx-credit-card"></i> {{ __('staff.licenceNumberTh') }}</h6>
                                    <span class="text-secondary">{{ @$user->licence_number ?? '-' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <h6 class="mb-0"><i class="bx bx-calendar-alt"></i> {{ __('staff.birthDateTh') }}</h6>
                                    <span class="text-secondary">{{ @$user->dob ?? '-' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <h6 class="mb-0"><i class="bx bx-user-check"></i> {{ __('staff.roleTh') }}</h6>
                                    <span class="text-secondary">{{ @$user->getRoleNames()->first() ?? '-' }}</span>
                                </li>
                            </ul>
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
