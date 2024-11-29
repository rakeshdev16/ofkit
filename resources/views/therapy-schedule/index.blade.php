@extends('layout.master')
@push('customLink')
    <link href="{{ asset('assets/css/main.css')}}" type="text/css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="{{ asset('assets/js/daypilot-all.min.js')}}"></script>
@endpush
@section('section')

<div class="container-fluid" style="margin-top: 130px;">
    <h3>Create New Schedule</h3>

    @include('components.schedule-header')

    <div class="mb-5" id="calender-view">
        <div id="scheduleCalendar"></div>
    </div>
</div>

@include('components.calendar-modals')

@endsection
@push('customScript')
    <script type="text/javascript">
        $(document).ready(function () {
            var events = {!! json_encode(calenderEvents()) !!};
            schedules(events)
        })

        $('#frequency_repeat').on('change', function (){
            let value = $(this).val();
            if(value == 'bi-weekly'){
                $('.monthly').hide();
                $('.bi-weekly').show();
            }else{
                $('.bi-weekly').hide();
                $('.monthly').show();
            }
        });
        $('.bi-weekly').hide();
        $('.monthly').hide();
        $('#group_name').hide();

        $('#appointment_type').on('change', function (){
            let value = $(this).val();
            if(value == 'preparation' || value == 'documentation' || value == 'tutorial' || value == 'other'){
                $('#comment').hide();
                $('#image').hide();
                $('#children_ids').hide();
            }else{
                $('#comment').show();
                $('#image').show();
                $('#children_ids').show();
                if(value == 'group' || value == 'staff meeting'){
                    $('#group_name').show();
                }else{
                    $('#group_name').hide();
                }
            }

        });
        $('#newAppointmentSubmit').on('click', function(e) {
            e.preventDefault();

            var url = "{{route('therapy-schedule.store')}}";
            var formData = new FormData(); // Use FormData for handling file uploads

            // Append form data
            formData.append('type', $('#appointment_type').val());
            formData.append('schedule_time', $('#schedule_time').val());
            formData.append('frequency_repeat', $('#frequency_repeat').val());
            formData.append('start', $('#start').val());
            formData.append('group_name', $('#group_name').val());
            formData.append('therapist_id', $('#therapist_id').val());
            formData.append('children_ids', JSON.stringify($('#children_ids').val()));
            formData.append('description', $('#comment').val());
            formData.append('file', $('#image')[0].files[0]); // Access the file

            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                processData: false, // Required for FormData
                contentType: false, // Required for FormData
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Assuming refreshCalender() refreshes your DayPilot Calendar
                    refreshCalender();
                    $('#appointmentForm')[0].reset(); // Reset all fields in the form
                    $('#children_ids').val(null).trigger('change');
                    alert("Appointment created successfully!");
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("AJAX Error: ", textStatus, errorThrown);
                    alert("Failed to create the appointment. Please try again.");
                }
            });
        });
        
    </script>
    @include('components.calendar-js', ['type' => 'view']);
@endpush
