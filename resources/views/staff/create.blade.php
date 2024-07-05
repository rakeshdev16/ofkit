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
                        <a href="{{ route('staff.index') }}" class="btn button">{{ __('staff.back') }}</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 mx-auto">
                    <div class="card">
                        <div class="card-body p-4">
                            <h5 class="mb-4">{{ __('staff.addStaffDetail') }}</h5>
                            <form class="row g-3" action="{{ route('staff.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="col-md-12 text-center upload-photo">
                                    <img src="https://placehold.co/150x150" id="previewImage" alt="">
                                    @error('member_photo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <input type="file" style="visibility: hidden" name="member_photo" id="imgInp">
                                <div class="col-md-6">
                                    @include('components.text-input', ['label' => __('staff.nameTh'), 'name' => 'name', 'icon' => 'user'])
                                </div>
                                <div class="col-md-6">
                                    @include('components.text-input', ['label' => __('staff.addressTh'), 'name' => 'address', 'icon' => 'current-location'])
                                </div>
                                <div class="col-md-6">
                                    @include('components.text-input', ['label' => __('staff.emailTh'), 'name' => 'email', 'icon' => 'envelope'])
                                </div>
                                <div class="col-md-6">
                                    @include('components.text-input', ['label' => __('staff.telephoneTh'), 'name' => 'telephone', 'icon' => 'phone'])
                                </div>
                                <div class="col-md-6">
                                    @include('components.text-input', ['label' => __('staff.licenceNumberTh'), 'name' => 'licence_number', 'icon' => 'credit-card'])
                                </div>
                                <div class="col-md-6">
                                    @include('components.select-input', [
                                        'label' => __('staff.professionTh'),
                                        'name' => 'profession_id',
                                        'icon' => 'user-circle',
                                        'options' => $professions
                                    ])
                                </div>
                                <div class="col-md-6">
                                    @include('components.date-input', ['label' => __('staff.birthDateTh'), 'name' => 'dob'])
                                </div>
                                <div class="col-md-6">
                                    @include('components.select-input', [
                                        'label' => __('staff.roleTh'), 
                                        'name' => 'role', 
                                        'icon' => 'user-check', 
                                        'options' => $roles
                                    ])
                                </div>
                                <div class="col-md-12">
                                    @include('components.select-input', [
                                        'label' => __('staff.kindergartenTh'),
                                        'name' => 'kindergarten_id',
                                        'class' => 'kindergarten',
                                        'icon' => 'buildings',
                                        'multiple' => 'multiple',
                                        'options' => $kindergartens
                                    ])
                                </div>
                                <div class="col-md-12 kindergarten-section" style="display: none">
                                    <div class="time-table">
                                        <h4 class="text-center">Kindergarten</h4>
                                        <div class="table-responsive" style="display: block !important;">
                                            <table class="table table-borderd" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Role</th>
                                                        <th>Profession</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="selected-kindergarten">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="time-table">
                                        <h4 class="text-center">{{ __('staff.scheduleHeading') }}</h4>
                                        @php
                                            $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                                        @endphp
                                        <div class="table-responsive" style="display: block !important;">
                                            <table class="table table-borderd" style="width:100%;">
                                                <tr>
                                                    <th>{{ __('staff.day') }}</th>
                                                    <th>{{ __('staff.start') }}</th>
                                                    <th>{{ __('staff.end') }}</th>
                                                </tr>
                                                @foreach ($days as $day)
                                                    <tr>
                                                        <td>
                                                            <h6 class="pt-2">{{ __('staff.'.$day) }}</h6>
                                                            <input type="hidden" name="schedule[{{$loop->index}}][day]" value="{{ $day }}">
                                                        </td>
                                                        <td>
                                                            <input
                                                                type="time"
                                                                name="schedule[{{$loop->index}}][start_time]"
                                                                class="form-control"
                                                                placeholder="Enter Start Date"
                                                            >
                                                        </td>
                                                        <td>
                                                            <input
                                                                type="time"
                                                                name="schedule[{{$loop->index}}][end_time]"
                                                                class="form-control"
                                                                placeholder="Enter end Date"
                                                            >
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="d-md-flex d-grid align-items-center gap-3">
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
@endsection
@push('customScript')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.kindergarten').select2();
        });

        $(document).on('click', '#previewImage', function() {
            $('#imgInp').click();
        });

        $(document).on('change', '.kindergarten', function() {
            var ids = $(this).val();
            $.ajax({
                type : 'GET',
                url : "{{ route('selected.kindergarten') }}",
                data : { ids: ids },
                success : function(data){
                    if (data.status == true) {
                        $('.selected-kindergarten').html('');
                        $('.kindergarten-section').show();
                        $('.selected-kindergarten').append(data.data);
                    }
                }
            });
        })
    </script>
@endpush
