<script>
    let scrollingPosition = 0;
    $(document).ready(function () {
        var kindergartenId = $('#kindergartenFilter').val();
        var params = {
            'status': status,
            'kindergarten_id': kindergartenId,
        };
        $('.page-loader').fadeOut('slow');
        window.addEventListener('scroll', function() {
            scrollingPosition = this.scrollY;
        });
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
                    body: JSON.stringify({ ids: ids })
                }).then(response => response.json()).then(data => {
                    if (data.status == true) {
                        data.ids.map((id) => {
                            let existEvent = window.dp.events.find(id);
                            if (existEvent) {
                                window.dp.events.remove(existEvent);
                            }
                        });
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
        $('#appointmentFormDiv').html('');
        eventData.kindergarten_id = $('#kindergartenFilter').val();

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
        timeSlotData['day'] = $('#day').val();
        timeSlotData['status'] = getQueryParam('status');
        fetch("{{ route('schedule.time-slot') }}", {
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
    
    function appointmentSummary(kindergartenId) {
        const url = "{{ route('schedule.hour-summary') }}?kindergarten_id="+kindergartenId;
        fetch(url).then((response) => response.json()).then((data) => {
            $('#childrenSummary').html(data.childrenSummary);
            $('#staffHours').html(data.staffSummary);
            $('#scoreSummary').modal('toggle');
        });
    }

</script>