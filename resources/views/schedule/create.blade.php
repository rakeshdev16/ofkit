@extends('layout.master')
@push('customLink')
    <link href="{{ asset('assets/css/main.css')}}" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <link href="{{ asset('assets/js/daypilot/helpers/v2/main.css') }}" type="text/css" rel="stylesheet" />
    <script src="{{ asset('assets/js/daypilot/daypilot-all.min.js')}}"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script> --}}
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
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex flex-wrap gap-3 lg:flex-row justify-content-between mb-3">
            <div>
                @if (request('edit') == true)
                    <h3>Editing Kindergarten Weekly Schedule</h3>
                @else
                    <h3>New Kindergarten Weekly Schedule</h3>
                @endif
                <div class="filters d-flex flex-wrap  gap-3">
                     @include('components.schedule-filter', ['kindergartens' => $kindergartens])
                </div>
            </div>
            <div class="">
                <button class="btn badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer updateEventStatus" data-status="published">Publish</button>
                <button class="btn badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" id="newAppointment">New Appointment</button>
                <span class="badge button btn rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" onclick="appointmentSummary($('#kindergartenFilter').val());">Appointment Summary</span>
                <a href="{{ route('schedule.index') }}" class="badge button btn rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">Exit</a>
            </div>
        </div>
        <div class="mb-5" id="calender-view">
            <div id="scheduleCalendar"></div>
        </div>
    </div>
</div>
@include('components.calendar-modals')
@endsection
@push('customScript')
    <script type="text/javascript" src="{{ asset('assets/js/jquery.validate.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript">
        const status = "{{ request('status') }}";
        let eventData = {};
        let timeSlotData = {};
        let container = '';

        $(document).ready(function () {
            var params = {
                'status': getQueryParam('status'),
                'kindergarten_id': getQueryParam('kindergarten_id'),
                "mode": "{{ explode('.', Route::currentRouteName())[1] }}"
            };
            filterCalendar(params);

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

        $(document).on('change', '#appointmentType', function() {
            var type = $('#appointmentType').val();
            selectVisibility(type);
            $('#therapist, #children').val(null).trigger('change');
        });

        $(document).on('change', '.event-time', function() {
            $('#therapist, #children').val(null).trigger('change');
        });

        $(document).on('change', '#day', function() {
            var day = $('#day').val();
            eventData.day = day;
            eventData.therapistIds = [];
            eventData.childrenId = [];
            if (day) {
                filterFormData();
            }
        })
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
                $('#eventTypeModal').modal('toggle');
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
                let isContinue = $('#isContinue').val();
                // console.log("isContinue", isContinue);
                // return;
                var submitForm = function() {
                    var kindergartenId = getQueryParam('kindergarten_id');
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
                            setCalendar();
                            dp.clearSelection();
                            $('#createEventModal').modal('toggle');
                        } else {
                            toastr.error(data.message);
                        }
                    }).catch(error => toastr.error('An error occurred while processing the request.'));
                };

                if (isContinue == 'true') {
                    Swal.fire({
                        title: confirmMsgTitle,
                        text: "The event time is outside the staff availability range",
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
                } else {
                    submitForm();
                }
            }
        });
        
        $(document).on('click', '.updateEventStatus', function() {
            $('#createEventModalBtn').html('Processing').attr('disabled', true);
            // var ids = $('#eventIds').val();
            // if (ids == '' || ids == null) {
            //     toastr.error('There are not any created event');
            //     return true;
            // }
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
                        $('#publishEventForm').trigger("reset");
                        $('#eventDateModal').modal('toggle');
                        filterCalendar({'status': status});
                        $('#eventIds').val('');
                    } else {
                        $('#isAgree').val(true);
                        $('#isAgreeMsg').show();
                        $('#publishEventFormBtn').removeClass('button').addClass('btn-danger').html('Continue');
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
        var endPicker = flatpickr("#publishEndDate", {
            dateFormat: "d/m/Y",
        });

        var startPicker = flatpickr("#publishStartDate", {
            dateFormat: "d/m/Y",
            onChange: function(selectedDates, dateStr, instance) {
                endPicker.set('minDate', dateStr);
            }
        });

        $(document).on('change', '#publishStartDate', function() {
            $('#publishEndDate').val('').attr('min', $(this).val());
            $('#isAgree').val('false');
            $('#isAgreeMsg').hide();
            $('#publishEventFormBtn').removeClass('btn-danger').addClass('button').html('Save');
        });
        
    </script>
    @include('components.calendar-js', ['type' => 'create', 'filterRoute' => route('schedule.calendar')])
    @include('schedule.script')
    <script src="{{ asset('assets/js/daypilot/helpers/v2/app.js')}}"></script>
@endpush
