<div class="mb-5">
    <div id="dp"></div>
</div>
<script type="text/javascript">
    var dp = new DayPilot.Calendar("dp");
    dp.rtl = true;

    // Ensure the first day of the week is Monday
    // dp.firstDayOfWeek = 1;  // 0 for Sunday, 1 for Monday

    // dp.onColumnFilter = function(args) {
    //     if (args.column.name.toUpperCase().indexOf(args.filter.toUpperCase()) === -1) {
    //         args.visible = false;
    //     }
    // };

    // Calculate start of the current week (Monday)
    var today = new Date();
    var startOfWeek = new Date(today.setDate(today.getDate() - today.getDay() + 1)); // Monday as the first day

    // Format the start date in 'yyyy-MM-dd' format
    var startDate = startOfWeek.getFullYear() + '-' + (startOfWeek.getMonth() + 1).toString().padStart(2, '0') + '-' + startOfWeek.getDate().toString().padStart(2, '0');
    dp.startDate = startDate;
    // dp.viewType = "Week";

    dp.allDayEventHeight = 50;

    dp.viewType = "Resources";
    dp.headerLevels = 2;
    dp.columns.list = {!! json_encode(calenderHeader()) !!};
    // dp.columnWidthSpec = "Fixed";
    // dp.columnMinWidth = 20;
    dp.events.list = {!! json_encode($formattedEvents) !!};

    dp.onTimeRangeSelected = function(args) {
        var name = prompt("New event name:", "Event");
        if (!name) return;

        // Use the selected time range's day and time for the event
        var selectedStartDate = args.start;  // Exact selected start time
        var selectedEndDate = args.end;      // Exact selected end time

        var e = new DayPilot.Event({
            start: selectedStartDate,  // Correctly set the event's start date
            end: selectedEndDate,      // Correctly set the event's end date
            id: DayPilot.guid(),
            resource: args.resource,
            text: name                  // Set the event text to the name entered by the user
        });
console.log("resource:", args.resource);
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

    dp.headerHeightAutoFit = true;

    dp.init();
</script>

{{-- <script type="text/javascript">
    var dp = new DayPilot.Calendar("dp");
    dp.rtl = true;

    dp.onColumnFilter = function(args) {
        if (args.column.name.toUpperCase().indexOf(args.filter.toUpperCase()) === -1) {
            args.visible = false;
        }
    };

    // view
    var today = new Date();
    var startOfWeek = new Date(today.setDate(today.getDate() - today.getDay())); // Sunday is the first day of the week

    // Format the start date in 'yyyy-MM-dd' format
    var startDate = startOfWeek.getFullYear() + '-' + (startOfWeek.getMonth() + 1).toString().padStart(2, '0') + '-' + startOfWeek.getDate().toString().padStart(2, '0');
    dp.startDate = startDate; // or just dp.startDate = "2013-03-25";

    dp.viewType = "Week";
    // dp.days = 1;
    dp.allDayEventHeight = 50;

    dp.viewType = "Resources";
    dp.headerLevels = 2;
    dp.columns.list = {!! json_encode(calenderHeader()) !!};
    dp.columnWidthSpec = "Fixed";
    dp.columnMinWidth = 20;
    dp.events.list = {!! json_encode($formattedEvents) !!};

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
        console.log(args.start);

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


    dp.headerHeightAutoFit = true;

    dp.init();
</script> --}}
