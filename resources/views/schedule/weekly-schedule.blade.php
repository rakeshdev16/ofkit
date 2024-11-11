@extends('layout.master')
@push('customLink')
<link href="assets/css/main.css" type="text/css" rel="stylesheet" />
<script src="assets/js/daypilot-all.min.js"></script>
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
    <div id="dp"></div>
    
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
<script src="assets/js/app.js"></script>
<script>
    var elements = {
        filter: document.querySelector("#filter"),
        clear: document.querySelector("#clear"),
    };

    elements.filter.addEventListener("keyup", function() {
        var query = this.value;
        dp.columns.filter(query); // see dp.onColumnFilter below
    });

    elements.clear.addEventListener("click", function(ev) {
        ev.preventDefault();
        elements.filter.value = "";
        dp.columns.filter(null);
    });
</script>

<script type="text/javascript">
    var dp = new DayPilot.Calendar("dp");
    // overlay start


    // overlay end

    dp.onColumnFilter = function(args) {
        if (args.column.name.toUpperCase().indexOf(args.filter.toUpperCase()) === -1) {
            args.visible = false;
        }
    };

    // view
    dp.startDate = "2013-03-25"; // or just dp.startDate = "2013-03-25";
    dp.days = 1;
    dp.allDayEventHeight = 25;

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

    dp.headerHeightAutoFit = true;

    dp.init();

    var e = new DayPilot.Event({
        start: new DayPilot.Date("2013-03-25T12:00:00"),
        end: new DayPilot.Date("2013-03-25T12:00:00").addHours(3),
        id: DayPilot.guid(),
        text: "Special event",
        resource: "J"

    });
    dp.events.add(e);
</script>
<script></script>
@endpush