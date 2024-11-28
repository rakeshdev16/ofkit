<!-- Modal: Hours Summary -->
<div class="modal fade" id="scoreSummary" tabindex="-1" aria-labelledby="scoreSummaryLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="scoreSummaryLabel">Hours Summary</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-wrap gap-3 mb-3">
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Draft List -->
<div class="modal fade" id="draft" tabindex="-1" aria-labelledby="draftLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="draftLabel">Draft List</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-unstyled">
                    <li class="d-flex flex-wrap gap-3 justify-content-between align-items-center border-bottom py-2">
                        <div class="d-flex gap-3">
                            <span class="badge button rounded-pill p-2 rounded-circle fs-6 fw-normal"><i class="fa fa-trash text-danger"></i></span>
                            <span class="badge button rounded-pill p-2 px-4 fs-6 fw-normal">Open</span>
                        </div>
                        <div class="text-end">
                            <small class="text-success">Last saved</small>
                            <p class="m-0">Jun, 15 / 3:35PM</p>
                        </div>
                        <div class="text-end">Draft 1</div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="appointmentModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                <h4 class="modal-title">Individual Intervention</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <select id="staffFilter" class="btn btn-outline-secondary mb-3 text-start rounded w-100 form-select">
                    <option value="">Choose Appointment</option>
                    <option value="John">John</option>
                    <option value="Ortal Remano">Ortal Remano</option>
                </select>
                <input type="date" class="w-100 mb-3 form-control border-1">
                <select id="staffFilter" class="btn btn-outline-secondary mb-3 text-start rounded w-100 form-select">
                    <option value="">Add Frequency (Repeat)</option>
                    <option value="John">John</option>
                    <option value="Ortal Remano">Ortal Remano</option>
                </select>
                <select id="staffFilter" class="btn btn-outline-secondary mb-3 text-start rounded w-100 form-select">
                    <option value="">Therapist name</option>
                    <option value="John">John</option>
                    <option value="Ortal Remano">Ortal Remano</option>
                </select>
                <select id="staffFilter" class="btn btn-outline-secondary mb-3 text-start rounded w-100 form-select">
                    <option value="">Child</option>
                    <option value="John">John</option>
                    <option value="Ortal Remano">Ortal Remano</option>
                </select>
                <textarea class="form-control mb-3 w-100" placeholder="Add Description" rows="5" id="comment" name="text"></textarea>
                <input type="file" class="mb-3">
                <div class="d-flex gap-3">
                    <button class="button p-2 px-4 rounded-pill border-0">Cancel</button>
                    <button class="button p-2 px-4 rounded-pill border-0">Edit</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- new appointment -->
<div class="modal" id="newAppointment">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">

            <!-- Modal body -->
            <div class="modal-body d-flex gap-3 flex-column">
                <button class="btn new-btn-appointment" data-bs-toggle="modal" data-bs-target="#appointmentModal">Individual</button>
                <button class="btn new-btn-appointment" data-bs-toggle="modal" data-bs-target="#appointmentModal">Group</button>
                <button class="btn new-btn-appointment" data-bs-toggle="modal" data-bs-target="#appointmentModal">Parental guidance</button>
                <button class="btn new-btn-appointment" data-bs-toggle="modal" data-bs-target="#appointmentModal">Staff Meeting</button>
                <button class="btn new-btn-appointment" data-bs-toggle="modal" data-bs-target="#appointmentModal">Documentation/break</button>
                <button class="btn new-btn-appointment" data-bs-toggle="modal" data-bs-target="#appointmentModal">Preparation</button>
                <button class="btn new-btn-appointment" data-bs-toggle="modal" data-bs-target="#appointmentModal">Tutorial</button>
                <button class="btn new-btn-appointment" data-bs-toggle="modal" data-bs-target="#appointmentModal">Other</button>
            </div>
        </div>
    </div>
</div>
<!-- hours summary -->
<div class="modal" id="appointmentModal">
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
                    <option value="">Choose Appointment</option>
                    <option value="John">John</option>
                    <option value="Ortal Remano">Ortal Remano</option>
                </select>
                <input type="date" class="w-100 mb-3 form-control border-1">
                <select id="staffFilter" class="btn btn-outline-secondary mb-3 text-start rounded w-100 form-select">
                    <option value="">Add Frequency (Repeat)</option>
                    <option value="John">John</option>
                    <option value="Ortal Remano">Ortal Remano</option>
                </select>
                <select id="staffFilter" class="btn btn-outline-secondary mb-3 text-start rounded w-100 form-select">
                    <option value="">Therapist name</option>
                    <option value="John">John</option>
                    <option value="Ortal Remano">Ortal Remano</option>
                </select>
                <select id="staffFilter" class="btn btn-outline-secondary mb-3 text-start rounded w-100 form-select">
                    <option value="">Child</option>
                    <option value="John">John</option>
                    <option value="Ortal Remano">Ortal Remano</option>
                </select>
                <textarea class="form-control mb-3 w-100" placeholder="Add Description" rows="5" id="comment" name="text"></textarea>
                <input type="file" class="mb-3">
                <div class="d-flex gap-3">
                    <button class="button p-2 px-4 rounded-pill border-0">Cancel</button>
                    <button class="button p-2 px-4 rounded-pill border-0">Save</button>
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