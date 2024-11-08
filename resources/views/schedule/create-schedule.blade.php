@extends('layout.master')
@push('customLink')
    {{-- if need to add any cdn --}}
@endpush
@section('section')

    {{-- Main Content Section --}}
    <div class="container-fluid" style="margin-top: 130px;">
    <h3>Create New Schedule</h3>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="filters">
            <!-- Filter Dropdowns -->
            <select id="staffFilter" class="btn form-select btn-outline-secondary w-auto px-5 rounded-pill ">
                <option value="">Select Kindergarten</option>
                <option value="John">John</option>
                <option value="Ortal Remano">Ortal Remano</option>
            </select>
        </div>
        <div class="d-flex gap-3">
            <span class="badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" data-bs-toggle="modal" data-bs-target="#">Export as PDf</span>
            <span class="badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">Save as draft</span>
            <span class="badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">Cancel</span>
            <span class="badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">Publish</span>
            <span class="badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" data-bs-toggle="modal" data-bs-target="#newAppointment">New Appointment</span>
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
<div class="modal" id="newAppointment">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                <h4 class="modal-title">New Appointment</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
            <select id="staffFilter" class="btn btn-outline-secondary mb-3 text-start rounded w-100 form-select">
                <option value="">Select Kindergarten</option>
                <option value="John">John</option>
                <option value="Ortal Remano">Ortal Remano</option>
            </select>
            <input type="date" class="w-100 mb-3 form-control border-1">
            <select id="staffFilter" class="btn btn-outline-secondary mb-3 text-start rounded w-100 form-select">
                <option value="">Select Kindergarten</option>
                <option value="John">John</option>
                <option value="Ortal Remano">Ortal Remano</option>
            </select>
            <select id="staffFilter" class="btn btn-outline-secondary mb-3 text-start rounded w-100 form-select">
                <option value="">Select Kindergarten</option>
                <option value="John">John</option>
                <option value="Ortal Remano">Ortal Remano</option>
            </select>
            <select id="staffFilter" class="btn btn-outline-secondary mb-3 text-start rounded w-100 form-select">
                <option value="">Select Kindergarten</option>
                <option value="John">John</option>
                <option value="Ortal Remano">Ortal Remano</option>
            </select>
            <textarea class="form-control mb-3 w-100" rows="5" id="comment" name="text"></textarea>
            <input type="file" class="mb-3">
            <div class="d-flex gap-3">
                <button class="button p-2 px-4 rounded-pill border-0">Save</button>
                <button class="button p-2 px-4 rounded-pill border-0">Cancel</button>
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
    {{-- if need to add any script --}}
@endpush 