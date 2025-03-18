@extends('layout.master')
@push('customLink')
    <link href="{{ asset('assets/css/main.css')}}" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <link href="{{ asset('assets/js/daypilot/helpers/v2/main.css') }}" type="text/css" rel="stylesheet" />
    <script src="{{ asset('assets/js/daypilot/daypilot-all.min.js')}}"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script> --}}
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
@endpush
@section('section')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex flex-wrap gap-3 lg:flex-row justify-content-between mb-3 calendar-header">
            <div>
                <div class="filters d-flex justify-content-between flex-wrap  gap-3">
                    <div class="d-flex">
                        @foreach ($kindergartens as $kindergarten)
                            @php
                                $bg = json_decode($kindergarten->color[0]);
                            @endphp
                            <div class="mx-2" style="display: flex; font-size: 20px">
                                {{ $kindergarten->name }}&nbsp;
                                <div style="border-radius: 5px; height: 30px; width: 30px; {{ $bg[0] }}"></div>
                            </div>
                        @endforeach
                    </div>
                    <div class="d-flex gap-2">
                        <div class="d-flex">
                            <button class="btn btn-secondary day" id="increaseDay"><i class="fa fa-angle-right"></i></button>
                            <input type="text" class="form-control text-center" id="dayPicker" value="{{ request('day', 'All Days') }}" readonly style="width: 110px;">
                            <button class="btn btn-secondary day" id="decreaseDay"><i class="fa fa-angle-left"></i></button>
                        </div>
                        <div class="d-flex">
                            @php
                                $weekOfMonth = Carbon\Carbon::today()->weekOfMonth;
                            @endphp
                            <button class="btn btn-secondary week" id="increaseWeek"><i class="fa fa-angle-right"></i></button>
                            <input type="text" class="form-control text-center" id="weekPicker" value="{{ request('week', 'Week '.$weekOfMonth) }}" readonly style="width: 80px;">
                            <button class="btn btn-secondary week" id="decreaseWeek"><i class="fa fa-angle-left"></i></button>
                        </div>
                    </div>
                    <div>
                        <input type="month" class="form-control w-100" value="{{ date('Y-m') }}" onchange="getEvents();" id="monthPicker">
                    </div>
                </div>
            </div>
            <div class="d-flex gap-1">
                <button class="badge button btn rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" id="today">Today</button>
                <div class="dropdown">
                    <button class="btn button dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">New Documentation</button>
                    <ul class="dropdown-menu" style="">
                        <li><a class="dropdown-item newEvent" data-type="individual" href="#">Individual</a></li>
                        <li><a class="dropdown-item newEvent" data-type="group" href="#">Group</a></li>
                        <li><a class="dropdown-item newEvent" data-type="parental-guidance" href="#">Parental Guidance</a></li>
                        <li><a class="dropdown-item newEvent" data-type="staff-meeting" href="#">Staff Meeting</a></li>
                        <li><a class="dropdown-item newEvent" data-type="documentation-break" href="#">Documentation/break</a></li>
                        <li><a class="dropdown-item newEvent" data-type="preparation" href="#">Preparation</a></li>
                        <li><a class="dropdown-item newEvent" data-type="tutorial" href="#">Tutorial</a></li>
                        <li><a class="dropdown-item newEvent" data-type="other" href="#">Other</a></li>
                    </ul>
                </div>
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
        $(document).ready(function() {
            getEvents();
            let week = getQueryParam('week');
            let weekNumber = week ? parseInt(week.replace('Week ', '')) : 1;
            const minWeek = 1;
            const maxWeek = 4;
            const weekPicker = document.getElementById("weekPicker");
            $(".week").on("click", function () {
                let id = $(this).attr("id");
                if (id === "increaseWeek" && weekNumber < maxWeek) weekNumber++;
                if (id === "decreaseWeek" && weekNumber > minWeek) weekNumber--;
                weekPicker.value = "Week " + weekNumber;
                getEvents();
            });

            const dayPicker = document.getElementById("dayPicker");
            $(".day").on("click", function () {
                let days = ["All Days", "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                let selectedDay = getQueryParam('day');  
                let currentIndex = selectedDay ? days.indexOf(selectedDay) : 0;
                let id = $(this).attr("id");
                if (id === "increaseDay") currentIndex = (currentIndex + 1) % days.length;
                if (id === "decreaseDay") currentIndex = (currentIndex - 1 + days.length) % days.length;
                dayPicker.value = days[currentIndex];
                getEvents();
            });
        });

        $(document).on('click', '.newEvent', function () {
            let type = $(this).data('type');
            let data = { 'type': type, 'kindergarten_id': "{{ @$kindergartens[0]->id }}" };
            fetch("{{ route('documentation.form-data') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            }).then((response) => response.json()).then((data) => {
                $('#eventStatusForm').html(data.data);
                $('#eventStatusModal').modal('toggle');
                selectVisibility(type);
            });
        });

        $(document).on('click', '#today', function () {
            const date = new Date();
            const year = date.getFullYear();
            const month = (date.getMonth() + 1).toString().padStart(2, '0');

            const getWeekOfMonth = (date) => {
                const firstDayOfMonth = new Date(date.getFullYear(), date.getMonth(), 1);
                const firstMonday = firstDayOfMonth.getDay() === 1 ? 1 : (firstDayOfMonth.getDay() === 0 ? 2 : (8 - firstDayOfMonth.getDay())); 
                return Math.ceil((date.getDate() - firstMonday + 1) / 7);
            };

            const weekOfMonth = getWeekOfMonth(date);
            const dayName = date.toLocaleString('en-us', { weekday: 'long' });            
           
            $('#monthPicker').val(year + '-' + month);
            $('#weekPicker').val('Week ' + weekOfMonth);
            $('#dayPicker').val('All Days');
            getEvents();
        });

        function getEvents() {
            var params = {
                'month': $('#monthPicker').val(),
                "week": $('#weekPicker').val(),
                "day": $('#dayPicker').val()
            };
            filterCalendar(params);
        }
    </script>
    @include('components.calendar-js', ['type' => 'view', 'filterRoute' => route('documentation.calendar')])
    @include('schedule.script')
    <script src="{{ asset('assets/js/daypilot/helpers/v2/app.js')}}"></script>
@endpush
