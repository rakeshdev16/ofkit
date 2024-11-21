@extends('layout.master')
@push('customLink')
<link href="{{ asset('assets/css/main.css')}}" type="text/css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="{{ asset('assets/js/daypilot-all.min.js')}}"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@section('section')

{{-- Main Content Section --}}
<div class="container-fluid" style="margin-top: 130px;">
    <h3>Create New Schedule</h3>
    <div class="d-flex justify-content-between my-3">
        <div class="filters">
            <!-- Filter Dropdowns -->
            <select id="staffFilter" class="btn form-select btn-outline-secondary w-auto px-5 rounded-pill ">
                <option value="">Select Kindergarten</option>
                <option value="John">John</option>
                <option value="Ortal Remano">Ortal Remano</option>
            </select>
        </div>
        <div class="d-flex gap-3">
            <span class="badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" data-bs-toggle="modal" data-bs-target="#">Export as PDf</span>
            <span class="badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">Save as draft</span>
            <span class="badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">Cancel</span>
            <span class="badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">Publish</span>
            <span class="badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" data-bs-toggle="modal" data-bs-target="#newAppointment">New Appointment</span>
        </div>
    </div>
    <div class="mb-5" id="calender-view">

        <div id="dp"></div>
    </div>
</div>

<!-- new appointment -->
{{-- <div class="modal" id="newAppointment">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">

            <!-- Modal body -->
            <div class="modal-body d-flex gap-3 flex-column">
                <button class="btn new-btn-appointment" data-bs-toggle="modal" data-bs-target="#appointmentModal">Individual</button>
                <button class="btn new-btn-appointment">Group</button>
                <button class="btn new-btn-appointment">Parental guidance</button>
                <button class="btn new-btn-appointment">Staff Meeting</button>
                <button class="btn new-btn-appointment">Documentation/break</button>
                <button class="btn new-btn-appointment">Preparation</button>
                <button class="btn new-btn-appointment">Tutorial</button>
                <button class="btn new-btn-appointment">Other</button>
            </div>
        </div>
    </div>
</div> --}}
<!-- hours summary -->
<div class="modal" id="newAppointment">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                <h4 class="modal-title">New Appointment</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form enctype="multipart/form-data">
                    <select id="appointment_type" name="type" class="btn btn-outline-secondary mb-3 text-start rounded w-100 form-select">
                        <option value="" selected disabled>Choose Appointment Type</option>
                        <option value="individual">Individual</option>
                        <option value="group">Group</option>
                        <option value="parental guidance">Parental guidance</option>
                        <option value="staff meeting">Staff Meeting</option>
                        <option value="documentation">Documentation</option>
                        <option value="preparation">Preparation</option>
                        <option value="tutorial">Tutorial</option>
                        <option value="other">Other</option>
                    </select>
                    <input type="datetime-local" id="schedule_time" name='schedule_time' class="w-100 mb-3 form-control border-1">
                    <select id="frequency_repeat" name="frequency_repeat" class="btn btn-outline-secondary mb-3 text-start rounded w-100 form-select">
                        <option value="" selected disabled>Add Frequency (Repeat)</option>
                        <option value="bi-weekly">Bi-weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                    <select id="start" name="start" class="btn btn-outline-secondary mb-3 text-start rounded w-100 form-select">
                        <option value="" selected disabled>When it will start</option>
                        <option value="one week offset" class="bi-weekly">One week offset</option>
                        <option value="from start week offset" class="bi-weekly">From start week offset</option>
                        <option value="start week" class="monthly">Start week</option>
                        <option value="after 1 week" class="monthly">After 1 week</option>
                        <option value="after 2 week" class="monthly">After 2 week</option>
                        <option value="after 3 week" class="monthly">After 3 week</option>
                    </select>
                    <input type="test" id="group_name" name='group_name' placeholder="Group Name" class="w-100 mb-3 form-control border-1">
                    <select id="therapist_id" name='therapist_id' class="btn btn-outline-secondary mb-3 text-start rounded w-100 form-select">
                        <option value="" selected disabled>Therapist name</option>
                        @foreach ($therapists as $therapist)
                            <option value="{{$therapist->id}}">{{$therapist->name}}</option>
                        @endforeach
                    </select>
                    <select id="children_ids" name='children_ids[]' class="btn btn-outline-secondary mb-3 text-start rounded w-100 form-select">
                        <option value="" selected disabled>Child</option>
                        @foreach ($childrens as $children)
                            <option value="{{$children->id}}">{{$children->name}}</option>
                        @endforeach
                    </select>
                    <textarea class="form-control mb-3 w-100" placeholder="Add Description" rows="5" id="comment" name="description"></textarea>
                    <input type="file" id="image" name="image" class="mb-3">
                    <div class="d-flex gap-3">
                        <button class="button p-2 px-4 rounded-pill border-0">Cancel</button>
                        <button type="submit" id="newAppointmentSubmit" class="button p-2 px-4 rounded-pill border-0">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- draft list -->

<div class="modal" id="draft">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                <h4 class="modal-title">Draft List</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

                <div class="table-responsive">
                    <ul class="p-0 m-0">
                        <!-- Add rows as needed -->
                        <li class="d-flex gap-3 justify-content-between align-items-center border-bottom py-2">
                            <div class="text-end">
                                <div class="d-flex gap-3">
                                    <span class="badge button rounded-pill p-2 rounded-circle fs-6 fw-normal"><i class="fa fa-trash text-danger"></i></span>
                                    <span class="badge button rounded-pill p-2 px-4 fs-6 fw-normal">Open</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <small class="text-success">Last saved</small>
                                <p class="m-0">Jun, 15 /3:35PM</p>
                            </div>
                            <div class="text-end">Draft 1</div>
                        </li>
                        <!-- Repeat rows as needed -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('customScript')
    <script type="text/javascript">

        function refreshCalender() {
            let url = "{{ route('therapy-schedule.calender-view') }}";
            $.ajax({
                type: "GET",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $("#calender-view").html(response.record);
                },
            });
        }

        refreshCalender();

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
@endpush
