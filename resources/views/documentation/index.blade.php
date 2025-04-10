@extends('layout.master')
@push('customLink')
    <link href="{{ asset('assets/css/main.css')}}" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <link href="{{ asset('assets/js/daypilot/helpers/v2/main.css') }}" type="text/css" rel="stylesheet" />
    <script src="{{ asset('assets/js/daypilot/daypilot-all.min.js')}}"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
@endpush
@section('section')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex flex-wrap gap-3 lg:flex-row justify-content-between mb-3 calendar-header">
            <div class="">
                <div class="filters d-flex justify-content-right flex-wrap gap-1">
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
                    @if (Auth::user()->hasRole(['admin', 'manager']))
                        <div class="d-flex gap-2">
                            <div>
                                <select name="" class="form-control" id="kindergarten">
                                    @foreach ($allKindergartens as $kindergarten)
                                        <option
                                            {{ request('kindergarten_id') == $kindergarten->id ? 'selected' : '' }}
                                            value="{{ $kindergarten->id }}"
                                        >
                                            {{ $kindergarten->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="userFilter">
                                <select name="" class="form-control" id="users">
                                    <option value="">Select User</option>
                                </select>
                            </div>
                        </div>
                    @endif
                    <div class="d-flex gap-2">
                        <div class="d-flex gap-1">
                            <button class="btn btn-secondary nextPrev" id="decrease"><i class="fa fa-angle-right"></i></button>
                            <button class="btn btn-secondary nextPrev" id="increase"><i class="fa fa-angle-left"></i></button>
                        </div>
                        <div class="d-flex">
                            @php
                                $weekOfMonth = Carbon\Carbon::today()->weekOfMonth;
                            @endphp
                            <select name="" class="form-control" id="weekDays">
                                <option value="week">Week</option>
                                <option value="days">Days</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <input
                            type="month"
                            class="form-control"
                            value="{{ date('Y-m') }}"
                            onchange="filterCalendar({ 'month': this.value, 'filter-type': 'week', 'filter-type-num': 1 });"
                            id="monthPicker"
                        >
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-1 mt-2">
                    <div class="mx-2"><span class="event-status" style="background: red"></span> Missing documentation</div>
                    <div class="mx-2"><span class="event-status" style="background: rgb(5, 5, 5)"></span> Documented and occurred</div>
                    <div class="mx-2"><span class="event-status" style="background: rgb(148, 145, 145)"></span> Documented but did not occur</div>
                </div>
            </div>
            <div class="d-flex">
                <div class="dropdown gap-1">
                    <button class="badge button btn rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" id="today">Today</button>
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
        let data = { 'kindergarten_id': "{{ @$kindergartens[0]->id }}" };
        $(document).ready(function() {
            let month = $('#monthPicker').val();
            let kindergartenId = getQueryParam('kindergarten_id') ?? $('#kindergarten').val();
            getKindergartenUsers(kindergartenId);
            const minWeek = 1;
            const maxWeek = 4;
            $("#weekDays").on("change", function () {
                let type = $(this).val();
                filterCalendar({ 'month': month, 'filter-type': type, 'filter-type-num': 1 });
            });
            $(".nextPrev").on("click", function () {
                let filterNum = parseInt(getQueryParam('filter-type-num'));
                let filterType = getQueryParam('filter-type');
                let id = $(this).attr("id");
                let weekDays = $('#weekDays').val();
                if (filterType == 'week') {
                    if (id === "decrease" && filterNum > minWeek) filterNum--;
                    if (id === "increase" && filterNum < maxWeek) filterNum++;
                } else {
                    let ym = month.split('-');
                    let lastDay = new Date(ym[0], ym[1], 0).getDate();
                    if (id === "decrease" && filterNum > 1) filterNum--;
                    if (id === "increase" && filterNum < lastDay) filterNum++;
                }
                filterCalendar({ 'month': month, 'filter-type': filterType, 'filter-type-num': filterNum });
            });
        });

        $(document).on('change', '#kindergarten', function () {
            let kindergarten_id = $(this).val();
            getKindergartenUsers(kindergarten_id);
            setTimeout(() => {
                userEvents($('#users').val());
            }, 100);
        });

        function getKindergartenUsers(id) {
            fetch("{{ route('documentation.kindergarten-users') }}?kindergarten_id="+id)
                .then((response) => response.json())
                .then((data) => {
                    $('#userFilter').html(data.data);
                });
                setTimeout(() => {
                    userEvents($('#users').val());
                }, 200);
        }

        function userEvents(id) {
            filterCalendar({
                'month': $('#monthPicker').val(),
                'filter-type': getQueryParam('filter-type') ?? 'week',
                'filter-type-num': getQueryParam('filter-type-num') ?? 1,
                'kindergarten_id': $('#kindergarten').val(),
                'user_id': id,
            });
        }

        $(document).on('click', '.newEvent', function () {
            let type = $(this).data('type');
            data.type = type;
            filterForm(data);
            setTimeout(() => {
                $('#eventStatusModal').modal('toggle');
                selectVisibility(data.type);
            }, 500);
        });

        $(document).on('change', '.day', function () {
            data.day = $(this).val();
            filterForm(data);
            setTimeout(() => {
                selectVisibility(data.type);
            }, 500);
            $('.event-time').val(null);
            $('#therapist, #children').val(null).trigger('change');
        });

        $(document).on('change', '#kindergartenFilter', function () {
            data.kindergarten_id = $(this).val();
            filterForm(data);
            setTimeout(() => {
                selectVisibility(data.type);
            }, 500);
            $('.event-time').val(null);
            $('#therapist, #children').val(null).trigger('change');
        });

        $(document).on('click', '#today', function () {
            let today = new Date();
            let year = today.getFullYear();
            let month = String(today.getMonth() + 1).padStart(2, '0');
            let firstDayOfMonth = new Date(year, today.getMonth(), 1);
            let currentWeek = Math.ceil((today.getDate() + firstDayOfMonth.getDay()) / 7);
            $('#monthPicker').val(year+'-'+month);
            $('#weekDays').val('week');
            filterCalendar({
                'month': year+'-'+month,
                'filter-type': 'week',
                'filter-type-num': currentWeek
            });
        });

        function filterForm(data, callback) {
            $('.eventStatusForm').html('<div class="card form-loader"><div class="card-body text-center"><div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status"> <span class="visually-hidden">Loading...</span></div></div></div>');
            fetch("{{ route('documentation.form-data') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            }).then((response) => response.json()).then((data) => {
                $('.eventStatusForm').html(data.data);
                setTimeout(() => {
                    $('.eventStatusForm').off('select2:select select2:unselect', '#therapist, #children');
                    $('.eventStatusForm').on('select2:select select2:unselect', '#therapist, #children', function(e) {
                        const selectedOption = e.params.data;
                        const selectedId = $(this).val();
                        const selectedElementId = $(this).attr('id');
                        if ($('.startTime').val() == '' || $('.endTime').val() == ''  || $('#day').val() == '') {
                            $(this).val(null).trigger('change');
                            return toastr.error('Please select day, start time and end time first for checking time slot');
                        }
                        Object.keys(timeSlotData).forEach(key => delete timeSlotData[key]);
                        checkTimeSlot(selectedElementId, selectedId, $(this));
                        if (selectedElementId == 'therapist' && $('#children').val() > 0) {
                            checkTimeSlot('children', $('#children').val(), $('#children'));
                        }
                    });
                }, 500);
            });

            if (callback) callback();
        }

        function childParticipated(radio) {
            const row = $(radio).closest('.row');
            const reasonSelect = row.find('.participatedReason select');
            const descriptionTextarea = row.find('.participatedDescription textarea');
            if (radio.value === '0') {
                reasonSelect.val('');
                reasonSelect.closest('.col-md-12').show();
                descriptionTextarea.closest('.col-md-12').hide();
            } else {
                descriptionTextarea.val('');
                descriptionTextarea.closest('.col-md-12').show();
                reasonSelect.closest('.col-md-12').hide();
            }
        }
    </script>
    @include('components.calendar-js', ['type' => 'view', 'filterRoute' => route('documentation.calendar')])
    @include('schedule.script')
    <script src="{{ asset('assets/js/daypilot/helpers/v2/app.js')}}"></script>
@endpush
