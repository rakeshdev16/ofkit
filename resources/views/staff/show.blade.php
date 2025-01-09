@extends('layout.master')
@push('customLink')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .document {
            background: #fff;
        }

        .choosenDocument {
            background: #fff;
            border-radius: 5px;
            padding: 5px;
        }
    </style>
@endpush
@section('section')
    <div class="wrapper">
        <div class="header-wrapper">
            <div class="page-wrapper">
                <div class="page-content">
                    <div class="page-breadcrumb d-flex align-items-center mb-3">
                        <div class="breadcrumb-title pe-3">{{ __('staff.detail') }}</div>
                        <div class="ps-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 p-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('staff.index') }}?kindergarten_id={{ request()->kindergarten_id }}">
                                            <img class="p-1" src="{{ asset('assets/icons/fi_2887367.png') }}" />
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ __('staff.staff') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="ms-auto">
                            <div class="">
                                <a href="{{ route('staff.index') }}?kindergarten_id={{ request()->kindergarten_id }}" class="btn button">{{ __('comon.back') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div class="main-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="card">
                                        <div class="card-body">
                                            @if (Auth::user()->hasRole(['admin', 'manager']))
                                                <a href="{{ route('staff.edit', $staff->id) }}?kindergarten_id={{ request()->kindergarten_id }}" class="btn button">{{ __('comon.edit') }}</a>
                                            @endif
                                            <div class="d-flex flex-column align-items-center text-center">
                                                <img src="{{ @$staff->profile }}" alt="Admin" class="rounded-circle p-1 bg-primary staff-profile" width="110">
                                                <div class="mt-3">
                                                    <h4>{{ @$staff->first_name }}</h4>
                                                    <p class="text-secondary mb-1">{{ @$staff->profession->name }}</p>
                                                    <p class="text-muted font-size-sm">{{ @$staff->address }}</p>
                                                </div>
                                            </div>
                                            <hr class="my-4">
                                            <ul class="list-group list-group-flush profile-detail">
                                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                    <h6 class="mb-0"><i class="bx bx-user"></i> {{ __('staff.familyName') }}</h6>
                                                    <span class="text-secondary">{{ @$staff->family_name ?? '-' }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                    <h6 class="mb-0"><i class="bx bx-mail-send"></i> {{ __('staff.emailTh') }}</h6>
                                                    <span class="text-secondary">{{ @$staff->email ?? '-' }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                    <h6 class="mb-0"><i class="bx bx-search-alt"></i> {{ __('staff.idTh') }}</h6>
                                                    <span class="text-secondary">{{ @$staff->identification ?? '-' }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                    <h6 class="mb-0"><i class="bx bx-phone-call"></i> {{ __('staff.telephoneTh') }}</h6>
                                                    <span class="text-secondary">{{ @$staff->telephone ?? '-' }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                    <h6 class="mb-0"><i class="bx bx-credit-card"></i> {{ __('staff.licenceNumberTh') }}</h6>
                                                    <span class="text-secondary">{{ @$staff->licence_number ?? '-' }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                    <h6 class="mb-0"><i class="bx bx-user-circle"></i> {{ __('staff.professionTh') }}</h6>
                                                    <span class="text-secondary">{{ @$staff->profession->name ?? '-' }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                    <h6 class="mb-0"><i class="bx bx-calendar-alt"></i> {{ __('staff.birthDateTh') }}</h6>
                                                    <span class="text-secondary">{{ @$staff->date_of_birth ? $staff->date_of_birth : '-' }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                    <h6 class="mb-0"><i class="bx bx-user-check"></i> {{ __('staff.roleTh') }}</h6>
                                                    <span class="text-secondary">{{ @ucfirst($staff->getRoleNames()->first()) ?? '-' }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                    <h6 class="mb-0"><i class="bx bx-calendar"></i> {{ __('staff.workStartDate') }}</h6>
                                                    <span class="text-secondary">{{ @$staff->work_start_date ? $staff->work_start_date : '-' }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                    <h6 class="mb-0"><i class="bx bx-calendar"></i> {{ __('comon.createdOn') }}</h6>
                                                    <span class="text-secondary">{{ date('d/m/Y', strtotime($staff->created_at)) ?? '-' }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                    <h6 class="mb-0"><i class="bx bx-calendar"></i> {{ __('comon.updatedOn') }}</h6>
                                                    <span class="text-secondary">{{ date('d/m/Y', strtotime($staff->updated_at)) ?? '-' }}</span>
                                                </li>
                                                {{-- @foreach ($staff->documents as $document)
                                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                <h6 class="mb-0"><i class="bx bx-file"></i> Document</h6>
                                                <a href="{{ @$document->name }}" target="_blank" rel="noopener noreferrer">
                                                    {{ \Str::limit($document->file_name, 10, '...') ?? '-' }}
                                                </a>
                                            </li>
                                        @endforeach --}}
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="row">
                                        <div class="col-md-12 kindergarten-section">
                                            <div class="time-table table-responsive" style="display: block !important;">
                                                <h4 class="text-center">{{ __('staff.kindergartenTh') }}</h4>
                                                <table class="table table-borderd" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('staff.name') }}</th>
                                                            <th>{{ __('staff.professionalRole') }}</th>
                                                            <th>{{ __('staff.association') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="selected-kindergarten">
                                                        @forelse ($staffKindergartens as $kindergarten)
                                                            @include('components.kindergarten-tr', [
                                                                'id' => @$kindergarten['kindergarten_id'],
                                                                'index' => $loop->index,
                                                                'professions' => $professions,
                                                                'roles' => $memberRoles,
                                                                'data' => $kindergarten,
                                                            ])
                                                        @empty
                                                            <tr class="text-center">
                                                                <td colspan="3">{{ __('comon.emptyTableMsg') }}</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-4">
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
                                                                <th>{{ __('staff.kindergartenTh') }}</th>
                                                                <th>{{ __('staff.start') }}</th>
                                                                <th>{{ __('staff.end') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($staff->days()->get()->toArray() as $data)
                                                                <tr>
                                                                    <td><h6 class="pt-2">{{ __('staff.' . $data['day']) }}</h6></td>
                                                                    <td><div>{{ @getKindergartenNameById($data['kindergarten_id']) ?? '-' }}</div></td>
                                                                    <td><div><input type="time" class="form-control" value="{{ @$data['start_time'] }}" disabled></div></td>
                                                                    <td><div><input type="time" class="form-control" value="{{ @$data['end_time'] }}" disabled></div></td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                {{-- <div class="table-responsive" style="display: block !important;">
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
                                                                        <h6 class="pt-2">{{ __('staff.' . $day) }}</h6>
                                                                    </td>
                                                                    <td>
                                                                        <input type="time" name="schedule[{{ $loop->index }}][start_time]" class="form-control" value="{{ @$data['start_time'] }}" disabled>
                                                                    </td>
                                                                    <td>
                                                                        <input type="time" name="schedule[{{ $loop->index }}][end_time]" class="form-control" value="{{ @$data['end_time'] }}" disabled>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div> --}}
                                            </div>
                                        </div>
                                        @if (isset($staff->documents) && count($staff->documents) > 0)
                                            {{-- <div class="col-md-12">
                                                    <div class="time-table">
                                                        <h4 class="text-center">{{ __('staff.document') }}</h4>
                                                        <div class="table-responsive" style="display: block !important;">
                                                            <div class="d-flex choosenDocument" style="flex-wrap: wrap;">
                                                                @foreach ($staff->documents as $document)
                                                                    <div class="document mt-1 doc{{ $document->id }}">
                                                                        <a href="{{ $document->name }}" target="_blank" rel="noopener noreferrer">
                                                                            {{ $document->file_name }}
                                                                        </a>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> --}}

                                            <div class="col-md-12 document-section mt-4">
                                                <div class="time-table p-4">
                                                    <h4 class="text-center">{{ __('staff.document') }}</h4>
                                                    <div class="bg-white p-2">
                                                        @foreach ($staff->documents as $document)
                                                            <a href="{{ $document->name }}" target="__blank" data-toggle="tooltip" data-placement="bottom" title="{{ __('comon.view') }}">
                                                                <i class="bx bx-file icon"></i>
                                                            </a>
                                                            {{-- <h5 class="document-name"><a href="{{ $document->name }}" target="__blank">{{ $document->file_name }}</a></h5> --}}
                                                            <p class="border p-2">{!! description($document->description, 80) !!}</p>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery.validate.js') }}"></script>
    <script src="{{ asset('assets/js/cropper.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/cropper.min.css') }}" />
    @include('components.cropper-script')
    @include('staff.script')
    @endpush
