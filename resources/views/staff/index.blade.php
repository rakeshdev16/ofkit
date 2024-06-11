@extends('layout.master')
@section('section')
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                @include('components.bread-crumb', ['title' => 'Staff', 'subTitle' => 'Members'])

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-sm-4">
                                        <div class="search-box me-2 mb-2 d-inline-block">
                                            <div class="position-relative">
                                                <input type="text" class="form-control" placeholder="Search...">
                                                <i class="bx bx-search-alt search-icon"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="text-sm-end">
                                            <button type="button" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2"><i class="mdi mdi-plus me-1"></i> Add New</button>
                                            <button type="button" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2"><i class="mdi mdi-plus me-1"></i> Edit</button>
                                            <button type="button" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2"><i class="mdi mdi-plus me-1"></i> Move to Archive</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table align-middle table-nowrap table-check">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 20px;" class="align-middle">
                                                    <div class="form-check font-size-16">
                                                        <input class="form-check-input" type="checkbox" id="checkAll">
                                                        <label class="form-check-label" for="checkAll"></label>
                                                    </div>
                                                </th>
                                                <th class="align-middle">Kindergarten</th>
                                                <th class="align-middle">Role</th>
                                                <th class="align-middle">License Number</th>
                                                <th class="align-middle">Profession</th>
                                                <th class="align-middle">E-mail</th>
                                                <th class="align-middle">Telephone</th>
                                                <th class="align-middle">Address</th>
                                                <th class="align-middle">Birth Date</th>
                                                <th class="align-middle">Name</th>
                                                <th class="align-middle">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="form-check font-size-16">
                                                        <input class="form-check-input" type="checkbox" id="orderidcheck01">
                                                        <label class="form-check-label" for="orderidcheck01"></label>
                                                    </div>
                                                </td>
                                                <td><a href="javascript: void(0);" class="text-body fw-bold">Hatsav</a></td>
                                                <td>Professional Therapist</td>
                                                <td>100-153</td>
                                                <td>Physiotherapy</td>
                                                <td><span class="badge badge-pill badge-soft-success font-size-12">jkdf@dfg</span></td>
                                                <td>052-123-4567</td>
                                                <td>Mi St., Haifa</td>
                                                <td>15/06/1999</td>
                                                <td>Aleen Mahmod</td>
                                                <td>
                                                    <div class="d-flex gap-3">
                                                        <a href="javascript:void(0);" class="text-success"><i
                                                                class="mdi mdi-pencil font-size-18"></i></a>
                                                        <a href="javascript:void(0);" class="text-danger"><i
                                                                class="mdi mdi-delete font-size-18"></i></a>
                                                    </div>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                                <ul class="pagination pagination-rounded justify-content-end mb-2">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="javascript: void(0);" aria-label="Previous">
                                            <i class="mdi mdi-chevron-left"></i>
                                        </a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="javascript: void(0);">1</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">2</a></li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">3</a></li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">4</a></li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">5</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="javascript: void(0);" aria-label="Next">
                                            <i class="mdi mdi-chevron-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layout.footer')
    </div>
@endsection
