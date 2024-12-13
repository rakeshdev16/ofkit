<script>
    function setFieldValue(fieldId, value, defaultValue = '') {
        const field = $(`#${fieldId}`);
        if (field.is('select')) {
            field.val(value || defaultValue).trigger('change');
        } else {
            field.val(value || defaultValue);
        }
    }

    function populateFormFields(data) {    
        console.log(data);
                
        const formFieldMap = {
            'eventId': data.id,
            'appointmentType': data.type,
            'day': `${data.day}`,
            'appointmentFrequency': data.frequencyRepeat,
            'therapist': data.therapistId,
            'children': data.childrenId,
            'description': data.description,
            'resource': data.resource,
            'startTime': data.start,
            'endTime': data.end,
            'eventOldFile': data.file,
        };

        // Set values for all mapped fields
        Object.keys(formFieldMap).forEach(fieldId => {
            setFieldValue(fieldId, formFieldMap[fieldId]);
        });

        if (data.type === 'group') {
            $('#appointmentGroupName').val(data.groupName);
            setFieldValue('appointmentGroupName', data.groupName);
        }

        if (data.frequencyRepeat !== null || data.frequencyRepeat !== undefined) {
            $('#Monthly, #Bi-weekly').attr('name', '').hide();
            $('#'+data.frequencyRepeat).attr('name', 'start').show();
        }

        // Handle file display
        const fileName = data.file ? data.file.split('therapy-schedule/')[1] : '';
        if (fileName) {
            $('.event-file').show().html(`<div class="document my-1">${fileName}<i class="bx bx-x" onclick="removeEventFile()"></i></div>`);
        } else {
            $('.event-file').hide();
        }
    }

    function editEvent(data) {
        populateFormFields(data);
        $('#createEventModal').modal('toggle');
    }
    
    function deleteEvent(ids) {
        if (ids == '' || ids == null) {
            toastr.error('There are not any created event');
            return true;
        }            
        Swal.fire({
            title: confirmMsgTitle,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes cancel it",
            cancelButtonText: cancelButtonText
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    type: 'POST',
                    url: "{{ route('therapy-schedule.delete') }}",
                    data: { ids: ids },
                    success: function (data) {
                        if (data.status == true) {
                            filterCalendar({'event[status]': JSON.stringify(status)});
                            toastr.success(data.message);
                        } else {
                            toastr.error(data.message);
                        }
                    }
                });
            }
        });
    }

    function filterCalendar(params = {}) {        
        var url = "{{ route('therapy-schedule.calendar') }}?"+queryParam(params);
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
                $('#therapistDropdownDiv').html(data.therapistDropdown);
                $('#childrenDropdownDiv').html(data.childrensDropdown);
                $('.selectChildrens, .selectTherapist').select2({ dropdownParent: $("#createEventModal") });
                calendar(data.calenderEvents, data.calenderHeader);
            }
        });
    }

    function calendar(events = '', list, childrens) {
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
        // dp.columnWidthSpec = "Fixed";
        dp.columnMinWidth = 20;
        dp.events.list = events;
        dp.dayBeginsHour = 8;
        dp.timeHeaderCellDuration = 15;
        dp.cellDuration = 15;
        dp.hourWidth = 100;
        dp.cellHeight = 45;
        dp.headerHeightAutoFit = true;
        dp.columns.list = list;

        dp.onTimeRangeSelected = function(args) {
            if (type == 'view') {
                dp.clearSelection();
            } else {
                
                if (args.resource == '' || args.resource == undefined || args.resource == null) {
                    toastr.error("The chosen resource dosen't have any user");
                    return true;
                }
                
                resetForm();

                var therapistId = null;
                var day = null;
                const resource = args.resource.match(/^(\d+)([a-zA-Z]+)$/);
                if (resource) {
                    args.therapistId = resource[1];
                    args.day = resource[2].charAt(0).toUpperCase() + resource[2].slice(1);
                }
                args.startTime = args.start.value.split("T")[1].slice(0, 5);
                populateFormFields(args);
                $('#eventTypeModal').modal('toggle');
            }
        };

        dp.onBeforeTimeHeaderRender = function(args) {
            var hour = DayPilot.Date.today().addTime(args.header.time);
            args.header.html = hour.toString("h:mm");
        };

        dp.onBeforeEventRender = function(args) {
            args.data.html = `<div class="p-1 event-box" style="${args.data.color[0]}; ${args.data.color[1]}">
                    <p class="text-start fw-bold text-end mb-0">
                        ${type === 'create' ? `
                            <i class="fa fa-edit" onclick='editEvent(${JSON.stringify(args.data)})'></i>&nbsp;
                            <i class="fa fa-trash" onclick='deleteEvent([${args.data.id}])'></i>&nbsp;
                        ` : ''}
                        ${args.data.therapistName} 
                        <i class="fa fa-user" aria-hidden="true"></i>
                    </p>
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

    function resetForm() {
        $('#addEventForm').trigger("reset");
        $('#addEventForm .error').html('').removeClass('error');
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

</script>