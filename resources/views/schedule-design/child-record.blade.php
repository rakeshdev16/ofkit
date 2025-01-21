@extends('layout.master')
@push('customLink')
<link href="assets/css/main.css" type="text/css" rel="stylesheet" />
<script src="assets/js/daypilot-all.min.js"></script>
@endpush
@section('section')
<div class="container-fluid" style="margin-top: 130px;">
    <h3 class="text-start text-dark">Child Records</h3>
    <div class="my-4 p-3">
        <div class="d-flex align-items-center justify-content-end gap-4">

            <div class="row w-100">
                <div class="col-lg-3 mb-3">
                    <p class="text-dark fw-normal fs-6">Adi <span class="green-light fw-medium">:First Name</span></p>
                </div>
                <div class="col-lg-3 mb-3">
                    <p class="text-dark fw-normal fs-6">Zohar <span class="green-light fw-medium">:Last Name</span></p>
                </div>
                <div class="col-lg-3 mb-3">
                    <p class="text-dark fw-normal fs-6 justify-content-end d-flex gap-2" style="flex-direction:row-reverse">2132333 <span class="green-light fw-medium">:ID</span></p>
                </div>
                <div class="col-lg-3 mb-3">
                    <p class="text-dark fw-normal fs-6">First Grade <span class="green-light fw-medium"> :Kindergarten</span></p>
                </div>
                <div class="col-lg-3 mb-3">
                    <p class="text-dark fw-normal fs-6 justify-content-end d-flex gap-2" style="flex-direction:row-reverse"> 03/11/1998 <span class="green-light fw-medium"> Child’s Birthday: </span></p>
                </div>
                <div class="col-lg-3 mb-3">
                    <p class="text-dark fw-normal fs-6 d-flex gap-2 justify-content-end" style="flex-direction:row-reverse">26 <span class="green-light fw-medium">:Child’s Age</span></p>
                </div>
            </div>
            <div class="avatar-img">
                <img src="assets/images/avatars/avatar-1.png" class="w-100 h-100" alt="">
            </div>
        </div>
    </div>
    <div id="dp"></div>
</div>
@endsection
@push('customScript')

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
    dp.startDate = "2024-11-11";
    dp.viewType = "Week";

    // dp.days = 1;
    dp.allDayEventHeight = 50;

    dp.viewType = "Resources";
    dp.headerLevels = 1;
    dp.columns.list = [{
            name: "Sunday",

        },
        {
            name: "Monday",

        },
        {
            name: "Tuesday",

        },
        {
            name: "Wednesday",

        },
        {
            name: "Thrusday",

        },
        {
            name: "Friday",

        },
        {
            name: "Saturday",

        },





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
        start: new DayPilot.Date("2024-11-14T12:10:00"),
        end: new DayPilot.Date("2024-11-14T12:12:00").addHours(3),
        id: DayPilot.guid(),
        text: "Special event",
        resource: "J"

    });
    dp.events.add(e);
</script>
@endpush