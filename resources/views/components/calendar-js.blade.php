<script>
    let scrollingPosition = 0;
    $(document).ready(function () {
        var kindergartenId = $('#kindergartenFilter').val();
        var params = {
            'status': JSON.stringify(status),
            'kindergarten_id': kindergartenId,
        };
        $('.page-loader').fadeOut('slow');
        window.addEventListener('scroll', function() {
            scrollingPosition = this.scrollY;
        });
        filterCalendar(params);
    });

    function editEvent(data) {
        console.log("data", data);
        Object.keys(eventData).forEach(key => delete eventData[key]);
        eventData.id = data.id;
        eventData.resource = data.resource;
        eventData.type = data.type;
        eventData.day = data.day;
        eventData.startTime = data.startTime;
        eventData.endTime = data.endTime;
        eventData.frequencyRepeat = data.frequencyRepeat;
        eventData.frequencyRepeatAt = data.frequencyRepeatAt;
        eventData.groupName = data.groupName;
        eventData.therapistIds = data.therapistIds;
        eventData.childrenId = data.childrenId;
        eventData.description = data.description;
        eventData.uniqueId = data.uniqueId;
        eventData.color = data.color;
        eventData.mode = 'edit';
        filterFormData(function() {
            $('#createEventModal').modal('toggle');
        });
    }
    
    function deleteEvent(ids) {
        if (ids == '' || ids == null) {
            toastr.error('There are not any created events');
            return true;
        }
        Swal.fire({
            title: confirmMsgTitle,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, cancel it",
            cancelButtonText: cancelButtonText
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("{{ route('therapy-schedule.delete') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ ids: ids })
                }).then(response => response.json()).then(data => {
                    if (data.status == true) {
                        filterCalendar({ 'event[status]': JSON.stringify(status) });
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                }).catch(error => toastr.error('An error occurred while processing the request.'));
            }
        });
    }

    function filterCalendar(params = {}) {
        var url = "{{ route('therapy-schedule.calendar') }}?"+queryParam(params);
        fetch(url).then((response) => response.json()).then((data) => {
            if ("{{ Route::currentRouteName() }}" == 'therapy-schedule.index') {
                $('#childrenFilter').html('<option value="">Select Children</option>')
                    .append(data.childrens.map((item) =>
                        `<option ${data.childrenId == item.key ? 'selected' : ''} value="${item.key}">${item.value}</option>`
                    ).join(''));

                $('#staffFilter').html('<option value="">Select Staff</option>')
                    .append(data.users.map((item) =>
                        `<option ${data.usersId == item.id ? 'selected' : ''} value="${item.id}">${item.name}</option>`
                    ).join(''));
            }
            calendar(data.calenderEvents, data.calenderHeader);
            $(window).scrollTop(scrollingPosition);
            // $('.page-loader').fadeOut('slow');
            setTimeout(() => {
                $('.calendar_default_scroll  > div > div:nth-of-type(2)').css("height", "1215px");
                const buttonRight = document.getElementById('slideRight');
                const buttonLeft = document.getElementById('slideLeft');
                const targetElement = $('.calendar_default_scroll > div > div:nth-of-type(2)')[0]; // Convert to DOM element
                // buttonRight.onclick = function () {
                //     targetElement.scrollLeft += 200;
                // };
                // buttonLeft.onclick = function () {
                //     targetElement.scrollLeft -= 200;
                // };

                $(window).keyup(function (e) {
                    var key = e.which;
                    if(key == 13 || key == 39) { // the enter key code or right arrow
                        console.log('right key');
                        targetElement.scrollLeft += 200;
                    } else if(key == 37) { // left arrow
                        console.log('left key');
                        targetElement.scrollLeft -= 200;
                    }
                });
            }, 1500);
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
        var startDate = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0') + "T00:00:00";
        dp.startDate = startDate;
        dp.allDayEventHeight = 100;
        dp.viewType = "Resources";
        // dp.headerHeight = 5;
        // dp.headerMinHeight = 5;
        dp.eventMoveHandling = "Disabled";
        dp.headerLevels = 2;
        dp.columnWidthSpec = "Fixed";
        dp.columnMinWidth = 20;
        dp.columnWidth = 100;
        dp.events.list = events;
        dp.dayBeginsHour = 7;
        dp.dayEndsHour = 17;
        dp.businessBeginsHour = 7; // Start at 7:00
        dp.businessEndsHour = 17; // End at 18:00
        dp.timeHeaderCellDuration = 15;
        dp.cellDuration = 15;
        dp.hourWidth = 80;
        dp.cellHeight = 30;
        dp.headerHeightAutoFit = true;
        // dp.columns.list = list;
        dp.columns.list = list.map(column => {
            return {
                name: `<span class="days-header">${column.name}</span>`,
                id: column.id,
                children: column.children.map(child => ({
                    id: child.id,
                    name: `<div class="schedule-user-name text-center wrap-text">${child.first_name ?? '-'}<br>${child.family_name ?? '-'}<br>${child.profession ?? '-'}<br>${child.association ?? '-'}</div>`
                })),
            };
        });


        dp.onBeforeRender = function () {
            this.scrollTo("07:00");
        };

        dp.onTimeRangeSelected = function(args) {
            // dp.keyboard.focusCell(args.end.addTime(-1), args.resource);
            if (type == 'view') {
                dp.clearSelection();
            } else {
                
                if (args.resource == '' || args.resource == undefined || args.resource == null) {
                    toastr.error("The chosen resource dosen't have any user");
                    return true;
                }

                const resource = args.resource.match(/^(\d+)([a-zA-Z]+)$/);
                Object.keys(eventData).forEach(key => delete eventData[key]);
                eventData.day = resource[2].charAt(0).toUpperCase() + resource[2].slice(1);
                eventData.resource = args.resource;
                eventData.startTime = args.start.value.split("T")[1].slice(0, 5);
                eventData.endTime = args.end.value.split("T")[1].slice(0, 5);
                eventData.therapistIds = [resource[1]];
                eventData.mode = 'create';
                $('#eventTypeModal').modal('toggle');
            }
        };

        dp.onBeforeTimeHeaderRender = function(args) {
            var hour = DayPilot.Date.today().addTime(args.header.time);
            args.header.html = hour.toString("HH:mm");
        };

        dp.onBeforeEventRender = function(args) {
            let title = '';
            function eventName(fullNames) {
                return fullNames.split(", ").map(fullName => {
                    const nameParts = fullName.trim().split(" ");
                    const firstName = nameParts[0];
                    const lastNameInitial = nameParts.length > 1 ? nameParts[1][0] : "";
                    return `${firstName} ${lastNameInitial}`;
                }).join(", ");
            }
            let cellTitle = args.data.type.split('-').map((item, index) => item[0].toUpperCase()+''+item.slice(1) ).join(' ');
            switch (args.data.type) {
                case 'staff-meeting':
                    title = 'Staff Meeting: ' + eventName(args.data.twoChildrenNames);
                    break;
                case 'group':
                    title = args.data.groupName + ': ' + eventName(args.data.twoChildrenNames);
                    break;
                case 'individual':
                    title = `<p style="font-size: 16px; margin-bottom: 0px;">${eventName(args.data.twoChildrenNames)}</p>`;
                    break;
                case 'parental-guidance':
                    title = `<p style="font-size: 16px; margin-bottom: 0px;">${eventName(args.data.twoChildrenNames)}</p>`;
                    break;
                default:
                    title = cellTitle;
                break;
            }
            function escapeJson(json) {
                return JSON.stringify(json).replace(/'/g, '&#39;');
            }
            args.data.html = `
            <div class="p-1 event-box d-flex flex-column justify-content-between" style="${args.data.color[0]}; ${args.data.color[1]}">
                <div class="d-flex justify-content-between">
                    <span>${args.data.start.toString("HH:mm")}</span>
                    <span><i class="fa fa-${args.data.icon}"></i></span>
                </div>
                <div class="text-center" style="font-size: 12px;">${title}</div>
                ${type === 'create' ? `
                    <div class="d-flex justify-content-start mt-auto" style="position: relative; bottom: 0;">
                        <i class="fa fa-edit" onclick='editEvent(${escapeJson(args.data)})'></i>&nbsp;
                        <i class="fa fa-trash" onclick='deleteEvent(["${args.data.uniqueId}"])'></i>&nbsp;
                    </div>
                ` : ''}
            </div>`;

            args.data.bubbleHtml = `
            <div class="p-3 calendar-event-overlay tooltip-left" style="word-wrap: break-word; white-space: normal; direction: rtl; text-align: right;">
                <ul>
                    <li class="d-flex gap-4 text-dark fs-6 mb-2 justify-content-start">
                        <i class="fa fa-info fa-lg"></i>${cellTitle}
                    </li>
                    ${['individual', 'group', 'staff-meeting', 'parental-guidance'].includes(args.data.type) ? `
                        <li class="d-flex gap-4 text-dark fs-6 mb-2 justify-content-start">
                            <i class="fa fa-${args.data.icon}"></i>${args.data.childrenNames.trim()}
                        </li>
                    ` : ''}
                    <li class="d-flex gap-4 text-dark fs-6 mb-2 justify-content-start">
                        <i class="fa fa-calendar"></i>${args.data.start.toString("HH:mm")} - ${args.data.end.toString("HH:mm")}
                    </li>
                    <li class="d-flex gap-4 text-dark fs-6 mb-2 justify-content-start">
                        <i class="fa fa-clock-o"></i>${args.data.frequencyRepeat || ''} ${args.data.frequencyRepeatAt || ''}
                    </li>
                    <li class="d-flex gap-4 text-dark fs-6 mb-2 justify-content-start">
                        <i class="fa fa-users"></i>${args.data.therapistNames.trim()}
                    </li>
                    <li class="d-flex gap-4 text-dark fs-6 mb-2 justify-content-start">
                        <i class="fa fa-align-justify"></i>
                        <p class="m-0">${args.data.description || ''}</p>
                    </li>

                </ul>
            </div>`;
        };

        dp.init();
    }

    function filterFormData(callback) {
        $('#formLoader').show();
        $('#appointmentFormDiv').html('');
        eventData.kindergarten_id = $('#kindergartenFilter').val();

        fetch("{{ route('therapy-schedule.filter-form-data') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(eventData)
        }).then((response) => response.json()).then((data) => {
            setTimeout(() => {
                $('#appointmentFormDiv').html(data);
                $('#formLoader').hide();
                $('#appointmentFormDiv').off('select2:select', '#therapist, #children');
                $('#appointmentFormDiv').on('select2:select', '#therapist, #children', function(e) {
                    const selectedOption = e.params.data;
                    const selectedId = selectedOption.id;
                    const selectedElementId = $(this).attr('id');
                    if ($('#startTime').val() == '' || $('#endTime').val() == ''  || $('#day').val() == '') {
                        $(this).val(null).trigger('change');
                        return toastr.error('Please select day, start time and end time first for checking time slot');
                    }
                    Object.keys(timeSlotData).forEach(key => delete timeSlotData[key]);
                    checkTimeSlot(selectedElementId, selectedId, $(this));
                });
            }, 500);
        });

        if (callback) callback();
    }

    function checkTimeSlot(type, id, dropdown) {
        timeSlotData['id'] = id;
        timeSlotData['type'] = type;
        timeSlotData['startTime'] = $('#startTime').val();
        timeSlotData['endTime'] = $('#endTime').val();
        timeSlotData['frequencyRepeat'] = $('#appointmentFrequency').val();
        timeSlotData['status'] = getQueryParam('status');
        fetch("{{ route('therapy-schedule.time-slot') }}", {
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Content-Type': 'application/json',
            },
            method: 'POST',
            body: JSON.stringify(timeSlotData)
        }).then(response => response.json()).then(data => {
            if (data.status == true) {
                const selectedOptions = dropdown.val();
                const updatedOptions = selectedOptions.filter(option => option !== id);
                dropdown.val(updatedOptions).trigger('change');
                toastr.error(data.message);
            }
        });
    }

    $(document).on('change', '#kindergartenFilter', function() {
        let url = new URL(window.location.href);
        url.searchParams.delete('user_id');
        url.searchParams.delete('children_id');
        return history.replaceState(null, '', url.toString());
    });

    function selectVisibility(type) {
        var isMultiple = (type === 'group' || type === 'staff-meeting');
        if (type === 'group') {
            $('#appointmentGroupName').show();
        } else {
            $('#appointmentGroupName').hide();
        }
        $('.selectChildrens, .selectTherapist').select2('destroy');
        $('.selectChildrens, .selectTherapist').select2({
            dropdownParent: $("#createEventModal"),
            multiple: isMultiple
        }).on('select2:open', function() {
            // $('.select2-container--open').css('left', '719.5px');

            // $('.select2-container').addClass('event-dropdown');
            // $('.select2-dropdown').addClass('event-dropdown-class');
            // $('.select2-results').addClass('custom-results-class');
        });
    }
    
    function hourSummary(kindergartenId) {
        const url = "{{ route('therapy-schedule.hour-summary') }}?kindergarten_id="+kindergartenId;
        fetch(url).then((response) => response.json()).then((data) => {
            console.log(data);
            $('#childrenSummary').html(data.childrenSummary);
            $('#staffHours').html(data.staffSummary);
            $('#scoreSummary').modal('toggle');
        });
    }

    function queryParam(params = {}) {
        var currentUrl = new URL(window.location.href);
        var searchParams = currentUrl.searchParams;
        for (const [key, value] of Object.entries(params)) {
            if (value === null || value === undefined || value === '') {
                searchParams.delete(key);
            } else {
                searchParams.set(key, value);
            }
        }
        var newUrl = currentUrl.origin + currentUrl.pathname + '?' + searchParams.toString();
        history.replaceState(null, '', newUrl);
        return searchParams.toString();
    }

    function getQueryParam(query) {
        var currentUrl = new URLSearchParams(window.location.search);
        return currentUrl.get(query);
    }

</script>