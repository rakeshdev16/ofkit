@extends('layout.master')
@push('customLink')
    <link href="{{ asset('assets/css/main.css')}}" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="{{ asset('assets/js/daypilot-all.min.js')}}"></script>
@endpush
@section('section')

<div class="container-fluid" style="margin-top: 130px;">
    <h3>Create New Schedule</h3>
    <div class="d-flex justify-content-between my-3">
        <div class="filters">
            <select class="kindergartenFilter form-select rounded-pill px-5 w-auto">
                <option value="">Select Kindergarten</option>
                @foreach ($kindergartens as $kindergarten)
                    <option value="{{ $kindergarten->staffKindergartens->pluck('user_id') }}">{{ $kindergarten->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="d-flex gap-3">
            <button class="btn badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" data-bs-toggle="modal" data-bs-target="#">Export as PDf</button>
            <button class="btn badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer updateEventStatus" data-status="draft">Save as draft</button>
            <button class="btn badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">Cancel</button>
            <button class="btn badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer updateEventStatus" data-status="published">Publish</button>
            <button class="btn badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" data-bs-toggle="modal" data-bs-target="#eventTypeModal">New Appointment</button>
        </div>
    </div>

    <div class="mb-5" id="calender-view">
        <div id="scheduleCalendar"></div>
    </div>

    <input type="hidden" id="createdEventIds" value="{{ $createdEventIds }}">
</div>

@include('components.calendar-modals', ['therapists' => $therapists, 'childrens' => $childrens])

@endsection
@push('customScript')
    <script type="text/javascript" src="{{ asset('assets/js/jquery.validate.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            var url = queryParam({'event[status]': 'created'});
            filterCalendar(url);

            flatpickr("#appointmentDate", {
                enableTime: true,
                dateFormat: "l H:i",
                minDate: "today",
                time_24hr: true
            });
        })

        $(document).on('click', '.eventType', function() {
            var type = $(this).data('type');
            console.log(type);
            setTimeout(function () {
                $('#appointmentGroupName').show();
                if (type !== 'group') {
                    $('#appointmentGroupName').hide();
                }
                $('#eventTypeModal').modal('toggle');
                $('#createEventModal').modal('toggle');
                $('#appointmentType').val(type);
                // $('#appointmentType').val(type);
            }, 200);
        });

        $(document).on('change', '#appointmentFrequency', function() {
            var appointmentFrequency = $(this).val();
            switch (appointmentFrequency) {
                case 'monthly':
                    $('#weeklyFrequency').hide();
                    $('#monthlyFrequency').show();
                break;
                case 'by_weekly':
                    $('#monthlyFrequency').hide();
                    $('#weeklyFrequency').show();
                break;
                default:
                    $('#weeklyFrequency, #monthlyFrequency').hide();
                break;
            }
        });

        $("#addEventForm").validate({
            rules: {
                type: { required: true },
                schedule_time: { required: true },
                start: { required: true },
                group_name: { required: true },
                therapist_id: { required: true },
                children_id: { required: true },
                description: { required: true },
                image: { required: true },
            },
            messages: {
                type: { required: "Please enter type!" },
                schedule_time: { required: "Please enter schedule time!" },
                start: { required: "Please enter start at!" },
                group_name: { required: "Please enter group name!" },
                therapist_id: { required: "Please choose therapist!" },
                children_id: { required: "Please choose children!" },
                description: { required: "Please enter description!" },
                image: { required: "Please choose file!" },
            },
            submitHandler: function (form, e) {  
                e.preventDefault();
                var formData = new FormData(form);
                $('#createEventModalBtn').html('Processing');
                
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    type: 'POST',
                    url: "{{ route('therapy-schedule.store') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function (data) {
                        $('#createEventModalBtn').html('Save');
                        if (data.status == true) {
                            toastr.success(data.message);
                            var newEvent = new DayPilot.Event({
                                start: data.event.start_date,
                                end: data.event.end_date,
                                id: data.event.id,
                                resource: data.event.resource,
                                therapistName: data.event.therapistName,
                                frequencyRepeat: data.event.frequency_repeat,
                                frequencyRepeatAt: data.event.start,
                                description: data.event.description,
                            });
                            dp.events.add(newEvent);
                            dp.message("Created: " + newEvent.text);
                            dp.clearSelection();
                            $('#createEventModal').modal('toggle');

                            const hiddenInput = $('#createdEventIds');
                            let currentIds = hiddenInput.val() ? JSON.parse(hiddenInput.val()) : [];
                            currentIds = [...new Set([...currentIds, data.event.id])];
                            hiddenInput.val(JSON.stringify(currentIds));
                            console.log(JSON.parse($('#createdEventIds').val()));
                        } else {
                            toastr.error(data.message);
                        }
                    },
                    error: function (xhr) {
                        $('#createEventModalBtn').html('Save');
                        toastr.error('An error occurred. Please try again.');
                    }
                });
            }
        });
        
        $(document).on('click', '.updateEventStatus', function() {
            $('#createEventModalBtn').html('Processing').attr('disabled', true);
            var ids = $('#createdEventIds').val();
            if (ids == '' || ids == null) {
                toastr.error('There are not any created event');
                return true;
            }
            var status = $(this).data('status');
            var btnText = '';
            if (status == 'published') {
                btnText = 'Yes, Publish it';
            } else {
                btnText = 'Yes, Save it as draft';
            }
            Swal.fire({
                title: confirmMsgTitle,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: btnText,
                cancelButtonText: cancelButtonText
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        type: 'POST',
                        url: "{{ route('therapy-schedule.update') }}",
                        data: {
                            ids: ids,
                            status: status,
                        },
                        dataType: 'json',
                        success: function (data) {
                            $('#createEventModalBtn').html('Save').attr('disabled', false);
                            if (data.status == true) {
                                toastr.success(data.message);
                                $('#createdEventIds').val('');
                                var url = queryParam({'event[status]': 'created'});
                                filterCalendar(url);
                            } else {
                                toastr.error(data.message);
                            }
                        },
                        error: function (xhr) {
                            $('#createEventModalBtn').html('Save').attr('disabled', false);
                            toastr.error('An error occurred. Please try again.');
                        }
                    });
                }
            });
        });
    </script>
    @include('components.calendar-js', ['type' => 'create']);
@endpush
