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
                <div class="breadcrumb-title pe-3">{{ __('staff.detail') }}</div>
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
            <div class="container">
                <div class="main-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <a href="{{ route('staff.edit', $staff->id) }}" class=""><i class="bx bx-edit"></i></a>
                                    <div class="d-flex flex-column align-items-center text-center">
                                        <img src="{{ $staff->photo }}" alt="Admin" class="rounded-circle p-1 bg-primary staff-profile" width="110">
                                        <div class="mt-3">
                                            <h4>{{ $staff->name }}</h4>
                                            <p class="text-secondary mb-1">{{ $staff->profession->name }}</p>
                                            <p class="text-muted font-size-sm">{{ $staff->address }}</p>
                                        </div>
                                    </div>
                                    <hr class="my-4">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                            <h6 class="mb-0"><i class="bx bx-mail-send"></i> {{ __('staff.emailTh') }}</h6>
                                            <span class="text-secondary">{{ $staff->email }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                            <h6 class="mb-0"><i class="bx bx-phone-call"></i> {{ __('staff.telephoneTh') }}</h6>
                                            <span class="text-secondary">{{ $staff->telephone }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                            <h6 class="mb-0"><i class="bx bx-credit-card"></i> {{ __('staff.licenceNumberTh') }}</h6>
                                            <span class="text-secondary">{{ $staff->licence_number }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                            <h6 class="mb-0"><i class="bx bx-user-circle"></i> {{ __('staff.professionTh') }}</h6>
                                            <span class="text-secondary">{{ $staff->profession->name }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                            <h6 class="mb-0"><i class="bx bx-calendar-alt"></i> {{ __('staff.birthDateTh') }}</h6>
                                            <span class="text-secondary">{{ $staff->dob }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                            <h6 class="mb-0"><i class="bx bx-user-check"></i> {{ __('staff.roleTh') }}</h6>
                                            <span class="text-secondary">{{ $staff->getRoleNames()->first() }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-md-12 kindergarten-section">
                                    <div class="time-table">
                                        <h4 class="text-center">Kindergarten</h4>
                                        <table class="table table-borderd" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Professional Role</th>
                                                    <th>Association</th>
                                                </tr>
                                            </thead>
                                            <tbody class="selected-kindergarten">
                                                @foreach ($staff->staffKindergartens as $kindergarten)
                                                    @include('components.kindergarten-tr', [
                                                        'id' => $kindergarten->kindergarten_id,
                                                        'index' => $loop->index,
                                                        'professions' => $professions,
                                                        'roles' => $memberRoles,
                                                        'data' => $kindergarten
                                                    ])
                                                @endforeach
                                            </tbody>
                                        </table>
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
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('staff.day') }}</th>
                                                        <th>{{ __('staff.start') }}</th>
                                                        <th>{{ __('staff.end') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($days as $day)
                                                        @php
                                                            $data = @$staff->days[$loop->index];
                                                        @endphp
                                                        <tr>
                                                            <td>
                                                                <h6 class="pt-2">{{ __('staff.'.$day) }}</h6>
                                                            </td>
                                                            <td>
                                                                <input
                                                                    type="time"
                                                                    name="schedule[{{$loop->index}}][start_time]"
                                                                    class="form-control"
                                                                    placeholder="Enter Start Date"
                                                                    value="{{ @$data['start_time'] }}"
                                                                    disabled
                                                                >
                                                            </td>
                                                            <td>
                                                                <input
                                                                    type="time"
                                                                    name="schedule[{{$loop->index}}][end_time]"
                                                                    class="form-control"
                                                                    placeholder="Enter End Date"
                                                                    value="{{ @$data['end_time'] }}"
                                                                    disabled
                                                                >
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>                                
                            </div>
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
