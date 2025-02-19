<script>
    let scrollingPosition = 0;
    $(document).ready(function () {
        $('.page-loader').fadeOut('slow');
        window.addEventListener('scroll', function() {
            scrollingPosition = this.scrollY;
        });
    });

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
                    const selectedId = $('#therapist').val();
                    const selectedElementId = $(this).attr('id');
                    if ($('.startTime').val() == '' || $('.endTime').val() == ''  || $('#day').val() == '') {
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
            $("#children").prop("disabled", false);
            $('#createEventModalBtn').attr('disabled', false).removeAttr('title').tooltip('dispose');
            if (data.status == true) {
                if (type == 'therapist') $("#children").prop("disabled", true);
                $('#createEventModalBtn').attr('disabled', true).attr('title', 'This selected therapist or child not available at this time').tooltip({ trigger: 'hover' });
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
            $('#groupName').show();
        } else {
            $('#groupName').hide();
        }

        if (['individual', 'group', 'parental-guidance', 'staff-meeting', ''].includes(type)) {
            $('#otherFields').show();
        } else {
            $('#otherFields').hide();
        }

        $('.selectChildrens').select2({
            dropdownParent: $("#createEventModal"),
            placeholder: "Select Children",
            allowClear: true,
            maximumSelectionLength: !isMultiple ?? 1,
            language: {
                maximumSelected: function (args) {
                    return "You can only select one children";
                }
            }
        });

        $('.selectTherapist').select2({
            dropdownParent: $("#createEventModal"),
            placeholder: "Select Therapist",
            allowClear: true,
            maximumSelectionLength: !isMultiple ?? 1,
            language: {
                maximumSelected: function (args) {
                    return "You can only select one therapist";
                }
            }
        });
    }
    
    function appointmentSummary(kindergartenId) {
        const url = "{{ route('schedule.hour-summary') }}?kindergarten_id="+kindergartenId+"&status="+getQueryParam('status');
        fetch(url).then((response) => response.json()).then((data) => {
            $('#childrenSummary').html(data.childrenSummary);
            $('#staffHours').html(data.staffSummary);
            $('#scoreSummary').modal('toggle');
        });
    }

</script>