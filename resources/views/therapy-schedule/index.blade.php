@extends('layout.master')
@push('customLink')
    <link href="{{ asset('assets/css/main.css')}}" type="text/css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="{{ asset('assets/js/daypilot-all.min.js')}}"></script>
@endpush
@section('section')

<div class="container-fluid" style="margin-top: 130px;">
    <h3>Create New Schedule</h3>
    @include('components.schedule-header', ['kindergartens' => $kindergartens])

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
            var list = {!! json_encode(calenderHeader()) !!};
            schedules(events, list);
        })
        
        $(document).on('change', '#kindergartenFilter', function() {
            var ids = $(this).val();
            $.ajax({
                type : 'GET',
                url : "{{ route('therapy-schedule.index') }}",
                data : { user_id: ids },
                dataType: 'json',
                success : function(data){
                    schedules(data.calenderEvents, data.calenderHeader);
                }
            });
        });

    </script>
    @include('components.calendar-js', ['type' => 'view']);
@endpush
