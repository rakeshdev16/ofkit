<script>
    function schedules(events = '', list) {
        var type = "{{ $type }}";
        if (window.dp) {
            window.dp.dispose();
        }

        // Create a new DayPilot instance
        window.dp = new DayPilot.Calendar("scheduleCalendar");
        dp.rtl = true;

        dp.onColumnFilter = function(args) {
            if (args.column.name.toUpperCase().indexOf(args.filter.toUpperCase()) === -1) {
                args.visible = false;
            }
        };
        var today = new Date();
        var startOfWeek = new Date(today.setDate(today.getDate() - today.getDay())); // Sunday is the first day of the week
        var startDate = startOfWeek.getFullYear() + '-' + (startOfWeek.getMonth() + 1).toString().padStart(2, '0') + '-' + startOfWeek.getDate().toString().padStart(2, '0');
        dp.startDate = startDate;
        dp.allDayEventHeight = 100;
        dp.viewType = "Resources";
        dp.headerLevels = 2;
        dp.columnWidthSpec = "Fixed";
        dp.columnMinWidth = 20;
        dp.events.list = events;
        dp.dayBeginsHour = 8;
        dp.timeHeaderCellDuration = 15;
        dp.cellDuration = 15;
        dp.hourWidth = 100;
        dp.cellHeight = 50;
        dp.headerHeightAutoFit = true;
        console.log(list);
        console.log(events);

        dp.columns.list = list;
        dp.onBeforeCellRender = function (args) {
            // Find the column for the current cell
            const resourceColumn = dp.columns.list.find(col => col.name === args.resource);

            if (!resourceColumn || !resourceColumn.children) {
                return; // Skip if no children are found for the resource
            }

            // Check each child under the current column
            resourceColumn.children.forEach(resource => {
                if (resource.workingHours && resource.id === args.resource) {
                    const startTime = DayPilot.Date.today()
                        .addHours(parseInt(resource.workingHours.start.split(":")[0]))
                        .addMinutes(parseInt(resource.workingHours.start.split(":")[1]));
                    const endTime = DayPilot.Date.today()
                        .addHours(parseInt(resource.workingHours.end.split(":")[0]))
                        .addMinutes(parseInt(resource.workingHours.end.split(":")[1]));

                    // Check if the current cell falls within the working hours
                    if (args.start >= startTime && args.start < endTime) {
                        args.cell.backColor = "#f0f0f0"; // Highlight cell in light gray
                    }
                }
            });
        };


        dp.onTimeRangeSelected = function(args) {
            if (type == 'view') {
                dp.clearSelection();
            } else {
                const therapistId = args.resource.match(/\d+/)[0];                
                $('#therapist').val(therapistId);
                $('#resource').val(args.resource);
                $('#appointmentDate').val(args.start);
                $('#startDate').val(args.start);
                $('#endDate').val(args.end);
                $('#eventTypeModal').modal('toggle');
                // console.log(args);
                // console.log(args.start);
                // console.log(args.end);
                
                // var name = prompt("New event name:", "Event");
                // if (!name) return;
    
                // var e = new DayPilot.Event({
                //     start: args.start,
                //     end: args.end,
                //     id: DayPilot.guid(),
                //     resource: args.resource,
                //     text: name
                // });
    
                // dp.events.add(e);
                // dp.clearSelection();
                // dp.message("Created: " + name);
            }
        };

        dp.onBeforeTimeHeaderRender = function(args) {
            var hour = DayPilot.Date.today().addTime(args.header.time);
            args.header.html = hour.toString("h:mm");
        };

        dp.onBeforeEventRender = function(args) {
            
            const colors = [
                "background-color: #ff0000;",
                "background-color: #00ff00;",
                "background-color: #0000ff;",
                "background-color: #ff9900;",
                "background-color: #cccccc;",
                "background-color: #095F59;",
                "background-color: #FFD681;",
            ];

            // Dynamically assign a class based on the event ID
            const colorIndex = args.data.id % colors.length;
            const assignedColor = colors[colorIndex];
            
            args.data.html = `<div class="p-3 event-box" style="${assignedColor}">
                    <p class="text-start fw-bold text-end mb-0"> ${args.data.therapistName} <i class="fa fa-user" aria-hidden="true"></i></p>
                    <div class="d-flex">
                    <span>${args.data.start.toString("HH:mm")}</span>
                </div>
            </div>`,
            args.data.bubbleHtml = `<div class="p-3 calendar-event-overlay">
                <ul>
                    <li class="d-flex gap-4 text-dark fs-6 mb-2 justify-content-end">
                        ${args.data.therapistName} <i class="fa fa-user"></i>
                    </li>
                    <li class="d-flex gap-4 text-dark fs-6 mb-2 justify-content-end">
                        ${args.data.start.toString("HH:mm")} - ${args.data.end.toString("HH:mm")} <i class="fa fa-calendar"></i>
                    </li>
                    <li class="d-flex gap-4 text-dark fs-6 mb-2 justify-content-end">
                        ${args.data.frequencyRepeat}, ${args.data.frequencyRepeatAt}  <i class="fa fa-clock-o"></i>
                    </li>
                    <li class="d-flex gap-4 text-dark fs-6 mb-2 justify-content-end">
                        ${args.data.therapistName} <i class="fa fa-briefcase"></i>
                    </li>
                    <li class="d-flex gap-4 text-dark fs-6 mb-2 justify-content-end">

                    <div class="text-end">
                    <p class="mt-2">${args.data.description}</p>
                    </div>
                        <i class="fa fa-user"></i>
                    </li>
                </ul>
            </div>`;
        };

        dp.init();
    }


</script>