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
    <div class="mb-5">

        <div id="dp"></div>
    </div>
</div>

<!-- new appointment -->
{{-- <div class="modal" id="newAppointment">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">

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
</div> --}}
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
                <form action="{{route('therapy-schedule.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <select id="appointment_type" name="type" class="btn btn-outline-secondary mb-3 text-start rounded w-100 form-select">
                        <option value="" selected disabled>Choose Appointment Type</option>
                        <option value="individual">Individual</option>
                        <option value="group">Group</option>
                        <option value="parental guidance">Parental guidance</option>
                        <option value="staff meeting">Staff Meeting</option>
                        <option value="documentation">Documentation</option>
                        <option value="preparation">Preparation</option>
                        <option value="tutorial">Tutorial</option>
                        <option value="other">Other</option>
                    </select>
                    <input type="datetime-local" name='schedule_time' class="w-100 mb-3 form-control border-1">
                    <select id="frequency_repeat" name="frequency_repeat" class="btn btn-outline-secondary mb-3 text-start rounded w-100 form-select">
                        <option value="" selected disabled>Add Frequency (Repeat)</option>
                        <option value="bi-weekly">Bi-weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                    <select id="staffFilter" name="start" class="btn btn-outline-secondary mb-3 text-start rounded w-100 form-select">
                        <option value="" selected disabled>When it will start</option>
                        <option value="one week offset" class="bi-weekly">One week offset</option>
                        <option value="from start week offset" class="bi-weekly">From start week offset</option>
                        <option value="start week" class="monthly">Start week</option>
                        <option value="after 1 week" class="monthly">After 1 week</option>
                        <option value="after 2 week" class="monthly">After 2 week</option>
                        <option value="after 3 week" class="monthly">After 3 week</option>
                    </select>
                    <input type="test" id="group_name" name='group_name' placeholder="Group Name" class="w-100 mb-3 form-control border-1">
                    <select id="staffFilter" name='therapist_id' class="btn btn-outline-secondary mb-3 text-start rounded w-100 form-select">
                        <option value="" selected disabled>Therapist name</option>
                        @foreach ($therapists as $therapist)
                            <option value="{{$therapist->id}}">{{$therapist->name}}</option>
                        @endforeach
                    </select>
                    <select id="children_ids" name='children_ids[]' class="btn btn-outline-secondary mb-3 text-start rounded w-100 form-select">
                        <option value="" selected disabled>Child</option>
                        @foreach ($childrens as $children)
                            <option value="{{$children->id}}">{{$children->name}}</option>
                        @endforeach
                    </select>
                    <textarea class="form-control mb-3 w-100" placeholder="Add Description" rows="5" id="comment" name="description"></textarea>
                    <input type="file" id="image" name="image" class="mb-3">
                    <div class="d-flex gap-3">
                        <button class="button p-2 px-4 rounded-pill border-0">Cancel</button>
                        <button type="submit" class="button p-2 px-4 rounded-pill border-0">Save</button>
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

@endsection
@push('customScript')
<script type="text/javascript">
    var dp = new DayPilot.Calendar("dp");
    dp.rtl = true;
    // overlay start


    // overlay end

    dp.onColumnFilter = function(args) {
        if (args.column.name.toUpperCase().indexOf(args.filter.toUpperCase()) === -1) {
            args.visible = false;
        }
    };

    // view
    dp.startDate = "2024-11-19"; // or just dp.startDate = "2013-03-25";
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

    dp.dayBeginsHour = 7;
    dp.timeHeaderCellDuration = 15;
    dp.cellDuration = 15;
    dp.hourWidth = 100;
    dp.cellHeight = 50;

    dp.onBeforeTimeHeaderRender = function(args) {
        var hour = DayPilot.Date.today().addTime(args.header.time);
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

    dp.init();

    var e = new DayPilot.Event({
        start: new DayPilot.Date("2024-11-19T12:10:00"),
        end: new DayPilot.Date("2024-11-19T12:12:00").addHours(3),
        id: DayPilot.guid(),
        text: "Special event",
        resource: "J"

    });
    dp.events.add(e);

    $('#frequency_repeat').on('change', function (){
        let value = $(this).val();
        if(value == 'bi-weekly'){
            $('.monthly').hide();
            $('.bi-weekly').show();
        }else{
            $('.bi-weekly').hide();
            $('.monthly').show();
        }
    });
    $('.bi-weekly').hide();
    $('.monthly').hide();
    $('#group_name').hide();

    $('#appointment_type').on('change', function (){
        let value = $(this).val();
        if(value == 'preparation' || value == 'documentation' || value == 'tutorial' || value == 'other'){
            $('#comment').hide();
            $('#image').hide();
            $('#children_ids').hide();
        }else{
            $('#comment').show();
            $('#image').show();
            $('#children_ids').show();
            if(value == 'group' || value == 'staff meeting'){
                $('#group_name').show();
            }else{
                $('#group_name').hide();
            }
        }

    });
</script>

@endpush
