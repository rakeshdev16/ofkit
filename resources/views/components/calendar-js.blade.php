<script>
    $(document).ready(function () {
        var kindergartenId = $('#kindergartenFilter').val();
        // $('#kindergartenId').val(kindergartenId);
        // $('#associatedKindergartenId').val($('#kindergartenFilter').val());
        var params = {
            'status': JSON.stringify(status),
            'kindergarten_id': kindergartenId,
        };
        filterCalendar(params);
    });

    function editEvent(data) {
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
        filterFormData(function() {
            $('#createEventModal').modal('toggle');
        });
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
        dp.eventMoveHandling = "Disabled";
        dp.headerLevels = 2;
        dp.columnWidthSpec = "Fixed";
        dp.columnMinWidth = 20;
        dp.columnWidth = 200;
        dp.events.list = events;
        dp.dayBeginsHour = 7;
        dp.dayEndsHour = 17;
        dp.businessBeginsHour = 7; // Start at 7:00
        dp.businessEndsHour = 18; // End at 18:00
        dp.timeHeaderCellDuration = 15;
        dp.cellDuration = 15;
        dp.hourWidth = 80;
        dp.cellHeight = 30;
        dp.headerHeightAutoFit = true;
        dp.columns.list = list;

        dp.onBeforeRender = function () {
            this.scrollTo("07:00");
        };

        dp.onTimeRangeSelected = function(args) {
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
                $('#eventTypeModal').modal('toggle');
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
                            <i class="fa fa-trash" onclick='deleteEvent(["${args.data.uniqueId}"])'></i>&nbsp;
                        ` : ''}
                        ${args.data.twoChildrenNames}
                        <img src="${args.data.icon}" width="18">
                    </p>
                    <div class="d-flex">
                    <span>${args.data.start.toString("HH:mm")}</span>
                </div>
            </div>`,
            args.data.bubbleHtml = `<div class="p-3 calendar-event-overlay">
                <ul>
                    <li class="d-flex gap-4 text-dark fs-6 mb-2 justify-content-end">
                        ${args.data.type === 'group' ? `${args.data.groupName} <i class="fa fa-users"></i>` : ''}
                    </li>
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

    function filterFormData(callback) {
        $('#formLoader').show();
        $('#appointmentFormDiv').html('');
        eventData.kindergarten_id = $('#kindergartenFilter').val();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            type: 'GET',
            url: "{{ route('therapy-schedule.filter-form-data') }}",
            data: { eventData },
            success : function(data){
                setTimeout(() => {
                    $('#appointmentFormDiv').html(data);
                    $('#formLoader').hide();
                }, 500);
                if (callback) callback();
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