@extends('layout.master')
@push('customLink')
    <link href="{{ asset('assets/css/main.css')}}" type="text/css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="{{ asset('assets/js/daypilot-all.min.js')}}"></script>
@endpush
@section('section')

<div class="container-fluid" style="margin-top: 130px;">
    <h3>Create New Schedule</h3>
{{-- @php
    echo '<pre>';
        print_r(calenderHeader("[29,34,21]"));
    echo '</pre>';
@endphp --}}
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
            schedules(events, list)
        })
        
        $(document).on('change', '#kindergartenFilter', function() {
            var ids = $(this).val();
            var url = `{{ route('test') }}?ids=${encodeURIComponent(ids)}`;
            fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
            }).then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            }).then(list => {
                var events = {!! json_encode(calenderEvents()) !!};
                schedules(events, list);
                console.log('Response from PHP:', list);
            }).catch(error => {
                console.error('Error:', error);
            });
            
        });
    </script>
    @include('components.calendar-js', ['type' => 'view']);
@endpush
