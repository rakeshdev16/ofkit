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

<div class="modal" id="eventTypeModal">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body d-flex gap-3 flex-column">
                <button class="btn new-btn-appointment eventType" data-type="individual">
                    <img src="{{ asset('assets/icons/Individual.svg') }}" width="18" > Individual
                </button>
                <button class="btn new-btn-appointment eventType" data-type="group">
                    <img src="{{ asset('assets/icons/Group.svg') }}" width="18" > Group
                </button>
                <button class="btn new-btn-appointment eventType" data-type="parental-guidance">
                    <img src="{{ asset('assets/icons/ParentalGuide.svg') }}" width="18" > Parental Guidance
                </button>
                <button class="btn new-btn-appointment eventType" data-type="staff-meeting">
                    <img src="{{ asset('assets/icons/StaffMeating.svg') }}" width="18"> Staff Meeting
                </button>
                <button class="btn new-btn-appointment eventType" data-type="documentation-break">
                    <img src="{{ asset('assets/icons/DocumentBreak.svg') }}" width="18" > Documentation/break
                </button>
                <button class="btn new-btn-appointment eventType" data-type="preparation">
                    <img src="{{ asset('assets/icons/Prepare.svg') }}" width="18" > Preparation
                </button>
                <button class="btn new-btn-appointment eventType" data-type="tutorial">
                    <img src="{{ asset('assets/icons/tutorials.svg') }}" width="18" > Tutorial
                </button>
                <button class="btn new-btn-appointment eventType" data-type="other">
                    <img src="{{ asset('assets/icons/Other.svg') }}" width="18" > Other
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
                        <input type="date" name="start_date" id="publishStartDate" placeholder="Start Date" class="w-100 form-control border-1" min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="mb-3">
                        <input type="date" name="end_date" id="publishEndDate" placeholder="End Date" class="w-100 mb-3 form-control border-1">
                    </div>
                    <input type="hidden" name="status" value="published">
                    <input type="hidden" name="ids" id="eventIds">
                    <input type="hidden" name="kindergarten_id" id="associatedKindergartenId">
                    <div class="d-flex gap-3">
                        <button class="button p-2 px-4 rounded-pill border-0" id="publishEventFormBtn">Save</button>
                        <button class="button p-2 px-4 rounded-pill border-0">Cancel</button>
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