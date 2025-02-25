<!-- Modal: Hours Summary -->
<div class="modal fade" id="scoreSummary" tabindex="-1" aria-labelledby="scoreSummaryLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="scoreSummaryLabel">Appointment Summary</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-wrap gap-3 mb-2">
                    <ul class="nav nav-pills nav-pills-warning mb-2" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link btn button active" data-bs-toggle="pill" href="#childrenHoursTab" role="tab" aria-selected="true">
                                <div class="d-flex align-items-center"><div class="tab-title">Children</div></div>
                            </a>
                        </li>&nbsp;
                        <li class="nav-item" role="presentation">
                            <a class="nav-link btn button" data-bs-toggle="pill" href="#staffHoursTab" role="tab" aria-selected="false" tabindex="-1">
                                <div class="d-flex align-items-center"><div class="tab-title">Staff</div></div>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="table-responsive">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="childrenHoursTab" role="tabpanel">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th colspan="4" class="text-center">Children</th>
                                        <th colspan="3" class="text-center total-hours-bg">Total Appointments</th>
                                        <th colspan="3" class="text-center tabam-bg">Tabam</th>
                                        <th colspan="3" class="text-center">Matia</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center" colspan="4"></th>
                                        <th class="text-center total-hours-bg">Grp</th>
                                        <th class="text-center total-hours-bg">Indv</th>
                                        <th class="text-center total-hours-bg">Total</th>
                                        <th class="text-center tabam-bg">Grp</th>
                                        <th class="text-center tabam-bg">Indv</th>
                                        <th class="text-center tabam-bg">Total</th>
                                        <th class="text-center">Grp</th>
                                        <th class="text-center">Indv</th>
                                        <th class="text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="childrenSummary"></tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="staffHoursTab" role="tabpanel">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Staff</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center">Indv</th>
                                        <th class="text-center">Group</th>
                                        <th class="text-center">Staff Metting</th>
                                        <th class="text-center">Tutorial</th>
                                        <th class="text-center">Prep</th>
                                        <th class="text-center">Other</th>
                                    </tr>
                                </thead>
                                <tbody id="staffHours"></tbody>
                            </table>
                        </div>
                    </div>
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

<div class="modal" id="eventTypeModal">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body d-flex gap-3 flex-column">
                <button class="btn new-btn-appointment eventType" data-type="individual">
                    <i class="fa fa-user"></i> Individual
                </button>
                <button class="btn new-btn-appointment eventType" data-type="group">
                    <i class="fa fa-users"></i> Group
                </button>
                <button class="btn new-btn-appointment eventType" data-type="parental-guidance">
                    <i class="fa fa-child"></i> Parental Guidance
                </button>
                <button class="btn new-btn-appointment eventType" data-type="staff-meeting">
                    <i class="fa fa-handshake-o"></i> Staff Meeting
                </button>
                <button class="btn new-btn-appointment eventType" data-type="documentation-break">
                    <i class="fa fa-book"></i> Documentation/break
                </button>
                <button class="btn new-btn-appointment eventType" data-type="preparation">
                    <i class="fa fa-cogs"></i> Preparation
                </button>
                <button class="btn new-btn-appointment eventType" data-type="tutorial">
                    <i class="fa fa-laptop"></i> Tutorial
                </button>
                <button class="btn new-btn-appointment eventType" data-type="other">
                    <i class="fa fa-th"></i> Other
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="createEventModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                <h4 class="modal-title test">New Appointment</h4>
            </div>
            <div class="modal-body">
                <form action="" id="addEventForm" enctype="multipart/form-data">
                    <div class="card" id="formLoader">
                        <div class="card-body text-center">
                            <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status"> <span class="visually-hidden">Loading...</span></div>
                        </div>
                    </div>
                    <div id="appointmentFormDiv"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="eventDateModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                <h4 class="modal-title test">When do you want to start?</h4>
            </div>
            <div class="modal-body">
                <form action="" id="publishEventForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="input16" class="form-label fw-bold">Start Date</label>
                        <input type="text" name="start_date" id="publishStartDate" placeholder="Start Date" class="w-100 form-control border-1">
                    </div>
                    <div class="mb-3">
                        <label for="input16" class="form-label fw-bold">End Date</label>
                        <input type="text" name="end_date" id="publishEndDate" placeholder="End Date" class="w-100 form-control border-1">
                    </div>
                    <input type="hidden" name="ids" id="eventIds" value="{{ @$createdEventIds }}">
                    <input type="hidden" name="isAgree" id="isAgree" value="false"  >
                    <div class="d-flex gap-3">
                        <button class="button p-2 px-4 rounded-pill border-0" id="publishEventFormBtn">Save</button>
                        <button type="button" class="button p-2 px-4 rounded-pill border-0" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
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