@extends('layout.master')
@push('customLink')
    
@endpush
@section('section')
<div class="wrapper">
    <div class="header-wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">{{ __('staff.editBtnText') }}</div>
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
                            <h5 class="mb-4">{{ __('staff.editStaffDetail') }}</h5>
                            <form class="row g-3" action="{{ route('staff.update', $staff->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="col-md-6">
                                    @include('components.text-input', ['label' => __('staff.nameTh'), 'name' => 'name', 'icon' => 'user', 'value' => $staff->name])
                                </div>
                                <div class="col-md-6">
                                    @include('components.text-input', ['label' => __('staff.addressTh'), 'name' => 'address', 'icon' => 'current-location', 'value' => $staff->address])
                                </div>
                                <div class="col-md-6">
                                    @include('components.text-input', ['label' => __('staff.emailTh'), 'name' => 'email', 'icon' => 'envelope', 'value' => $staff->email, 'readonly' => true])
                                </div>
                                <div class="col-md-6">
                                    @include('components.text-input', ['label' => __('staff.telephoneTh'), 'name' => 'telephone', 'icon' => 'phone', 'value' => $staff->telephone])
                                </div>
                                <div class="col-md-6">
                                    @include('components.text-input', ['label' => __('staff.licenceNumberTh'), 'name' => 'licence_number', 'icon' => 'credit-card', 'value' => $staff->licence_number])
                                </div>
                                <div class="col-md-6">
                                    @include('components.text-input', ['label' => __('staff.professionTh'), 'name' => 'profession', 'icon' => 'user-circle', 'value' => $staff->profession])
                                </div>
                                <div class="col-md-6">
                                    @include('components.date-input', ['label' => __('staff.birthDateTh'), 'name' => 'dob', 'value' => date('Y-m-d', strtotime($staff->dob))])
                                </div>
                                <div class="col-md-6">
                                    @include('components.select-input', [
                                        'label' => __('staff.roleTh'), 
                                        'name' => 'role', 
                                        'icon' => 'user-check', 
                                        'options' => $roles,
                                        'value' => $staff->getRoleNames()->first()
                                    ])
                                </div>
                                <div class="col-md-6">
                                    @include('components.select-input', [
                                        'label' => __('staff.kindergartenTh'), 
                                        'name' => 'kindergarten_id', 
                                        'icon' => 'buildings', 
                                        'options' => $kindergartens,
                                        'value' => @$staff->userKindergarten->kindergarten_id
                                    ])
                                </div>
                                <div class="col-md-6">
                                    @include('components.file-input', [
                                        'label' => 'Upload Photo',
                                        'name' => 'member_photo',
                                        'icon' => 'buildings',
                                        'value' => $staff->photo
                                    ])
                                </div>
                                <div class="col-md-12">
                                    <div class="time-table">
                                        <h4 class="text-center">{{ __('staff.scheduleHeading') }}</h4>
                                        @php
                                            $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                                        @endphp
                                        <table class="table table-borderd">
                                            <tr>
                                                <th>{{ __('staff.day') }}</th>
                                                <th>{{ __('staff.start') }}</th>
                                                <th>{{ __('staff.end') }}</th>
                                            </tr>
                                            @foreach ($days as $day)
                                                @php
                                                    $data = @$staff->days[$loop->index];
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <h6 class="pt-2">{{ __('staff.'.$day) }}</h6>
                                                        <input type="hidden" name="schedule[{{$loop->index}}][id]" value="{{ @$data['id'] }}">
                                                        <input type="hidden" name="schedule[{{$loop->index}}][day]" value="{{ $day }}">
                                                    </td>
                                                    <td>
                                                        <input
                                                            type="time"
                                                            name="schedule[{{$loop->index}}][start_time]"
                                                            class="form-control"
                                                            placeholder="Enter Start Date",
                                                            value="{{ @$data['start_time'] }}"
                                                        >
                                                    </td>
                                                    <td>
                                                        <input
                                                            type="time"
                                                            name="schedule[{{$loop->index}}][end_time]"
                                                            class="form-control"
                                                            placeholder="Enter end Date",
                                                            value="{{ @$data['end_time'] }}"
                                                        >
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-md-flex d-grid align-items-center gap-3">
                                        <button type="submit" class="btn button px-4">{{ __('staff.updateBtnText') }}</button>
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
    
@endpush
