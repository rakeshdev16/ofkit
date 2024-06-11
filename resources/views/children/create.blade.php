@extends('layout.master')
@section('section')
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                @include('components.bread-crumb', ['title' => 'Children', 'subTitle' => 'Add Children'])

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-lg-12">
                                        <div class="mt-4 mt-xl-0">
                                            <form>
                                                <div class="row">                                                            
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="formrow-firstname-input">First Name</label>
                                                            <input type="text" class="form-control" placeholder="Enter First Name" id="formrow-firstname-input">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="formrow-firstname-input">Family Name</label>
                                                            <input type="text" class="form-control" placeholder="Enter Family Name" id="formrow-firstname-input">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="formrow-email-input">.I.D</label>
                                                            <input type="text" class="form-control" placeholder="Enter .I.D" id="formrow-email-input">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="formrow-email-input">Gender</label>
                                                            <select class="form-select">
                                                                <option>Select</option>
                                                                <option>Male</option>
                                                                <option>Female</option>
                                                            </select>                                                        
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="formrow-password-input">Date of Birth</label>
                                                            <input type="date" class="form-control" placeholder="Enter date of birth" id="formrow-password-input">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="formrow-password-input">Age</label>
                                                            <input type="text" class="form-control" placeholder="Enter age" id="formrow-password-input">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="formrow-password-input">Address</label>
                                                            <input type="text" class="form-control" placeholder="Enter address" id="formrow-password-input">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="formrow-password-input">Kindergarten</label>
                                                            <input type="file" class="form-control" id="formrow-password-input">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="formrow-email-input">Functioning</label>
                                                            <select class="form-select">
                                                                <option>Select</option>
                                                                <option>High</option>
                                                                <option>Others</option>
                                                            </select>                                                        
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="formrow-password-input">Diagnosis</label>
                                                            <input type="file" class="form-control" id="formrow-password-input">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="formrow-email-input">Status</label>
                                                            <select class="form-select">
                                                                <option>Select</option>
                                                                <option>New</option>
                                                                <option>Continuing</option>
                                                            </select>                                                        
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="formrow-password-input">Tabam Services star</label>
                                                            <input type="date" class="form-control" placeholder="Enter age" id="formrow-password-input">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="formrow-password-input">HMO</label>
                                                            <input type="file" class="form-control" id="formrow-password-input">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-4">
                                                    <button type="submit" class="btn btn-primary w-md">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layout.footer')
    </div>
@endsection
