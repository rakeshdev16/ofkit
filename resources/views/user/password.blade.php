@extends('layout.master')
@push('customLink')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('section')
<div class="wrapper">
    <div class="header-wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">{{ __('staff.addBtnText') }}</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('staff.index') }}">
                                    <img class="p-1" src="{{ asset('assets/icons/fi_2887367.png') }}"/>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('staff.staff') }}</li>
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
                            <h5 class="mb-4">Change Password</h5>
                            <form class="row g-3" action="{{ route('change-password.update') }}" method="POST">
                                @csrf
                                <div class="col-md-6">
                                    @include('components.password-input', [
                                        'label' => 'Old Password',
                                        'name' => 'old_password',
                                        'icon' => 'key',
                                    ])
                                </div>
                                <div class="col-md-6">
                                    @include('components.password-input', [
                                        'label' => 'New Password',
                                        'name' => 'new_password',
                                        'icon' => 'key',
                                    ])
                                </div>
                                <div class="col-md-12">
                                    @include('components.password-input', [
                                        'label' => 'Confirm Password',
                                        'name' => 'confirm_password',
                                        'icon' => 'key',
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
    <script>
        $(document).on('click', '.show-password', function() {
            var input = $(this).parent().parent().find('input');
            if (input.attr('type') == 'password') {
                input.attr('type', 'text');
                $(this).removeClass('fa-eye');
                $(this).addClass('fa-eye-slash');
            } else {
                input.attr('type', 'password');
                $(this).addClass('fa-eye');
                $(this).removeClass('fa-eye-slash');
            }
        });
    </script>
@endpush
