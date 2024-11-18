@extends('layout.master')
@push('customLink')
<link href="{{ asset('assets/css/main.css')}}" type="text/css" rel="stylesheet" />
<script src="{{ asset('assets/js/daypilot-all.min.js')}}"></script>
@endpush
@section('section')

{{-- Main Content Section --}}
<div class="container-fluid" style="margin-top: 130px;">
    <h3>Create New Schedule</h3>
    <div class="d-flex justify-content-between my-3">
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
    <div id="dp1"></div>
</div>

<!-- new appointment -->
<div class="modal" id="newAppointment" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <!-- Modal body -->
            <div class="modal-body d-flex gap-3 flex-column">
                <button class="btn new-btn-appointment" data-bs-toggle="modal" data-bs-target="#appointmentModal">Individual</button>
                <button class="btn new-btn-appointment">Group</button>
                <button class="btn new-btn-appointment">Parental guidance</button>
                <button class="btn new-btn-appointment">Staff Meeting</button>
                <button class="btn new-btn-appointment">Documentation/break</button>
                <button class="btn new-btn-appointment">Preparation</button>
                <button class="btn new-btn-appointment">Tutorial</button>
                <button class="btn new-btn-appointment">Other</button>
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

@endsection
@push('customScript')
<script type="text/javascript">
    var dp = new DayPilot.Calendar("dp1");
    dp.rtl = true;
    // overlay start


    // overlay end

    dp.onColumnFilter = function(args) {
        if (args.column.name.toUpperCase().indexOf(args.filter.toUpperCase()) === -1) {
            args.visible = false;
        }
    };

    // view
    var today = new Date();
    dp.startDate = today; // or just dp.startDate = "2013-03-25";
    dp.viewType = "Week";
    // dp.days = 1;
    dp.allDayEventHeight = 50;

    dp.viewType = "Resources";
    dp.headerLevels = 2;
    dp.columns.list = {!! json_encode(calenderHeader()) !!};
    dp.columnWidthSpec = "Fixed";
    dp.columnMinWidth = 100;


    // event creating
    dp.onTimeRangeSelected = function(args) {
        $('#newAppointment').show();
        // var name = prompt("New event name:", "Event");
        // if (!name) return;
        // var e = new DayPilot.Event({
        //     start: args.start,
        //     end: args.end,
        //     id: DayPilot.guid(),
        //     resource: args.resource,
        //     text: "Event"
        // });
        // console.log(e);

        // dp.events.add(e);
        // dp.clearSelection();
        // dp.message("Created");
    };
    dp.dayBeginsHour = 7;
    dp.timeHeaderCellDuration = 15;
    dp.cellDuration = 15;
    dp.hourWidth = 100;
    dp.cellHeight = 50;

    dp.onBeforeTimeHeaderRender = function(args) {
        var hour = DayPilot.Date.today().addTime(args.header.time);
        console.log(hour);

        args.header.html = hour.toString("h:mm");
    };

    dp.onBeforeEventRender = function(args) {
        args.data.html = `<div class="p-3 event-box bg-danger">
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

        // generate and load events
    for (let i = 0; i < 10; i++) {
        const duration = Math.floor(Math.random() * 1.2);
        const start = Math.floor(Math.random() * 6) - 3; // -3 to 3

        dp.events.add({
            start: new DayPilot.Date(today).addDays(start),
            end: new DayPilot.Date(today).addDays(start).addDays(duration),
            id: DayPilot.guid(),
            text: "Event " + i
        });
    }

    dp.init();

    // var e = new DayPilot.Event({
    //     start: new DayPilot.today,
    //     end: new DayPilot.today,
    //     id: DayPilot.guid(),
    //     text: "Special event",
    //     resource: "J"

    // });
    // dp.events.add(e);
</script>
@endpush
