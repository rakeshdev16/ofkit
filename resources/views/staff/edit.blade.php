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
                        <div class="breadcrumb-title pe-3">{{ __('staff.editBtnText') }}</div>
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
                                <a href="{{ route('staff.index') }}" class="btn button">{{ __('staff.back') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 mx-auto">
                            <div class="card">
                                <div class="card-body p-4">
                                    <h5 class="mb-4">{{ __('staff.editStaffDetail') }}</h5>
                                    <form class="row g-3" action="{{ route('staff.update', $staff->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="col-md-12 text-center upload-photo">
                                            <img src="{{ $staff->photo }}" id="previewImage" alt="">
                                            <div class="cam-icom">
                                                <i class="bx bx-camera"></i>
                                            </div>
                                            @error('member_photo')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <input type="file" style="visibility: hidden" name="member_photo" id="profileInp" accept="image/*">
                                        <div class="col-md-6">
                                            @include('components.text-input', [
                                                'label' => __('staff.nameTh'),
                                                'name' => 'name',
                                                'icon' => 'user',
                                                'value' => $staff->name,
                                            ])
                                        </div>
                                        <div class="col-md-6">
                                            @include('components.text-input', [
                                                'label' => __('staff.addressTh'),
                                                'name' => 'address',
                                                'icon' => 'current-location',
                                                'value' => $staff->address,
                                            ])
                                        </div>
                                        <div class="col-md-6">
                                            @include('components.text-input', [
                                                'label' => __('staff.emailTh'),
                                                'name' => 'email',
                                                'icon' => 'envelope',
                                                'value' => $staff->email,
                                                'readonly' => true,
                                            ])
                                        </div>
                                        <div class="col-md-6">
                                            @include('components.text-input', [
                                                'label' => __('staff.telephoneTh'),
                                                'name' => 'telephone',
                                                'class' => 'numbers',
                                                'icon' => 'phone',
                                                'value' => $staff->telephone,
                                            ])
                                        </div>
                                        <div class="col-md-6">
                                            @include('components.text-input', [
                                                'label' => __('staff.licenceNumberTh'),
                                                'name' => 'licence_number',
                                                'class' => 'numbers',
                                                'icon' => 'credit-card',
                                                'value' => $staff->licence_number,
                                            ])
                                        </div>
                                        <div class="col-md-6">
                                            @include('components.select-input', [
                                                'label' => __('staff.professionTh'),
                                                'name' => 'profession_id',
                                                'icon' => 'user-circle',
                                                'options' => $professions,
                                                'value' => $staff->profession_id,
                                            ])
                                        </div>
                                        <div class="col-md-6">
                                            @include('components.date-input', [
                                                'label' => __('staff.birthDateTh'),
                                                'name' => 'dob',
                                                'max' => date('Y-m-d'),
                                                'value' => date('Y-m-d', strtotime($staff->dob)),
                                            ])
                                        </div>
                                        <div class="col-md-6">
                                            @include('components.text-input', [
                                                'label' => __('staff.roleTh'),
                                                'name' => 'role',
                                                'icon' => 'user-check',
                                                'value' => $staff->getRoleNames()->first(),
                                                'readonly' => true,
                                            ])
                                        </div>
                                        <div class="col-md-12">
                                            @include('components.file-input', [
                                                'label' => 'Document',
                                                'name' => 'documents[]',
                                                'fileType' => 'document',
                                                'icon' => 'file',
                                                'value' => old('doc'),
                                                'multiple' => 'multiple'
                                            ])
                                            <div class="d-flex" style="flex-wrap: wrap;">
                                                @foreach ($staff->documents as $document)
                                                    <div class="document mt-1 doc{{ $document->id }}">
                                                        <a href="{{ $document->name }}" target="_blank" rel="noopener noreferrer">
                                                            {{ $document->file_name }}
                                                        </a>
                                                        <i class="bx bx-x staffDocument" data-id="{{ $document->id }}"></i>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            @include('components.multi-select-input', [
                                                'label' => __('staff.kindergartenTh'),
                                                'name' => 'kindergarten_id[]',
                                                'class' => 'kindergarten',
                                                'icon' => 'buildings',
                                                'options' => $kindergartens,
                                                'value' =>
                                                    old('kindergarten_id') ??
                                                    @$staff->staffKindergartens->pluck('kindergarten_id')->toArray(),
                                            ])
                                        </div>
                                        <div class="col-md-12 kindergarten-section"
                                            style="display: {{ old('kindergarten_id') || count($staff->staffKindergartens) > 0 ? '' : 'none' }}">
                                            <div class="time-table">
                                                <h4 class="text-center">Kindergarten</h4>
                                                <div class="table-responsive" style="display: block !important;">
                                                    <table class="table table-borderd" style="width:100%;">
                                                        <thead>
                                                            <tr>
                                                                <th>Name</th>
                                                                <th>Professional Role</th>
                                                                <th>Association</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="selected-kindergarten">
                                                            @if (Session::get('kindergartenIds'))
                                                                @for ($i = 0; $i < count(Session::get('kindergartenIds')); $i++)
                                                                    @include(
                                                                        'components.kindergarten-tr',
                                                                        [
                                                                            'id' => @Session::get(
                                                                                'kindergartenIds')[$i],
                                                                            'index' => $i,
                                                                            'professions' => $professions,
                                                                            'memberRoles' => $memberRoles,
                                                                        ]
                                                                    )
                                                                @endfor
                                                            @else
                                                                @foreach ($staff->staffKindergartens as $kindergarten)
                                                                    @include(
                                                                        'components.kindergarten-tr',
                                                                        [
                                                                            'id' =>
                                                                                $kindergarten->kindergarten_id,
                                                                            'index' => $loop->index,
                                                                            'professions' => $professions,
                                                                            'memberRoles' => $memberRoles,
                                                                            'data' => $kindergarten,
                                                                        ]
                                                                    )
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="time-table">
                                                <h4 class="text-center">{{ __('staff.scheduleHeading') }}</h4>
                                                @php
                                                    $days = [
                                                        'sunday',
                                                        'monday',
                                                        'tuesday',
                                                        'wednesday',
                                                        'thursday',
                                                        'friday',
                                                        'saturday',
                                                    ];
                                                @endphp
                                                <div class="table-responsive" style="display: block !important;">
                                                    <table class="table table-borderd" style="width:100%;">
                                                        <tr>
                                                            <th>{{ __('staff.day') }}</th>
                                                            <th>{{ __('staff.start') }}</th>
                                                            <th>{{ __('staff.end') }}</th>
                                                        </tr>
                                                        @foreach ($days as $day)
                                                            @php
                                                                $data = @$staff->days[$loop->index];
                                                                $startTime = 'schedule.' . $loop->index . '.start_time';
                                                                $endTime = 'schedule.' . $loop->index . '.end_time';
                                                            @endphp
                                                            <tr>
                                                                <td>
                                                                    <h6 class="pt-2">{{ __('staff.' . $day) }}</h6>
                                                                    <input type="hidden"
                                                                        name="schedule[{{ $loop->index }}][id]"
                                                                        value="{{ @$data['id'] }}">
                                                                    <input type="hidden"
                                                                        name="schedule[{{ $loop->index }}][day]"
                                                                        value="{{ $day }}">
                                                                </td>
                                                                <td>
                                                                    <input type="time"
                                                                        name="schedule[{{ $loop->index }}][start_time]"
                                                                        class="form-control time-picker"
                                                                        placeholder="Enter Start Date",
                                                                        value="{{ old($startTime) ?? @$data['start_time'] }}">
                                                                    @error($startTime)
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </td>
                                                                <td>
                                                                    <input type="time"
                                                                        name="schedule[{{ $loop->index }}][end_time]"
                                                                        class="form-control time-picker"
                                                                        placeholder="Enter end Date",
                                                                        value="{{ old($endTime) ?? @$data['end_time'] }}">
                                                                    @error($endTime)
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="hidden" name="user_id" id="userId" value="{{ $staff->id }}">
                                            <input type="hidden" id="type" value="update">
                                            <input type="hidden" id="url" value="{{ route('uploadStaffProfile') }}">
                                            <div class="d-md-flex d-grid align-items-center gap-3">
                                                <button type="submit"
                                                    class="btn button px-4">{{ __('staff.updateBtnText') }}</button>
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
        <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
        <script src="{{ asset('assets/js/cropper.min.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('assets/css/cropper.min.css') }}" />
        @include('components.cropper-script')
        <script>
            $(document).ready(function() {
                $('.kindergarten').select2();
            });

            $(document).on('change', '.kindergarten', function() {
                var ids = $(this).val();
                var user_id = "{{ @request()->segment(2) }}";
                $.ajax({
                    type: 'GET',
                    url: "{{ route('selected.kindergarten') }}",
                    data: {
                        ids: ids,
                        user_id: user_id,
                    },
                    success: function(data) {
                        if (data.status == true) {
                            $('.selected-kindergarten').html('');
                            $('.kindergarten-section').show();
                            $('.selected-kindergarten').append(data.data);
                        } else {
                            $('.selected-kindergarten').html('');
                            $('.kindergarten-section').hide();
                        }
                    }
                });
            })

            $(document).on('click', '.staffDocument', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },
                            type: 'POST',
                            url: "{{ route('document.delete') }}",
                            data: {id: id},
                            success: function(data) {
                                if (data.status == true) {
                                    $('.doc'+id).remove();
                                    toastr.success(data.message);
                                }
                            }
                        });            
                    }
                });
            })
        </script>
    @endpush
