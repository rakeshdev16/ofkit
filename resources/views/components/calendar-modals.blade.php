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
                <button class="btn new-btn-appointment eventType" data-type="individual">Individual</button>
                <button class="btn new-btn-appointment eventType" data-type="group">Group</button>
                <button class="btn new-btn-appointment eventType" data-type="parental-guidance">Parental Guidance</button>
                <button class="btn new-btn-appointment eventType" data-type="staff-meeting">Staff Meeting</button>
                <button class="btn new-btn-appointment eventType" data-type="initial-evaluation">Initial Evaluation</button>
                <button class="btn new-btn-appointment eventType" data-type="final-evaluation">Final Evaluation</button>
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
                    <select id="appointmentType" name="type" class="btn btn-outline-secondary mb-3 text-start rounded w-100 form-select">
                        <option value="">Choose Appointment</option>
                        <option value="individual">Individual</option>
                        <option value="group">Group</option>
                        <option value="parental-guidance">Parental Guidance</option>
                        <option value="staff-meeting">Staff Meeting</option>
                        <option value="initial-evaluation">Initial Evaluation</option>
                        <option value="final-evaluation">Final Evaluation</option>
                    </select>
                    <input type="datetime-local" name="" id="appointmentDate" class="w-100 mb-3 form-control border-1">
                    {{-- <input type="text" id="appointmentDate" class="w-100 mb-3 form-control border-1" placeholder="Pick a day and time"> --}}
                    <select id="appointmentFrequency" name="frequency_repeat" class="btn btn-outline-secondary mb-3 text-start rounded w-100 form-select">
                        <option value="">Select Frequency (Repeat)</option>
                        <option value="monthly">Monthly</option>
                        <option value="by_weekly">By Weekly</option>
                    </select>
                    <select id="monthlyFrequency" name="start" class="btn btn-outline-secondary mb-3 text-start rounded w-100 form-select" style="display: none">
                        <option value="">Select Monthly option</option>
                        <option value="start_week">Start Week</option>
                        <option value="after_one_week">After 1 Week</option>
                        <option value="after_second_week">After 2 Week</option>
                        <option value="after_third_week">After 3 Week</option>
                    </select>
                    <select id="weeklyFrequency" name="start" class="btn btn-outline-secondary mb-3 text-start rounded w-100 form-select" style="display: none">
                        <option value="">Select Weekly Option</option>
                        <option value="one_week_ofset">One Week Ofset</option>
                        <option value="from_start_week">From Start Week</option>
                    </select>
                    <input type="text" name="group_name" id="appointmentGroupName" class="w-100 mb-3 form-control border-1" placeholder="Group Name">
                    <select id="therapist" name="therapist_id" class="btn btn-outline-secondary mb-3 text-start rounded w-100 form-select">
                        <option value="">Therapist name</option>
                        @foreach ($therapists as $therapist)
                            <option value="{{ $therapist->id }}">{{ $therapist->name }}</option>
                        @endforeach
                    </select>
                    <select id="children" name="children_id" class="btn btn-outline-secondary mb-3 text-start rounded w-100 form-select">
                        <option value="">Child</option>
                        @foreach ($childrens as $children)
                            <option value="{{ $children->id }}">{{ $children->name }}</option>
                        @endforeach
                    </select>
                    <textarea class="form-control mb-3 w-100" placeholder="Add Description" rows="5" id="comment" name="description"></textarea>
                    <input type="file" name="image" class="mb-3">
    
                    <input type="hidden" name="resource" id="resource">
                    <input type="hidden" name="day" id="appointmentDay">
                    <input type="hidden" name="start_date" id="startDate">
                    <input type="hidden" name="end_date" id="endDate">
                    <input type="hidden" name="draft_name" id="draftName">
                    <div class="d-flex gap-3">
                        <button class="button p-2 px-4 rounded-pill border-0" id="createEventModalBtn">Save</button>
                        <button class="button p-2 px-4 rounded-pill border-0">Cancel</button>
                    </div>
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
                <input type="datetime-local" name="start_date" id="appointmentDate" placeholder="Start Date" class="w-100 mb-3 form-control border-1">
                <input type="datetime-local" name="end_date" id="appointmentDate" placeholder="End Date" class="w-100 mb-3 form-control border-1">
                <div class="d-flex gap-3">
                    <button class="button p-2 px-4 rounded-pill border-0" id="">Save</button>
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