<script>
    const route = "{{ Route::currentRouteName() }}";
    let eventData = {};
    let timeSlotData = {};
    let isTimeOutSide = false;
    // let isTherapistAvailable = true;
    // let isChildrenAvailable = true;
    let isAvailableArray = [];
    $(document).ready(function() {
        $.validator.addMethod(
            "minChildren",
            function (value, element) {
                if ($('#appointmentType').val() === 'group') {
                    if ($(element).is('select')) {
                        return $(element).val() && $(element).val().length >= 2;
                    }
                } else {
                    return true;
                }
            },
            "Please choose at least two children!"
        );
    })

    function filterCalendar(params = {}) {
        var url = "{{ $filterRoute }}";
        let scrollingPosition = 0;
        var paramLength = Object.keys(params).length;        
        if (paramLength > 0) {
            url = url+"?"+queryParam(params);
        }
        fetch(url).then((response) => response.json()).then((data) => {
            $('#childrenFilter').html(data.childrens);
            $('#staffFilter').html(data.users);
            calendar(data.calenderEvents, data.calenderHeader, data.staffTimeSlots);
            $(window).scrollTop(scrollingPosition);
            $("#kindergartenFilter").blur();
            setTimeout(() => {
                setCalendar();
                // $('#export').on('click', function() {
                //     $(this).attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
                //     let div = $('#scheduleCalendar')[0];
                //     let targetElement = $('.calendar_default_scroll > div > div:nth-of-type(2)')[0];
                //     html2canvas(div, {
                //         useCORS: true,
                //         scrollX: -window.scrollX,
                //         scrollY: -window.scrollY,
                //         allowTaint: true,
                //         logging: true,
                //         width: targetElement.scrollWidth + 30,
                //         height: targetElement.scrollHeight + 110,
                //         windowWidth: targetElement.scrollWidth + 60,
                //         windowHeight: targetElement.scrollHeight,
                //     }).then(function(canvas) {
                //         $('#output').empty();
                //         let containerWidth = $('#output').width();
                //         let aspectRatio = canvas.width / canvas.height;
                //         let newWidth = containerWidth;
                //         let newHeight = newWidth / aspectRatio;
                //         let resizedCanvas = document.createElement('canvas');
                //         let ctx = resizedCanvas.getContext('2d');
                //         resizedCanvas.width = newWidth;
                //         resizedCanvas.height = newHeight;
                //         ctx.drawImage(canvas, 0, 0, canvas.width, canvas.height, 0, 0, newWidth, newHeight);
                //         $('#output')[0].appendChild(resizedCanvas);
                //         $('#export').attr('disabled', false).html('Export');
                //         $('#exportBtns').show();
                //         $('html, body').animate({
                //             scrollTop: $("#output").offset().top
                //         }, 500);
                //     });
                // });

            }, 1500);
        });
    }

    function calendar(events = '', list, availableTime = []) {
        let windowWidth = window.screen.width;
        let columsCount = list.map((item) => (Array.isArray(item.children) ? item.children.length : 1)).reduce((sum, count) => sum + count, 0);
        let columnWidth = 100;
        // if (columsCount == 0 || columsCount <= 7) {
        //     columnWidth = (windowWidth-100)/7;
        // } else if (columsCount*100 < windowWidth) {
        //     columnWidth = (windowWidth-100)/columsCount;
        // }
        let headerLevel = "{{ in_array(Route::currentRouteName() , ['children-schedule.index']) }}" ? "1" : "4";
        if (getQueryParam('kindergarten_id') === 'personal' || route == 'children-schedule.index'|| route == 'documentation.index') {
            headerLevel = "1";
            if (getQueryParam('day') && getQueryParam('day') !== 'All Days') {
                columnWidth = (windowWidth-140);
            } else if (screen.width < 768) {
                columnWidth = 100;
            } else {
                columnWidth = (windowWidth-140)/7;
            }
        }
        if (window.dp) {
            window.dp.dispose();
        }
        var type = "{{ $type }}";
        window.dp = new DayPilot.Calendar("scheduleCalendar", {
            rtl: true,
            startDate: DayPilot.Date.today(),
            viewType: "Resources",
            columnWidthSpec: "Fixed",
            headerLevels: headerLevel,
            headerLevelHeights: [ 40, 40, 40, 40 ],
            heightSpec: "BusinessHoursNoScroll",
            height: 500,
            columnWidth: columnWidth,
            businessBeginsHour: 7,
            businessEndsHour: 17,
            timeHeaderCellDuration: 15,
            cellDuration: 15,
            eventMoveHandling: "Disabled",
            eventResizeHandling: "Disabled",
            events: events,
            columns: list.map(column => {
                return {
                    id: column.id,
                    name: `<span class="days-header">${column.name}</span>`,
                    children: Array.isArray(column.children) ? column.children.map(child => ({
                        id: child.id,
                        name: `<span class="team-header">${child.first_name ?? '-'} ${child.family_name ?? '-'}</span>`,
                        children: {
                            id: child.id,
                            name: child.profession ?? '-',
                            children: {
                                id: child.id,
                                name: child.association ?? '-',
                            }
                        }
                    })) : undefined,
                };
            }),
            onBeforeTimeHeaderRender: function (args) {
                var hour = DayPilot.Date.today().addTime(args.header.time);
                args.header.html = hour.toString("HH:mm");
            },
            onTimeRangeSelected: async args => {
                // let isTimeOutSide = true;
                // if (Array.isArray(availableTime) && availableTime.length > 0) {
                //     let isAvailableTime = availableTime.find(e => e.resource === args.resource);
                //     if (isAvailableTime && isAvailableTime.startTime && isAvailableTime.endEnd) {
                //         let availableStart = isAvailableTime.startTime.substring(0, 5);
                //         let availableEnd = isAvailableTime.endEnd.substring(0, 5);
                //         let selectedStart = args.start.toString("HH:mm");
                //         let selectedEnd = args.end.toString("HH:mm");
                //         if (selectedStart >= availableStart && selectedEnd <= availableEnd) {
                //             isTimeOutSide = false;
                //         }
                //     }
                // }

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
                    eventData.startTime = args.start.value.split("T")[1].slice(0, 8);
                    eventData.endTime = args.end.value.split("T")[1].slice(0, 8);
                    eventData.therapistIds = [resource[1]];
                    $('#eventTypeModal').modal('toggle');
                    dp.clearSelection();
                }
            },
            onEventClicked: (args) => {
                let event = args.e.data;
                if (route === 'documentation.index') {
                    $('#eventStatusForm').html(event.form);
                    $('#eventStatusModal').modal('toggle');
                    let data = event.data;
                    selectVisibility(data.type, data.childrenId, data.therapistIds);
                } else {
                    const handleAction = (type, data) => {
                        DayPilot.Modal.close();
                        if (type == 'edit') editEvent(data);
                        if (type == 'delete') deleteEvent(data);
                    };
                    window.handleAction = handleAction;
                    DayPilot.Modal.alert(event.eventDetailSlotHtml);
                }
            },
            onBeforeEventRender: function(args) {
                let customClass = '';
                let startTime = args.data.start;
                let resource = args.data.resource;
                let startTimeFormatted = new Date(startTime).toISOString().substring(11, 16);
                let count = events.filter(event => {
                    let eventStartFormatted = new Date(event.start).toISOString().substring(11, 16);
                    return eventStartFormatted === startTimeFormatted && event.resource === resource;
                }).length;

                if (count == 5) customClass = 'five-event';
                if (count == 4) customClass = 'four-event';
                if (count == 3) customClass = 'three-event';
                if (count == 2) customClass = 'two-event';
                if (count == 1) customClass = 'single-event';

                args.data.cssClass = customClass;
                args.data.html = `${args.data.eventSlotHtml}`;
            },
            onBeforeCellRender: function(args) {
                if (availableTime.length > 0) {
                    let events = availableTime.filter(e => e.resource === args.cell.resource);
                    if (events.length > 0) {
                        let cellTime = args.cell.start.value.split('T')[1].substring(0, 8);
                        let isAvailable = events.some(event => event.startTime <= cellTime && cellTime < event.endEnd);
                        if (isAvailable) {
                            args.cell.business = false;
                            args.cell.cssClass = "available-cell";
                        } else {
                            args.cell.business = true;
                        }
                    }
                }
            },
            headerHeightAutoFit: true,
            showCurrentTime: false
        });
        dp.init();
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

    function setCalendar() {
        let screenWidth = window.innerWidth;
        const cssByWidth = {
            1920: { 'documentation.index': 70, 'schedule.index': 52, 'schedule.create': 52, 'children-schedule.index': 52 },
            2133: { 'documentation.index': 73, 'schedule.index': 57, 'schedule.create': 57, 'children-schedule.index': 57 },
            2400: { 'documentation.index': 76, 'schedule.index': 62, 'schedule.create': 62, 'children-schedule.index': 62 },
            2560: { 'documentation.index': 77, 'schedule.index': 64, 'schedule.create': 64, 'children-schedule.index': 64 }
        };
        let height = cssByWidth[1920][route];
        if (cssByWidth[screenWidth][route]) height = cssByWidth[screenWidth][route];
        $('.calendar_default_scroll > div > div:nth-of-type(2)').css('height', height+'vh');
        $('.calendar_default_scroll > div > div:nth-of-type(1)').css('height', height+'vh');
        $('.calendar_default_scroll').css('height', height+'vh');
        const targetElement = $('.calendar_default_scroll > div > div:nth-of-type(2)')[0];
        $(window).keyup(function (e) {
            var key = e.which;
            if (key == 13 || key == 39) targetElement.scrollLeft += 200;
            if (key == 37) targetElement.scrollLeft -= 200;
        });
    }

    function editEvent(data) {
        Object.keys(eventData).forEach(key => delete eventData[key]);
        eventData.id = data.id;
        eventData.resource = data.resource;
        eventData.type = data.type;
        eventData.day = data.day;
        eventData.startTime = data.start_time;
        eventData.endTime = data.end_time;
        eventData.frequencyRepeat = data.frequency_repeat;
        eventData.frequencyRepeatAt = data.frequency_repeat_at;
        eventData.groupName = data.group_name;
        eventData.therapistIds = data.therapistIds;
        eventData.childrenId = data.childrenId;
        eventData.description = data.description;
        eventData.file = data.file;
        eventData.uniqueId = data.unique_id;
        eventData.color = data.color;
        eventData.mode = 'edit';
        filterFormData(function() {
            $('#createEventModal').modal('toggle');
        });
    }

    function deleteEvent(data) {
        if (data == '' || data == null) {
            toastr.error('There are not any created events');
            return true;
        }
        Swal.fire({
            title: confirmMsgTitle,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it",
            cancelButtonText: cancelButtonText
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("{{ route('schedule.delete') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ data })
                }).then(response => response.json()).then(data => {
                    if (data.status == true) {
                        data.ids.map((id) => {
                            let existEvent = window.dp.events.find(id);
                            if (existEvent) {
                                window.dp.events.remove(existEvent);
                            }
                        });
                        setCalendar();
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                }).catch(error => toastr.error('An error occurred while processing the request.'));
            }
        });
    }

    function filterFormData(callback) {
        $('#formLoader').show();
        eventData.kindergarten_id = $('#kindergartenFilter').val();
        $('#appointmentFormDiv').html('');
        fetch("{{ route('schedule.filter-form-data') }}", {
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
                $('#appointmentFormDiv').off('select2:select select2:unselect', '#therapist, #children');
                $('#appointmentFormDiv').on('select2:select select2:unselect', '#therapist, #children', function(e) {
                    const selectedOption = e.params.data;
                    // const selectedId = selectedOption.id;
                    const selectedId = $(this).val();
                    const selectedElementId = $(this).attr('id');
                    if ($('.startTime').val() == '' || $('.endTime').val() == ''  || $('#day').val() == '') {
                        $(this).val(null).trigger('change');
                        return toastr.error('Please select day, start time and end time first for checking time slot');
                    }
                    Object.keys(timeSlotData).forEach(key => delete timeSlotData[key]);
                    checkTimeSlot(selectedElementId, selectedId, $(this));
                    if (selectedElementId == 'therapist' && $('#children').val() > 0) {
                        checkTimeSlot('children', $('#children').val(), $('#children'));
                        // $('#children').val(null).trigger('change');
                    }
                });
            }, 500);
        });

        if (callback) callback();
    }

    function checkTimeSlot(type, id, dropdown) {
        Object.keys(timeSlotData).forEach(key => delete timeSlotData[key]);
        let frequencyRepeat = $('#appointmentFrequency').val();
        let frequencyRepeatAt = frequencyRepeat == 'Bi-weekly' ? $('#Bi-weekly').val() : $('#Monthly').val();
        timeSlotData.id = id;
        timeSlotData.type = type;
        timeSlotData.startTime = $('.startTime').val().slice(0, 5);
        timeSlotData.endTime = $('.endTime').val().slice(0, 5);
        timeSlotData.frequencyRepeat = frequencyRepeat;
        timeSlotData.frequencyRepeatAt = frequencyRepeatAt;
        timeSlotData.day = $('#day').val();
        timeSlotData.uniqueId = $('#uniqueId').val();
        timeSlotData.status = getQueryParam('status');
        timeSlotData.scheduleId = getQueryParam('schedule_id') ?? '';
        timeSlotData.kindergartenId = $('#kindergartenFilter').val();
        fetch("{{ route('schedule.time-slot') }}", {
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Content-Type': 'application/json',
            },
            method: 'POST',
            body: JSON.stringify(timeSlotData)
        }).then(response => response.json()).then(data => {
            if (type == 'therapist') {
                isTimeOutSide = data.isTimeOutSide;
            }
            // isTherapistAvailable = true;
            // isChildrenAvailable = true;
            if (data.status == true) {
                if (data.type == 'therapist' && !isAvailableArray.includes('therapist')) isAvailableArray.push('therapist');
                if (data.type == 'children' && !isAvailableArray.includes('children')) isAvailableArray.push('children');
            } else {
                isAvailableArray = isAvailableArray.filter(item => item !== data.type);
            }
            submitIfAvailable(data);
        });
    }

    // function selectVisibility(type) {
    //     var isMultiple = (type === 'group' || type === 'staff-meeting');
    //     if (type === 'group') {
    //         $('#groupName').show();
    //     } else {
    //         $('#groupName').hide();
    //     }

    //     if (['individual', 'group', 'parental-guidance', 'staff-meeting', ''].includes(type)) {
    //         $('#otherFields').show();
    //     } else {
    //         $('#otherFields').hide();
    //     }

    //     $('.selectChildrens').select2({
    //         dropdownParent: $("#createEventModal"),
    //         placeholder: "Select Children",
    //         allowClear: true,
    //         maximumSelectionLength: !isMultiple ?? 1,
    //         language: {
    //             maximumSelected: function (args) {
    //                 return "You can only select one children";
    //             }
    //         }
    //     });

    //     $('.selectTherapist').select2({
    //         dropdownParent: $("#createEventModal"),
    //         placeholder: "Select Therapist",
    //         allowClear: true,
    //         maximumSelectionLength: !isMultiple ?? 1,
    //         language: {
    //             maximumSelected: function (args) {
    //                 return "You can only select one therapist";
    //             }
    //         }
    //     });
    // }

    function selectVisibility(type, selectedChildrens = [], selectedTherapist = []) {
        var isMultiple = (type === 'group' || type === 'staff-meeting');
        if (type === 'group') {
            $('#groupName').show();
        } else {
            $('#groupName').hide();
        }

        if (['individual', 'group', 'parental-guidance', 'staff-meeting', ''].includes(type)) {
            $('#otherFields').show();
        } else {
            $('#otherFields').hide();
        }

        $(".selectChildrens").select2({
            dropdownParent: $("#createEventModal, #eventStatusModal"),
            placeholder: "Select Children",
            allowClear: true,
            maximumSelectionLength: !isMultiple ?? 1,
            language: {
                maximumSelected: function () {
                    return "You can only select one child";
                }
            }
        });

        $(".selectChildrens").on("select2:select", function (e) {
            let data = e.params.data;
            let attendenceForm = `@include('components.children-participated', ['index' => '${data.id}', 'name' => '${data.text}', 'child_id' => '${data.id}'])`;
            $('.children-attendance').append(attendenceForm);
        });

        $(".selectChildrens").on("select2:unselecting", function (e) {
            let id = e.params.args.data.id;
            $('.fileSec'+id).remove();
            if (selectedChildrens.includes(Number(id))) e.preventDefault();
        });

        $(".selectTherapist").select2({
            dropdownParent: $("#createEventModal, #eventStatusModal"),
            placeholder: "Select Therapist",
            allowClear: true,
            maximumSelectionLength: !isMultiple ?? 1,
            language: {
                maximumSelected: function () {
                    return "You can only select one therapist";
                }
            }
        });

        $(".selectTherapist").on("select2:select", function (e) {
            let data = e.params.data;
            $('.therapist-attendance').removeClass('d-none').addClass('d-flex');
            $('.therapist-attendance').append(`<div class="therapist-${data.id} mx-1">
                <label for="therapist-${data.id}">${data.text}</label>
                <input type="checkbox" name="" id="therapist-${data.id}">
            </div>`);
            let length = $('.therapist-attendance > div').length;
            console.log("length", length);
        });

        $(".selectTherapist").on("select2:unselecting", function (e) {
            let id = e.params.args.data.id;
            $('.therapist-'+id).remove();
            let length = $('.therapist-attendance > div').length;
            if (length === 0) $('.therapist-attendance').removeClass('d-flex').addClass('d-none');
            if (selectedTherapist.includes(Number(id))) e.preventDefault();
        });

        setTimeout(() => {
            $(".locked-option").parent().find(".select2-selection__choice__remove").remove();
        }, 100);
    }

    function submitIfAvailable(data) {
        // $("#children").prop("disabled", false);
        $('#createEventModalBtn').attr('disabled', false).removeAttr('data-bs-original-title').tooltip('dispose');
        // if (!isTherapistAvailable || !isChildrenAvailable) {
        if (isAvailableArray.length > 0) {
            // if (data.type == 'therapist') $("#children").prop("disabled", true);
            $('#createEventModalBtn').attr('disabled', true).attr('title', 'This selected therapist or child not available at this time').tooltip({ trigger: 'hover' });
            if (data.status == true) toastr.error(data.message);
        }
    }

    $(document).on('click', '#cancelEventModalBtn', function() {
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
                $('#createEventModal').modal('toggle');
            }
        });
    });

    $(document).on('change', '#appointmentType', function() {
        var type = $('#appointmentType').val();
        // $('#therapist, #children').val(null).trigger('change');
        eventData.type = type;
        filterFormData();
        // selectVisibility(type);
    });

    $(document).on('change', '.event-time', function() {
        let therapist = $('#therapist');
        let children = $('#children');
        if (therapist.val() && $(this).attr('name') == 'end_time') {
            therapist.val().length > 0 ? checkTimeSlot(therapist.attr('id'), therapist.val(), therapist) : '';
            children.val().length > 0 ? checkTimeSlot(children.attr('id'), children.val(), children) : '';
            // children.val(null).trigger('change');
        }
        // $('#therapist, #children').val(null).trigger('change');
    });

    $(document).on('change', '#day', function() {
        var day = $('#day').val();
        eventData.day = day;
        eventData.therapistIds = [];
        eventData.childrenId = [];
        if (day) {
            filterFormData();
        }
    });
    $(document).on('change', '#appointmentFrequency, #Bi-weekly, #Monthly', function() {
        var type = $('#appointmentType').val();
        var appointmentFrequency = $('#appointmentFrequency').val();
        eventData.frequencyRepeat = appointmentFrequency;
        eventData.type = type;
        eventData.startTime = $('#startTime').val();
        eventData.endTime = $('#endTime').val();
        if (appointmentFrequency) {
            $('#Monthly, #Bi-weekly').attr('name', '').hide();
            $('#'+appointmentFrequency).attr('name', 'frequency_repeat_at').show();
        }
        let therapist = $('#therapist');
        let children = $('#children');
        therapist.val().length > 0 ? checkTimeSlot(therapist.attr('id'), therapist.val(), therapist) : '';
        children.val().length > 0 ? checkTimeSlot(children.attr('id'), children.val(), children) : '';
    });

    $(document).on('click', '#newAppointment', function() {
        Object.keys(eventData).forEach(key => delete eventData[key]);
        $('#eventTypeModal').modal('toggle');
    });

    $(document).on('click', '.eventType', function() {
        var type = $(this).data('type');
        eventData.type = type;
        filterFormData(function() {
            if (route == 'schedule.create') $('#eventTypeModal').modal('toggle');
            $('#createEventModal').modal('toggle');
            setTimeout(() => {
                const dropdown = $('#therapist');
                const id = dropdown.val();
                checkTimeSlot('therapist', id, dropdown);
            }, 1000);
        });
    });

    $("#addEventForm").validate({
        rules: {
            type: { required: true },
            day: { required: true },
            time: { required: true },
            "therapist_ids[]": { required: true },
            "children_ids[]": {
                required: false,
                minChildren: true
            },
            start_time: { required: true },
            end_time: { required: true },
        },
        messages: {
            type: { required: "Please enter type!" },
            day: { required: "Please enter schedule day!" },
            time: { required: "Please enter schedule time!" },
            "therapist_ids[]": { required: "Please choose therapist!" },
            "children_ids[]": {
                required: "Please choose at least one child!",
                minChildren: "Please choose at least two children!"
            },
            start_time: { required: "Please enter start time!" },
            end_time: { required: "Please enter end time!" },
        },
        errorPlacement: function (error, element) {
            var name = element.attr("name");
            if (name == 'therapist_ids[]') {
                $('.therapists').html(error);
            } else if (name == 'children_ids[]') {
                $('.childrens').html(error);
            } else {
                error.insertAfter($(element));
            }
        },
        submitHandler: function (form, e) {
            e.preventDefault();
            var submitForm = function() {
                var kindergartenId = route == 'documentation.index' ? 9 : getQueryParam('kindergarten_id');
                var formData = new FormData(form);
                formData.append('kindergarten_id', kindergartenId);
                formData.append('schedule_id', getQueryParam('schedule_id'));
                formData.append('edit', getQueryParam('edit'));
                formData.append('mode', 'create');
                $('#createEventModalBtn').html('Processing');
                fetch("{{ route('schedule.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: formData
                }).then(response => response.json()).then(data => {
                    $('#createEventModalBtn').html('Save');
                    if (data.status == true) {
                        toastr.success(data.message);
                        const hiddenInput = $('#eventIds');
                        if (data.deletedIds) {
                            data.deletedIds.map((id, index) => {
                                let existEvent = window.dp.events.find(id);
                                if (existEvent) {
                                    window.dp.events.remove(existEvent);
                                }
                            });
                        }
                        data.event.map((item, index) => {
                            let existEvent = window.dp.events.find(item.id);
                            if (existEvent) {
                                window.dp.events.remove(existEvent);
                            }
                            window.dp.events.add(item);
                            let currentIds = hiddenInput.val() ? JSON.parse(hiddenInput.val()) : [];
                            currentIds = [...new Set([...currentIds, item.uniqueId])];
                            hiddenInput.val(JSON.stringify(currentIds));
                        });
                        // setCalendar();
                        // window.dp.clearSelection();
                        $('#createEventModal').modal('toggle');
                    } else {
                        toastr.error(data.message);
                    }
                }).catch(error => toastr.error('An error occurred while processing the request.'));
            };

            let confirmMsg = isTimeOutSide == true ?
                                "This appointment is outside the therapist's availability hours. Are you sure you want to add this appointment?" :
                                "Are you sure you want to add this appointment?";

            Swal.fire({
                title: confirmMsgTitle,
                text: confirmMsg,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, continue it",
                cancelButtonText: cancelButtonText
            }).then((result) => {
                if (result.isConfirmed) {
                    submitForm();
                }
            });
        }
    });

    $(document).on('click', '.updateEventStatus', function() {
        $('#createEventModalBtn').html('Processing').attr('disabled', true);
        $('#publishEventForm').find('.is-invalid').removeClass('is-invalid');
        $('#publishEventForm').find('input').removeClass('error');
        $('#publishEventForm').find('label.error').remove();
        $('#publishEventForm').trigger("reset");
        $('#publishEventFormBtn').addClass('button').removeClass('btn-danger').html('Submit');
        $('#eventDateModal').modal('toggle');
    });

    $("#publishEventForm").validate({
        rules: {
            start_date: { required: true },
            end_date: { required: true },
        },
        messages: {
            start_date: { required: "Please select start date!" },
            end_date: { required: "Please select start date!" },
        },
        submitHandler: function (form, e) {
            e.preventDefault();
            var isAgree = $('#isAgree').val();
            var submitForm = function() {
                var formData = new FormData(form);
                formData.append('kindergarten_id', getQueryParam('kindergarten_id'));
                formData.append('status', getQueryParam('status'));
                $('#publishEventFormBtn').html('Processing');
                fetch("{{ route('schedule.update') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: formData
                }).then(response => response.json()).then(data => {
                    $('#createEventModalBtn').html('Save');
                    $('#publishEventFormBtn').html('Save').attr('disabled', false);
                    if (data.status == true) {
                        toastr.success(data.message);
                        window.location.href = "{{ route('schedule.index') }}";
                    } else {
                        $('#isAgree').val(true);
                        $('#scheduleIds').val(JSON.stringify(data.ids));
                        Swal.fire({
                            title: confirmMsgTitle,
                            text: "If you continue it will replace the existing published event",
                            icon: "question",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Yes, continue it",
                            cancelButtonText: cancelButtonText
                        }).then((result) => {
                            if (result.isConfirmed) {
                                submitForm();
                            }
                        });
                    }
                }).catch(error => {
                    $('#publishEventFormBtn').html('Save').attr('disabled', false);
                    toastr.error('An error occurred. Please try again.');
                });
            }

            Swal.fire({
                title: confirmMsgTitle,
                text: "Are you sure you want to publish this event?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, continue it",
                cancelButtonText: cancelButtonText
            }).then((result) => {
                if (result.isConfirmed) {
                    submitForm();
                }
            });
        }
    });

    function selectedEventFile(params) {
            $('.event-file').show();
        const files = event.target.files;
        $('.event-file').html(`<div class="document my-1">${files[0].name}<i class="bx bx-x" onclick="removeEventFile()" data-file-name="" data-id=""></i></div>`);
    }

    function removeEventFile() {
        $('#eventFile').val('');
        $('.event-file').html('');
    }

    $("#publishStartDate").datepicker({
        dateFormat: "dd/mm/yy",
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        minDate: 0,
        onSelect: function (selectedDate) {
            let startDate = $(this).datepicker("getDate");
            if (startDate) {
                $("#publishEndDate").datepicker("option", "minDate", startDate);
                $("#isAgree").val("false");
                $("#scheduleIds").val("");
            }
        }
    });

    $("#publishEndDate").datepicker({
        dateFormat: "dd/mm/yy",
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        minDate: 0,
        onSelect: function (selectedDate) {
            let endDate = $(this).datepicker("getDate");
            if (endDate) {
                $("#isAgree").val("false");
                $("#scheduleIds").val("");
            }
        }
    });

    $("#publishStartDate, #publishEndDate").attr("autocomplete", "off").attr("readonly", true);
</script>