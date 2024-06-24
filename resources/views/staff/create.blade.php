@extends('layout.master')
@push('customLink')
    
@endpush
@section('section')
<div class="wrapper">
    <div class="header-wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <div class="page-breadcrumb d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Add New</div>
                <div class="">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('staff.index') }}"><img class="p-1" src="{{ asset('assets/icons/fi_2887367.png') }}"/>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Staff</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="">
                        <a href="{{ route('staff.index') }}" class="btn button">Back</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 mx-auto">
                    <div class="card">
                        <div class="card-body p-4">
                            <h5 class="mb-4">Add Staff Detail</h5>
                            <form class="row g-3">
                                <div class="col-md-6">
                                    <label for="input16" class="form-label">Name</label>
                                    <div class="position-relative input-icon">
                                        <input type="text" class="form-control" id="input16" placeholder="Name">
                                        <span class="position-absolute top-50 translate-middle-y"><i class="bx bx-user"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="input15" class="form-label">I.D</label>
                                    <div class="position-relative input-icon">
                                        <input type="text" class="form-control" id="input15" placeholder="I.D">
                                        <span class="position-absolute top-50 translate-middle-y"><i class="bx bx-microphone"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="input14" class="form-label">Address</label>
                                    <div class="position-relative input-icon">
                                        <input type="text" class="form-control" id="input14" placeholder="Address">
                                        <span class="position-absolute top-50 translate-middle-y"><i class="bx bx-user"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="input13" class="form-label">Email</label>
                                    <div class="position-relative input-icon">
                                        <input type="text" class="form-control" id="input13" placeholder="Email">
                                        <span class="position-absolute top-50 translate-middle-y"><i class="bx bx-envelope"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="input18" class="form-label">License Number</label>
                                    <div class="position-relative input-icon">
                                        <input type="text" class="form-control" id="input18" placeholder="License Number">
                                        <span class="position-absolute top-50 translate-middle-y"><i class="bx bx-calendar"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="input20" class="form-label">Profession</label>
                                    <div class="position-relative input-icon">
                                        <input type="text" class="form-control" id="input20" placeholder="Profession">
                                        <span class="position-absolute top-50 translate-middle-y"><i class="bx bx-buildings"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="input20" class="form-label">Date of Birth</label>
                                    <div class="position-relative input-icon">
                                        <input type="date" class="form-control date-of-birth" id="input20" placeholder="Date of Birth">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="input20" class="form-label">Role</label>
                                    <div class="position-relative input-icon">
                                        <input type="text" class="form-control" id="input20" placeholder="Role">
                                        <span class="position-absolute top-50 translate-middle-y"><i class="bx bx-buildings"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-md-flex d-grid align-items-center gap-3">
                                        <button type="button" class="btn button px-4">Add</button>
                                        <button type="button" class="btn button px-4">Reset</button>
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
