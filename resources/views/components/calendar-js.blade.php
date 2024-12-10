<script>
    $(document).on('change', '.kindergartenFilter', function() {
        var value = $(this).val();
        var status = '';
        if ("{{ Route::currentRouteName() }}" == 'therapy-schedule.index') {
            status = 'published';
        } else {
            status = 'created';
        }
        var url = queryParam({
            'therapist[user_id]': value,
            'event[status]': status
        });
        filterCalendar(url);
    });

    $(document).on('change', '.calendarFilter', function() {
        var key = $(this).data('key');
        var value = $(this).val();
        var url = queryParam({ [key]: value });
        filterCalendar(url);
    });

    function filterCalendar(url) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            type: 'GET',
            url: url,
            processData: false,
            contentType: false,
            dataType: 'json',
            success : function(data){
                calendar(data.calenderEvents, data.calenderHeader);
            }
        });
    }

    function calendar(events = '', list) {
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
        dp.columns.list = list;

        dp.onTimeRangeSelected = function(args) {
            if (type == 'view') {
                dp.clearSelection();
            } else {
                var therapistId = null;
                var day = null;
                const resource = args.resource.match(/^(\d+)([a-zA-Z]+)$/);
                if (resource) {
                    therapistId = resource[1];
                    day = resource[2].charAt(0).toUpperCase() + resource[2].slice(1);
                }
                var time = args.start.value.split("T")[1].slice(0, 5);
                $('#therapist').val(therapistId);
                $('#resource').val(args.resource);
                $('#appointmentDate').val(day+' '+time);
                $('#appointmentDay').val(day);
                $('#startDate').val(args.start);
                $('#endDate').val(args.end);
                $('#eventTypeModal').modal('toggle');
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

    function queryParam(params = {}) {
        // Create a URL object
        var currentUrl = new URL("{{ route('therapy-schedule.calendar') }}");
        var searchParams = currentUrl.searchParams;

        // Iterate over the params object and set each key-value pair
        for (const [key, value] of Object.entries(params)) {
            if (key && value) {
                searchParams.set(key, value);
            }
        }

        // Return the updated URL as a string
        return currentUrl.toString();
    }
</script>