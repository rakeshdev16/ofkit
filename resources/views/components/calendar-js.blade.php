<script>
    $(document).ready(function () {
        var kindergartenId = $('#kindergartenFilter').val();
        var published = $('#published').val();
        $('#kindergartenId').val(kindergartenId);
        $('#associatedKindergartenId').val($('#kindergartenFilter').val());
        var params = {
            'status': JSON.stringify(status),
            'kindergarten_id': kindergartenId,
            'published': published,
        };
        filterCalendar(params);
    });

    function setFieldValue(fieldId, value, defaultValue = '') {
        const field = $(`#${fieldId}`);
        if (field.is('select')) {
            field.val(value || defaultValue).trigger('change');
        } else {
            field.val(value || defaultValue);
        }
    }

    function populateFormFields(data) {
        const formFieldMap = {
            'kindergartenId': $('#kindergartenFilter').val(),
            'appointmentType': data.type,
            'day': `${data.day}`,
            'appointmentFrequency': data.frequencyRepeat,
            'therapist': data.therapistIds,
            'children': data.childrenId,
            'description': data.description,
            'resource': data.resource,
            'startTime': data.startTime,
            'endTime': data.endTime,
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

        $('#day').attr('onchange', 'filterDropdown(this.value)');
    }

    function editEvent(data) {
        filterDropdown(data.day, function () {
            $('#unSelectedTherapistId').val(data.therapistIds);
            populateFormFields(data);
            $('#createEventModal').modal('toggle');
        });
        $('#day').attr('onchange', '');
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
        var startDate = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0') + "T00:00:00";
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
                    args.therapistIds = resource[1];
                    args.day = resource[2].charAt(0).toUpperCase() + resource[2].slice(1);
                }

                args.startTime = args.start.value.split("T")[1].slice(0, 5);
                args.endTime = args.end.value.split("T")[1].slice(0, 5);

                filterDropdown(args.day, function () {
                    populateFormFields(args);
                    $('#eventTypeModal').modal('toggle');
                });
            }
        };

        dp.onBeforeTimeHeaderRender = function(args) {
            var hour = DayPilot.Date.today().addTime(args.header.time);
            args.header.html = hour.toString("HH:mm");
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
                        ${args.data.frequencyRepeat || ''} ${args.data.frequencyRepeatAt || ''}  <i class="fa fa-clock-o"></i>
                    </li>
                    <li class="d-flex gap-4 text-dark fs-6 mb-2 justify-content-end">
                        ${args.data.therapistNames.trim()} <i class="fa fa-users"></i>
                    </li>
                    <li class="d-flex gap-4 text-dark fs-6 mb-2 justify-content-end">
                        ${args.data.childrenNames.trim()} <i class="fa fa-users"></i>
                    </li>
                    <li class="d-flex gap-4 text-dark fs-6 mb-2 justify-content-end">

                    <div class="text-end">
                    <p class="mt-2">${args.data.description || ''}</p>
                    </div>
                        <i class="fa fa-user"></i>
                    </li>
                </ul>
            </div>`;
        };

        dp.init();
    }

    function filterDropdown(day, callback) {
        var kindergartenId = $('#kindergartenFilter').val();
        $.ajax({
            type: 'GET',
            url: "{{ route('therapy-schedule.filter-dropdown') }}",
            data: { kindergarten_id: kindergartenId, day: day },
            success: function (data) {
                $('#therapistDropdownDiv').html(data.therapistDropdown);
                $('#childrenDropdownDiv').html(data.childrensDropdown);
                $('.selectChildrens, .selectTherapist').select2({
                    dropdownParent: $("#createEventModal"),
                });

                if (callback) callback();
            }
        });
    }

    function resetForm() {
        $('#addEventForm').trigger("reset");
        $('#addEventForm .error').html('').removeClass('error');
    }

    $(document).on('change', '#kindergartenFilter', function() {
        let url = new URL(window.location.href);
        url.searchParams.delete('user_id');
        url.searchParams.delete('children_id');
        return history.replaceState(null, '', url.toString());
    });

    function selectVisibility(type) {
        var isMultiple = (type === 'group' || type === 'staff-meeting');
        $('.selectChildrens, .selectTherapist').select2('destroy');
        $('.selectChildrens, .selectTherapist').select2({
            dropdownParent: $("#createEventModal"),
            multiple: isMultiple
        }).on('select2:open', function() {
            // $('.select2-container').addClass('event-dropdown');
            // $('.select2-dropdown').addClass('event-dropdown-class');
            // $('.select2-results').addClass('custom-results-class');
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

</script>