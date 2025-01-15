@extends('layout.master')
@push('customLink')
    <link href="{{ asset('assets/css/main.css')}}" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <link href="{{ asset('assets/js/daypilot/helpers/v2/main.css?v=2025.1.6333') }}" type="text/css" rel="stylesheet" />
    <script src="{{ asset('assets/js/daypilot-all-2024.min.js')}}"></script>
        {{-- <script src="{{ asset('assets/js/daypilot/daypilot-all.min.js')}}"></script> --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container[dir="rtl"] .select2-selection--single .select2-selection__rendered {
            padding-right: 20px;
            padding-left: 20px;
        }

        .select2-container .select2-selection--multiple {
            min-height: 38px !important;
        }

        .page-loader{
            width: 100%;
            height: 100vh;
            position: absolute;
            background: #272727;
            z-index: 1000;
            .txt{
                color: #666;
                text-align: center;
                top: 40%;
                position: relative;
                text-transform: uppercase;
                letter-spacing: 0.3rem;
                font-weight: bold;
                line-height: 1.5;
            }
        }

        .spinner {
            position: relative;
            top: 35%;
        width: 80px;
        height: 80px;
        margin: 0 auto;
        background-color: #fff;

        border-radius: 100%;  
        -webkit-animation: sk-scaleout 1.0s infinite ease-in-out;
        animation: sk-scaleout 1.0s infinite ease-in-out;
        }

        @-webkit-keyframes sk-scaleout {
        0% { -webkit-transform: scale(0) }
        100% {
            -webkit-transform: scale(1.0);
            opacity: 0;
        }
        }

        @keyframes sk-scaleout {
            0% {
                -webkit-transform: scale(0);
                transform: scale(0);
            } 100% {
                -webkit-transform: scale(1.0);
                transform: scale(1.0);
                opacity: 0;
            }
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
            <button id="slideRight" type="button" class="btn button"><i class="fa fa-angle-right"></i></button>
            <button id="slideLeft" type="button" class="btn button"><i class="fa fa-angle-left"></i></button>
            {{-- <button class="btn badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" data-bs-toggle="modal" data-bs-target="#">Export as PDf</button> --}}
            <button class="btn badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" onclick="deleteEvent({{ $createdEventIds }})">Cancel</button>
            <button class="btn badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer updateEventStatus" data-status="published">Publish</button>
            <button class="btn badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" id="newAppointment">New Appointment</button>
            <span class="badge button btn rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" onclick="hourSummary($('#kindergartenFilter').val());">Hours</span>
        </div>
    </div>
    <div class="page-loader">
        <div class="spinner"></div>
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
        let timeSlotData = {};
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

        $(document).on('click', '#appointmentType', function() {
            var type = $(this).val();
            selectVisibility(type);
        });

        $(document).on('change', '#day', function() {
            var type = $('#appointmentType').val();
            var day = $(this).val();
            eventData.day = day;
            eventData.type = type;
            if (day) {
                filterFormData();
            }
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
                setTimeout(() => {
                    const dropdown = $('#therapist');
                    const id = dropdown.val()[0];
                    checkTimeSlot('therapist', id, dropdown);
                }, 1000);
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
                fetch("{{ route('therapy-schedule.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: formData
                }).then(response => response.json()).then(data => {
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
                }).catch(error => toastr.error('An error occurred while processing the request.'));
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

                fetch("{{ route('therapy-schedule.update') }}", {
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
                        $('#publishEventForm').trigger("reset");
                        $('#eventDateModal').modal('toggle');
                        filterCalendar({'status': JSON.stringify(status)});
                    } else {
                        toastr.error(data.message);
                    }
                }).catch(error => {
                    $('#publishEventFormBtn').html('Save').attr('disabled', false);
                    toastr.error('An error occurred. Please try again.');
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
    <script src="{{ asset('assets/js/daypilot/helpers/v2/app.js?v=2025.1.6333')}}"></script>
@endpush
