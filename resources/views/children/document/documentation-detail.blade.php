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
                <div class="breadcrumb-title pe-3">Detail</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('staff.index') }}">
                                    <img class="p-1" src="{{ asset('assets/icons/fi_2887367.png') }}"/>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Document</li>
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
                                <img src="{{ @$children->photo }}" alt="Admin" class="rounded-circle p-1 bg-primary staff-profile" width="110" height="110">
                                <div class="mt-3">
                                    <h4>{{ $children->name }}</h4>
                                </div>
                            </div>
                            <hr class="my-4">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <h6 class="mb-0">Date</h6>
                                    <span class="text-secondary">{{ @date('d/m/Y', strtotime($document->created_at)) ?? '-' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <h6 class="mb-0">Start Time</h6>
                                    <span class="text-secondary">{{ @$document->start_time ?? '-' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <h6 class="mb-0">End Time</h6>
                                    <span class="text-secondary">{{ @$document->end_time ?? '-' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <h6 class="mb-0">Kindergarten</h6>
                                    <span class="text-secondary">{{ @getKindergartenNameById($document->kindergarten_id) ?? '-' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <h6 class="mb-0">Occured</h6>
                                    <span class="text-secondary">{{ @$document->occured == 1 ? 'Yes' : 'No' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <h6 class="mb-0">Description</h6>
                                    <span class="text-secondary">{{ @$document->occured_description }}</span>
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
