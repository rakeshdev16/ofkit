@extends('layout.master')
@push('customLink')
    <link href="{{ asset('assets/css/main.css')}}" type="text/css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="{{ asset('assets/js/daypilot-all.min.js')}}"></script>
@endpush
@section('section')

<div class="container-fluid" style="margin-top: 130px;">
    <h3>Weekly Therapy Schedule</h3>
    @php
        $status = @json_decode(request('event')['status'])[0] ?? 'published';
    @endphp
    <div class="d-flex justify-content-between my-3">
        <div class="filters d-flex flex-wrap  gap-3">
            <select id="kindergarten" onchange="filterCalendar({ 'therapist[kindergarten_id]': this.value, 'event[kindergarten_id]': this.value })" class="form-select rounded-pill px-5 w-auto">
                @foreach ($kindergartens as $kindergarten)
                    @php
                        $value = $kindergarten->id;
                    @endphp
                    <option value="{{ $value }}" {{ (request('therapist')['kindergarten_id'] ?? '') == $value ? 'selected' : '' }}>{{ $kindergarten->name }}</option>
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
            <select onchange="filterCalendar({ 'event[status]': JSON.stringify([this.value]) })" class="form-select rounded-pill px-5 w-auto">
                <option value="published" {{ ($status ?? '') == 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ ($status ?? '') == 'draft' ? 'selected' : '' }}>Saved as Draft</option>
            </select>

        </div>
        <div class="d-flex flex-wrap gap-3">
            {{-- <span class="badge button btn rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" data-bs-toggle="modal" data-bs-target="#draft">Draft</span> --}}
            <a href="/schedule-history" class="badge button btn rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">History</a>
            <a href="{{ route('therapy-schedule.create') }}?edit=true" class="badge button btn rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">Edit</a>
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
        const status = ["{{$status}}"];          
        $(document).ready(function () {
            var kindergartenId = $('#kindergarten').val();
            var params = {
                'event[status]': JSON.stringify(status),
                'event[kindergarten_id]': kindergartenId,
                'therapist[kindergarten_id]': kindergartenId
            };
            filterCalendar(params);
        })
    </script>
    @include('components.calendar-js', ['type' => 'view']);
@endpush
