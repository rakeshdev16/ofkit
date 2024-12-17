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
                    <div class="mb-3">
                        <select id="appointmentType" name="type" class="btn btn-outline-secondary text-start rounded w-100 form-select">
                            <option value="">Choose Appointment</option>
                            <option value="individual">Individual</option>
                            <option value="group">Group</option>
                            <option value="parental-guidance">Parental Guidance</option>
                            <option value="staff-meeting">Staff Meeting</option>
                            <option value="initial-evaluation">Initial Evaluation</option>
                            <option value="final-evaluation">Final Evaluation</option>
                        </select>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="w-100">
                            <select id="day" name="day" class="form-control border-1">
                                <option value="Sunday">Sunday</option>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <select id="appointmentFrequency" name="frequency_repeat" class="form-control">
                            <option value="Weekly">Weekly</option>
                            <option value="Bi-weekly">Bi-weekly</option>
                            <option value="Monthly">Monthly</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <select id="Monthly" name="start" class="form-control" style="display: none">
                            <option value="Start Week">Start Week</option>
                            <option value="After 1 Week">After 1 Week</option>
                            <option value="After 2 Week">After 2 Week</option>
                            <option value="After 3 Week">After 3 Week</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <select id="Bi-weekly" name="start" class="form-control" style="display: none">
                            <option value="One Week Ofset">One Week Ofset</option>
                            <option value="From Start Week">From Start Week</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="group_name" id="appointmentGroupName" class="w-100 form-control border-1" placeholder="Group Name">
                    </div>
                    <div class="mb-3" id="therapistDropdownDiv">
                        {{-- <select id="therapist" name="therapist_id" class="multipleTherapist form-control" multiple>
                            <option value="">Therapist name</option>
                        </select> --}}
                    </div>
                    <div class="mb-3" id="childrenDropdownDiv">
                        {{-- <select id="children" name="children_id[]" class="form-control">
                            <option value="">Child</option>
                        </select> --}}
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control w-100" placeholder="Add Description" rows="5" name="description" id="description"></textarea>
                    </div>
                    <div class="mb-3">
                        <input type="file" id="eventFile" name="image" class="form-control">
                        <input type="hidden" id="eventOldFile" name="old_image" class="form-control">
                        <div class="event-file" style="display: none"></div>
                    </div>
                    <input type="hidden" name="id" id="eventId">
                    <input type="hidden" name="resource" id="resource">
                    <input type="hidden" name="therapist_id" id="therapistId">
                    <input type="hidden" name="start_time" id="startTime">
                    <input type="hidden" name="end_time" id="endTime">
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
                <form action="" id="publishEventForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <input type="datetime-local" name="start_date" placeholder="Start Date" class="w-100 form-control border-1">
                    </div>
                    <div class="mb-3">
                        <input type="datetime-local" name="end_date" placeholder="End Date" class="w-100 mb-3 form-control border-1">
                    </div>
                    <input type="hidden" name="status" value="published">
                    <input type="hidden" name="ids" id="eventIds">
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