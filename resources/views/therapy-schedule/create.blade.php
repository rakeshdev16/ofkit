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

@include('components.calendar-modals', ['therapists' => $therapists, 'childrens' => $childrens])

@endsection
@push('customScript')
    <script type="text/javascript" src="{{ asset('assets/js/jquery.validate.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            var list = {!! json_encode(calenderHeader()) !!};
            schedules('', list);
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
                            window.location.href = "{{ route('therapy-schedule.index') }}";
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
        
    </script>
    @include('components.calendar-js', ['type' => 'create']);
@endpush
