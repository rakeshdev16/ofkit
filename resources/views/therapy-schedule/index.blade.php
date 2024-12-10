@extends('layout.master')
@push('customLink')
    <link href="{{ asset('assets/css/main.css')}}" type="text/css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="{{ asset('assets/js/daypilot-all.min.js')}}"></script>
@endpush
@section('section')

<div class="container-fluid" style="margin-top: 130px;">
    <h3>Create New Schedule</h3>
    <div class="d-flex justify-content-between my-3">
        <div class="filters d-flex flex-wrap  gap-3">
            <select class="kindergartenFilter form-select rounded-pill px-5 w-auto">
                <option value="">Select Kindergarten</option>
                @foreach ($kindergartens as $kindergarten)
                    <option value="{{ $kindergarten->staffKindergartens->pluck('user_id') }}">{{ $kindergarten->name }}</option>
                @endforeach
            </select>
            {{-- <select data-key="event[status]" class="calendarFilter form-select rounded-pill px-5 w-auto">
                <option value="">Children</option>
                <option value="Child1">Child 1</option>
                <option value="Child2">Child 2</option>
            </select>
            <select data-key="event[status]" class="calendarFilter form-select rounded-pill px-5 w-auto">
                <option value="">Staff</option>
                <option value="John">John</option>
                <option value="Ortal Remano">Ortal Remano</option>
            </select> --}}
            <select onchange="filterCalendar(queryParam({ 'event[status]': this.value }))" class="form-select rounded-pill px-5 w-auto">
                <option value="published">Published</option>
                <option value="created">Saved as Draft</option>
            </select>
        </div>
        <div class="d-flex flex-wrap gap-3">
            <span class="badge button btn rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" data-bs-toggle="modal" data-bs-target="#draft">Draft</span>
            <a href="/schedule-history" class="badge button btn rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">History</a>
            <span class="badge button btn rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">Edit</span>
            <a href="{{ route('therapy-schedule.create') }}" class="badge button btn rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">Create New</a>
            <span class="badge button btn rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" data-bs-toggle="modal" data-bs-target="#scoreSummary">Hours</span>
        </div>
    </div>

    <div class="mb-5" id="calender-view">
        <div id="scheduleCalendar"></div>
    </div>
</div>

@include('components.calendar-modals')

@endsection
@push('customScript')
    <script type="text/javascript">
        $(document).ready(function () {
            var url = queryParam({'event[status]': 'published'});
            filterCalendar(url);
        })
    </script>
    @include('components.calendar-js', ['type' => 'view']);
@endpush
