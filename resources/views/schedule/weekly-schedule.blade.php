@extends('layout.master')
@push('customLink')
@endpush

@section('section')
<div class="container-fluid" style="margin-top: 130px;">
    <h3>Weekly Schedule</h3>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="filters">
            <!-- Filter Dropdowns -->
            <select id="staffFilter" class="btn btn-outline-secondary mx-1 rounded-pill px-3">
                <option value="">Staff</option>
                <option value="John">John</option>
                <option value="Ortal Remano">Ortal Remano</option>
            </select>
            <select id="childrenFilter" class="btn btn-outline-secondary mx-1 rounded-pill px-3">
                <option value="">Children</option>
                <option value="Child1">Child 1</option>
                <option value="Child2">Child 2</option>
            </select>
            <select id="kindergartenFilter" class="btn btn-outline-secondary mx-1 rounded-pill px-3">
                <option value="">Kindergarten Name</option>
                <option value="Hatsav">Hatsav</option>
                <option value="Nitzan">Nitzan</option>
                <option value="Alwan">Alwan</option>
            </select>
        </div>
        <div class="d-flex gap-3">
            <span class="badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" data-bs-toggle="modal" data-bs-target="#scoreSummary">Hours</span>
            <span class="badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">Create New</span>
            <span class="badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">Edit</span>
            <span class="badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">History</span>
            <span class="badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" data-bs-toggle="modal" data-bs-target="#draft">Draft</span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-style table-bordered">
            <thead class="table-dark">
                <tr>
                    <th></th>
                    <th>Sunday</th>
                    <th>Monday</th>
                    <th>Tuesday</th>
                    <th>Wednesday</th>
                    <th>Thursday</th>
                    <th>Friday</th>
                    <th>Saturdat</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>08:00</td>
                    <td></td>
                    <td class="bg-info text-white">
                        John <br />
                        08:15AM
                    </td>
                    <td></td>
                    <td class="bg-success text-white">
                        Eating Group <br />
                        08:45AM
                    </td>
                    <td class="bg-warning text-dark">
                        Meeting Group <br />
                        08:30AM
                    </td>
                    <td>Employee 1</td>
                </tr>
                <tr>
                    <td>08:30</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="bg-primary text-white">
                        Project Review <br />
                        08:45AM
                    </td>
                    <td>Employee 2</td>
                </tr>
                <tr>
                    <td>08:45</td>
                    <td></td>
                    <td></td>
                    <td class="bg-secondary text-white">
                        Workshop <br />
                        08:50AM
                    </td>
                    <td></td>
                    <td></td>
                    <td>Employee 3</td>
                </tr>
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>
</div>
<!-- hours summary -->
<div class="modal" id="scoreSummary">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                <h4 class="modal-title">Hours Summary</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="d-flex gap-3 mb-3">
                    <span class="badge button rounded-pill p-2 px-4 fs-6 fw-normal">Staff</span>
                    <span class="badge button rounded-pill p-2 px-4 fs-6 fw-normal">Children</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="3" class="text-center">Matia</th>
                                <th colspan="3" class="text-center">Tabam</th>
                                <th colspan="3" class="text-center">Total Hours</th>
                                <th colspan="3" class="text-center">Children</th>
                            </tr>
                            <tr>
                                <th class="text-center">Grp</th>
                                <th class="text-center">Indv</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Grp</th>
                                <th class="text-center">Indv</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Grp</th>
                                <th class="text-center">Indv</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Grp</th>
                                <th class="text-center">Indv</th>
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Add rows as needed -->
                            <tr>
                                <td class="text-center">Example</td>
                                <td class="text-center">Example</td>
                                <td class="text-center">Example</td>
                                <td class="text-center">Example</td>
                                <td class="text-center">Example</td>
                                <td class="text-center">Example</td>
                                <td class="text-center">Example</td>
                                <td class="text-center">Example</td>
                                <td class="text-center">Example</td>
                                <td class="text-center">Example</td>
                                <td class="text-center">Example</td>
                                <td class="text-center">Example</td>
                            </tr>
                            <!-- Repeat rows as needed -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- draft list -->

<div class="modal" id="draft">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                <h4 class="modal-title">Draft List</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                
                <div class="table-responsive">
                        <ul class="p-0 m-0">
                            <!-- Add rows as needed -->
                            <li class="d-flex gap-3 justify-content-between align-items-center border-bottom py-2">
                                <div class="text-end">
                                    <div class="d-flex gap-3">
                                        <span class="badge button rounded-pill p-2 rounded-circle fs-6 fw-normal"><i class="fa fa-trash text-danger"></i></span>
                                        <span class="badge button rounded-pill p-2 px-4 fs-6 fw-normal">Open</span>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <small class="text-success">Last saved</small>
                                    <p class="m-0">Jun, 15 /3:35PM</p>
                                </div>
                                <div class="text-end">Draft 1</div>
                                
                                
                            </li>
                            <!-- Repeat rows as needed -->
                        </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('customScript')
  
@endpush
