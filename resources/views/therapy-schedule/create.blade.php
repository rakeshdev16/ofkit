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
        let eventData = {};
        $(document).ready(function () {
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
        });

        $(document).on('click', '#appointmentType', function() {
            var type = $(this).val();
            selectVisibility(type);
        });

        $(document).on('click', '#day', function() {
            var type = $('#appointmentType').val();
            eventData.day = $(this).val();
            eventData.type = type;
            filterFormData(type);
        });

        $(document).on('click', '#newAppointment', function() {
            Object.keys(eventData).forEach(key => delete eventData[key]);
            $('#eventTypeModal').modal('toggle');
        });
        
        $(document).on('click', '.eventType', function() {
            var type = $(this).data('type');
            eventData.type = type;
            filterFormData(function() {
                $('#eventTypeModal').modal('toggle');
                $('#createEventModal').modal('toggle');
            });
        });

        $(document).on('change', '#appointmentFrequency', function() {
            var frequency = $(this).val();
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
                var kindergartenId = getQueryParam('kindergarten_id');

                var formData = new FormData(form);
                formData.append('kindergarten_id', kindergartenId);
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
                            $('#createEventModal').modal('toggle');

                            const hiddenInput = $('#createdEventIds');
                            let currentIds = hiddenInput.val() ? JSON.parse(hiddenInput.val()) : [];
                            currentIds = [...new Set([...currentIds, data.event.unique_id])];
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
            // $('#associatedKindergartenId').val($('#kindergartenFilter').val());
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
