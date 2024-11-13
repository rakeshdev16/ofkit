@extends('layout.master')
@push('customLink')
<link href="assets/css/main.css" type="text/css" rel="stylesheet" />
<script src="assets/js/daypilot-all.min.js"></script>
@endpush

@section('section')
<div class="container-fluid" style="margin-top: 130px;">
    <h3>Weekly Schedule</h3>
    <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center my-3">
        <div class="filters d-flex flex-wrap  gap-3">
            <!-- Filter Dropdowns -->
            <select id="staffFilter" class="form-select rounded-pill px-5 w-auto">
                <option value="">Staff</option>
                <option value="John">John</option>
                <option value="Ortal Remano">Ortal Remano</option>
            </select>
            <select id="childrenFilter" class="form-select rounded-pill px-5 w-auto">
                <option value="">Children</option>
                <option value="Child1">Child 1</option>
                <option value="Child2">Child 2</option>
            </select>
            <select id="kindergartenFilter" class="form-select rounded-pill px-5 w-auto">
                <option value="">Kindergarten Name</option>
                <option value="Hatsav">Hatsav</option>
                <option value="Nitzan">Nitzan</option>
                <option value="Alwan">Alwan</option>
            </select>
        </div>
        <div class="d-flex flex-wrap gap-3">
            <span class="badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" data-bs-toggle="modal" data-bs-target="#scoreSummary">Hours</span>
            <a href="/create-schedule" class="badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">Create New</a>
            <span class="badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">Edit</span>
            <a href="/schedule-history" class="badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">History</a>
            <span class="badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" data-bs-toggle="modal" data-bs-target="#draft">Draft</span>
        </div>
    </div>

    <!-- Schedule Display -->
    <div id="dp" class="table-responsive">
        <!-- Include your schedule component or calendar here -->
    </div>
</div>

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

<!-- Include Bootstrap JS -->
<script type="text/javascript">
    var dp = new DayPilot.Calendar("dp");
    // overlay start
    dp.rtl = true;


    // overlay end

    dp.onColumnFilter = function(args) {
        if (args.column.name.toUpperCase().indexOf(args.filter.toUpperCase()) === -1) {
            args.visible = false;
        }
    };

    // view
    dp.startDate = "2024-11-11"; // or just dp.startDate = "2013-03-25";

    // dp.days = 1;
    dp.allDayEventHeight = 50;

    dp.viewType = "Resources";
    dp.headerLevels = 2;
    dp.columns.list = [{
            name: "Sunday",
            children: [{
                    name: "Big Car #1",
                    id: "big1"
                },
                {
                    name: "Big Car #2",
                    id: "big2"
                },

            ]
        },
        {
            name: "Saturday",
            children: [{
                    name: "Small Car #1",
                    id: "small1"
                },
                {
                    name: "Small Car #2",
                    id: "small2"
                },
            ]
        },
        {
            name: "Friday",
            children: [{
                    name: "Small Car #1",
                    id: "small1"
                },
                {
                    name: "Small Car #2",
                    id: "small2"
                },
            ]
        },
        {
            name: "Thrusday",
            children: [{
                    name: "Small Car #1",
                    id: "small1"
                },
                {
                    name: "Small Car #2",
                    id: "small2"
                },
            ]
        },
        {
            name: "Wednesday",
            children: [{
                    name: "Small Car #1",
                    id: "small1"
                },
                {
                    name: "Small Car #2",
                    id: "small2"
                },
            ]
        },
        {
            name: "Tuesday",
            children: [{
                    name: "Small Car #1",
                    id: "small1"
                },
                {
                    name: "Small Car #2",
                    id: "small2"
                },
            ]
        },
        {
            name: "Monday",
            children: [{
                    name: "Small Car #1",
                    id: "small1"
                },
                {
                    name: "Small Car #2",
                    id: "small2"
                },
            ]
        }
    ];


    // event creating
    dp.onTimeRangeSelected = function(args) {
        var name = prompt("New event name:", "Event");
        if (!name) return;
        var e = new DayPilot.Event({
            start: args.start,
            end: args.end,
            id: DayPilot.guid(),
            resource: args.resource,
            text: "Event"

        });
        dp.events.add(e);
        dp.clearSelection();
        dp.message("Created");
    };
    dp.onBeforeEventRender = function(args) {
        args.data.html = `<div class="p-3 event-box bg-danger"  data-bs-toggle="modal" data-bs-target="#appointmentModal">
                           <p class="text-start fw-bold text-end mb-0"> ${args.data.text} <i class="fa fa-user" aria-hidden="true"></i></p>
                           <div class="d-flex">
                           <span>${args.data.start.toString("HH:mm")}</span>
                           </div>
                          </div>`,
            args.data.bubbleHtml = `<div class="p-3 calendar-event-overlay">
                                    <ul>
                                        <li class="d-flex gap-4 text-dark fs-6 mb-2 justify-content-end">
                                         ${args.data.text} <i class="fa fa-user"></i>
                                        </li>
                                        <li class="d-flex gap-4 text-dark fs-6 mb-2 justify-content-end">
                                         ${args.data.start.toString("HH:mm")} <i class="fa fa-calendar"></i>
                                        </li>
                                        <li class="d-flex gap-4 text-dark fs-6 mb-2 justify-content-end">
                                       1 Week, 16-08-2024  <i class="fa fa-clock-o"></i> 
                                        </li>
                                        <li class="d-flex gap-4 text-dark fs-6 mb-2 justify-content-end">
                                         John Bride <i class="fa fa-briefcase"></i>
                                        </li>
                                        <li class="d-flex gap-4 text-dark fs-6 mb-2 justify-content-end">
                                       
                                        <div class="text-end">
                                        <p class="mb-2">Marina</p>
                                        <p class="mt-2">In publishing and graphic design, Lorem ipsum is a placeholder text commonly used to demonstrate the visual form of a document or a typeface without relying on meaningful content. </p>
                                        </div>
                                         <i class="fa fa-user"></i> 
                                        </li>
                                    </ul>
                                </div>`;
    };
    // Set the bubbleHtml property to create the tooltip content


    dp.headerHeightAutoFit = true;

    dp.init();

    var e = new DayPilot.Event({
        start: new DayPilot.Date("2024-11-14T12:10:00"),
        end: new DayPilot.Date("2024-11-14T12:12:00").addHours(3),
        id: DayPilot.guid(),
        text: "Special event",
        resource: "J"

    });
    dp.events.add(e);
</script>

@endsection
