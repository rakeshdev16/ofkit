@extends('layout.master')
@push('customLink')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
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
                            <form class="row g-3" action="{{ route('staff.store') }}" method="POST" id="addStaffForm" enctype="multipart/form-data">
                                @csrf
                                <div class="col-md-12 text-center upload-photo">
                                    <img src="https://placehold.co/150x150" id="previewStaffImage" alt="">
                                    <div class="cam-icom">
                                        <i class="bx bx-camera"></i>
                                    </div>
                                    @error('member_photo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <input type="file" style="visibility: hidden" name="member_photo" id="staffProfileInp">
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
                                    @include('components.date-input', [
                                        'label' => __('staff.birthDateTh'),
                                        'name' => 'dob',
                                        'max' => date('Y-m-d')
                                    ])
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
                                    @include('components.file-input', [
                                        'label' => 'Document',
                                        'name' => 'doc',
                                        'fileType' => 'document',
                                        'icon' => 'file',
                                        'value' => old('doc')
                                    ])
                                </div>
                                <div class="col-md-12">
                                    @include('components.multi-select-input', [
                                        'label' => __('staff.kindergartenTh'),
                                        'name' => 'kindergarten_id[]',
                                        'class' => 'kindergarten',
                                        'icon' => 'buildings',
                                        'options' => $kindergartens,
                                        'value' => old('kindergarten_id')
                                    ])
                                </div>
                                <div class="col-md-12 kindergarten-section" style="display: {{ Session::get('kindergartenIds') > 0 ? 'block' : 'none' }}">
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
                                                            @include('components.kindergarten-tr', [
                                                                'id' => @Session::get('kindergartenIds')[$i],
                                                                'index' => $i,
                                                                'professions' => $professions,
                                                                'memberRoles' => $memberRoles,
                                                            ])
                                                        @endfor
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
                                                    @php
                                                        $index = $loop->index;
                                                        $startTime = 'schedule.'.$index.'.start_time';
                                                        $endTime = 'schedule.'.$index.'.end_time';
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            <h6 class="pt-2">{{ __('staff.'.$day) }}</h6>
                                                            <input type="hidden" name="schedule[{{$loop->index}}][day]" value="{{ $day }}">
                                                        </td>
                                                        <td>
                                                            <input
                                                                type="time"
                                                                name="schedule[{{$loop->index}}][start_time]"
                                                                class="form-control time-picker startTime"
                                                                data-index="{{$index}}"
                                                                value="{{ old($startTime) }}"
                                                            >
                                                            @error($startTime)
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                            <input
                                                                type="time"
                                                                name="schedule[{{$loop->index}}][end_time]"
                                                                class="form-control time-picker endTime{{$index}}"
                                                                data-index="{{$index}}"
                                                                value="{{ old($endTime) }}"
                                                            >
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
                                <div class="col-md-12">
                                    <input type="hidden" id="type" value="add">
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
<div class="modal fade" id="cropImageModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="img-container">
                    <img id="imageForCrop" src="https://avatars0.githubusercontent.com/u/3456749">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="button text-dark btn btn-secondary close" data-dismiss="modal">Cancel</button>
                <button type="button" class="button text-dark btn btn-primary" id="crop">Crop</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('customScript')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery.validate.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" />
    @include('staff.script')
    <script>
        $(document).ready(function() {
            $('.kindergarten').select2();
        });

        $(document).on('click', '#previewStaffImage', function() {
            $('#staffProfileInp').click();
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
                    } else {
                        $('.selected-kindergarten').html('');
                        $('.kindergarten-section').hide();
                    }
                }
            });
        });
    </script>
@endpush
