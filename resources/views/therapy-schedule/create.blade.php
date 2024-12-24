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
        .select2-container .select2-selection--multiple {
            min-height: 38px !important;
        }
    </style>
@endpush
@section('section')

<div class="container-fluid" style="margin-top: 130px;">
    <h3>Create New Schedule</h3>
    <div class="d-flex flex-wrap gap-3 lg:flex-row justify-content-between my-3">
        <div class="filters d-flex flex-wrap  gap-3">
            @include('components.schedule-filter', ['kindergartens' => $kindergartens])
        </div>
        <div class="d-flex flex-wrap gap-3">
            {{-- <button class="btn badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" data-bs-toggle="modal" data-bs-target="#">Export as PDf</button> --}}
            <a href="{{ route('therapy-schedule.index') }}" class="btn badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">Save</a>
            <button class="btn badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" onclick="deleteEvent({{ $createdEventIds }})">Cancel</button>
            <button class="btn badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer updateEventStatus" data-status="published">Publish</button>
            <button class="btn badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" id="newAppointment" onclick="resetForm()">New Appointment</button>
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
        flatpickr("#startTime", { 
            enableTime: true, 
            noCalendar: true, 
            dateFormat: "H:i", // 24-hour format
            time_24hr: true, // Forces 24-hour format
            minuteIncrement: 15
        });

        flatpickr("#endTime", { 
            enableTime: true, 
            noCalendar: true, 
            dateFormat: "H:i", // 24-hour format
            time_24hr: true, // Forces 24-hour format
            minuteIncrement: 15
        });
        $(document).ready(function () {

        })

        $(document).on('click', '#appointmentType', function() {
            var type = $(this).val();
            selectVisibility(type);
        });

        $(document).on('click', '#newAppointment', function() {
            $('#day').attr('onchange', 'filterDropdown(this.value)');
            $('#eventTypeModal').modal('toggle');
        });
        
        $(document).on('click', '.eventType', function() {
            var type = $(this).data('type');
            selectVisibility(type);
            $('#unSelectedTherapistId').val('');
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
            console.log(frequency);
            if (frequency) {
                $('#Monthly, #Bi-weekly').attr('name', '').hide();
                $('#'+frequency).attr('name', 'start').show();
            }
        });

        $("#addEventForm").validate({
            rules: {
                type: { required: true },
                day: { required: true },
                time: { required: true },
                "therapist_ids[]": { required: true },
                start_time: { required: true },
                end_time: { required: true },
                // frequency_repeat: { required: true },
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
                "therapist_ids[]": { required: "Please choose therapist!" },
                start_time: { required: "Please enter start time!" },
                end_time: { required: "Please enter end time!" },
                // frequency_repeat: { required: "Please enter frequency!" },
                // group_name: { required: "Please enter group name!" },
                // children_id: { required: "Please choose children!" },
                // description: { required: "Please enter description!" },
                // image: { required: "Please choose file!" },
            },
            errorPlacement: function (error, element) {
                var name = element.attr("name");                
                if (name == 'therapist_ids[]') {
                    $('.therapists').html(error);
                } else {
                    error.insertAfter($(element));
                }
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
                            filterCalendar({ 'status': JSON.stringify(status) });
                            $('#day').attr('onchange', '');
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
            $('#associatedKindergartenId').val($('#kindergartenFilter').val());
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
                            filterCalendar({'status': JSON.stringify(status)});
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

        $(document).on('change', '#publishStartDate', function() {
            $('#publishEndDate').val('').attr('min', $(this).val());
        });
        
    </script>
    @include('components.calendar-js', ['type' => 'create']);
@endpush
