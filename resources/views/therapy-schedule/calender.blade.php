<div class="mb-5">
    <div id="dp"></div>
</div>
<script type="text/javascript">
    var dp = new DayPilot.Calendar("dp");
    dp.rtl = true;

    dp.onColumnFilter = function(args) {
        if (args.column.name.toUpperCase().indexOf(args.filter.toUpperCase()) === -1) {
            args.visible = false;
        }
    };
    var today = new Date();
    var startOfWeek = new Date(today.setDate(today.getDate() - today.getDay())); // Sunday is the first day of the week

    var startDate = startOfWeek.getFullYear() + '-' + (startOfWeek.getMonth() + 1).toString().padStart(2, '0') + '-' + startOfWeek.getDate().toString().padStart(2, '0');
    dp.startDate = startDate; // or just dp.startDate = "2013-03-25";

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
            resource: args.resource, // Ensure args.resource correctly maps to the selected column
            text: name // Use the inputted event name
        });

        // Add the event to the calendar
        dp.events.add(e);
        dp.clearSelection();
        dp.message("Created: " + name);
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
