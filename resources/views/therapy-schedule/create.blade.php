@extends('layout.master')
@push('customLink')
    <link href="{{ asset('assets/css/main.css')}}" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="{{ asset('assets/js/daypilot-all.min.js')}}"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container[dir="rtl"] .select2-selection--single .select2-selection__rendered {
            padding-right: 20px;
            padding-left: 20px;
        }
    </style>
@endpush
@section('section')

<div class="container-fluid" style="margin-top: 130px;">
    <h3>Create New Schedule</h3>
    <div class="d-flex justify-content-between my-3">
        <div class="filters">
            <select id="kindergarten" onchange="filterCalendar({ 'therapist[kindergarten_id]': this.value })" class="form-select rounded-pill px-5 w-auto">
                @foreach ($kindergartens as $kindergarten)
                    <option value="{{ $kindergarten->id }}" {{ (request('therapist')['kindergarten_id'] ?? '') == $kindergarten->id ? 'selected' : '' }}>{{ $kindergarten->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="d-flex gap-3">
            {{-- <button class="btn badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" data-bs-toggle="modal" data-bs-target="#">Export as PDf</button> --}}
            <a href="{{ route('therapy-schedule.index') }}" class="btn badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">Save</a>
            <button class="btn badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" onclick="deleteEvent({{ $createdEventIds }})">Cancel</button>
            <button class="btn badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer updateEventStatus" data-status="published">Publish</button>
            <button class="btn badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" data-bs-toggle="modal" data-bs-target="#eventTypeModal" onclick="resetForm()">New Appointment</button>
        </div>
    </div>

    <div class="mb-5" id="calender-view">
        <div id="scheduleCalendar"></div>
    </div>

    <input type="hidden" id="createdEventIds" value="{{ $createdEventIds }}">
</div>

@include('components.calendar-modals')

@endsection
@push('customScript')
    <script type="text/javascript" src="{{ asset('assets/js/jquery.validate.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript">
        const status = "{{ request('edit') }}" == 'true' ? ["published", "draft"] : ["draft"];
        
        $(document).ready(function () {
            var kindergartenId = $('#kindergarten').val();
            filterCalendar({ 'event[status]': JSON.stringify(status), 'therapist[kindergarten_id]': kindergartenId });

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
            
            var isMultiple = (type === 'group' || type === 'staff-meeting');
            console.log(isMultiple);
            $('.selectChildrens, .selectTherapist').select2('destroy');
            $('.selectChildrens, .selectTherapist').select2({
                dropdownParent: $("#createEventModal"),
                multiple: isMultiple
            }).on('select2:open', function() {
                $('.select2-dropdown').addClass('event-dropdown-class');
            })

            setTimeout(function () {
                $('#appointmentGroupName').show();
                if (type !== 'group') {
                    $('#appointmentGroupName').hide();
                }
                $('#eventTypeModal').modal('toggle');
                $('#createEventModal').modal('toggle');
                $('#appointmentType').val(type);
            }, 200);
        });

        $(document).on('change', '#appointmentFrequency', function() {
            var frequency = $(this).val();
            $('#Monthly, #Bi-weekly').attr('name', '').hide();
            $('#'+frequency).attr('name', 'start').show();
        });
        
        $(document).on('change', '#kindergarten', function() {
            var frequency = $(this).val();
            $('#Monthly, #Bi-weekly').attr('name', '').hide();
            $('#'+frequency).attr('name', 'start').show();
        });

        $("#addEventForm").validate({
            rules: {
                type: { required: true },
                day: { required: true },
                time: { required: true },
                therapist_id: { required: true },
                // frequency_repeat: { required: true },
                // start: { required: true },
                // group_name: { required: true },
                // children_id: { required: true },
                // description: { required: true },
                // image: {
                //     required: function () {
                //        return $("#eventOldFile").val() == '';
                //     }
                // },
            },
            messages: {
                type: { required: "Please enter type!" },
                day: { required: "Please enter schedule day!" },
                time: { required: "Please enter schedule time!" },
                therapist_id: { required: "Please choose therapist!" },
                // frequency_repeat: { required: "Please enter frequency!" },
                // start: { required: "Please enter start at!" },
                // group_name: { required: "Please enter group name!" },
                // children_id: { required: "Please choose children!" },
                // description: { required: "Please enter description!" },
                // image: { required: "Please choose file!" },
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
                            // if (data.isCreated) {
                            //     var newEvent = new DayPilot.Event({
                            //         start: data.event.start_time,
                            //         end: data.event.end_time,
                            //         id: data.event.id,
                            //         resource: data.event.resource,
                            //         therapistName: data.event.therapistName,
                            //         frequencyRepeat: data.event.frequency_repeat,
                            //         frequencyRepeatAt: data.event.start,
                            //         description: data.event.description,
                            //     });
                            //     dp.events.add(newEvent);
                            //     dp.message("Created: " + newEvent.description);
                            //     dp.clearSelection();
                            // }
                            filterCalendar({ 'event[status]': JSON.stringify(status) });
                            $('#createEventModal').modal('toggle');

                            const hiddenInput = $('#createdEventIds');
                            let currentIds = hiddenInput.val() ? JSON.parse(hiddenInput.val()) : [];
                            currentIds = [...new Set([...currentIds, data.event.id])];
                            hiddenInput.val(JSON.stringify(currentIds));
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
            $('#eventIds').val(ids);
            $('#eventDateModal').modal('toggle');
        });

        $("#publishEventForm").validate({
            rules: {
                start_date: { required: true },
                end_date: { required: true },
            },
            messages: {
                start_date: { required: "Please chode start date!" },
                end_date: { required: "Please chode start date!" },
            },
            submitHandler: function (form, e) {  
                e.preventDefault();
                var formData = new FormData(form);
                $('#publishEventFormBtn').html('Processing');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    type: 'POST',
                    url: "{{ route('therapy-schedule.update') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function (data) {
                        $('#publishEventFormBtn').html('Save').attr('disabled', false);
                        if (data.status == true) {
                            toastr.success(data.message);
                            $('#publishEventForm').trigger("reset");
                            $('#eventDateModal').modal('toggle');
                            filterCalendar({'event[status]': JSON.stringify(status)});
                        } else {
                            toastr.error(data.message);
                        }
                    },
                    error: function (xhr) {
                        $('#publishEventFormBtn').html('Save').attr('disabled', false);
                        toastr.error('An error occurred. Please try again.');
                    }
                });
            }
        });

        $('#eventFile').change(function(event) {
            $('.event-file').show();
            const files = event.target.files;
            $('.event-file').html(`<div class="document my-1">${files[0].name}<i class="bx bx-x" onclick="removeEventFile()" data-file-name="" data-id=""></i></div>`);
        });

        function removeEventFile() {
            $('#eventFile').val('');
            $('.event-file').html('');
        }
        
    </script>
    @include('components.calendar-js', ['type' => 'create']);
@endpush
