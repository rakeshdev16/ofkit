@extends('layout.master')
@push('customLink')
    <link href="{{ asset('assets/css/main.css')}}" type="text/css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <link href="{{ asset('assets/js/daypilot/helpers/v2/main.css?v=2025.1.6333') }}" type="text/css" rel="stylesheet" />
    <script src="{{ asset('assets/js/daypilot/daypilot-all.min.js')}}"></script>
@endpush
@section('section')

<div class="container-fluid" style="margin-top: 130px;">
    <h3>Weekly Therapy Schedule</h3>
    @php
        $status = @json_decode(request('event')['status'])[0] ?? 'published';
    @endphp
    <div class="d-flex flex-wrap gap-3 lg:flex-row justify-content-between my-3">
        <div class="filters d-flex flex-wrap  gap-3">
            @include('components.schedule-filter', ['kindergartens' => $kindergartens])
        </div>
        <div class="d-flex flex-wrap gap-3">
            <button id="slideRight" type="button" class="btn button"><i class="fa fa-angle-right"></i></button>
            <button id="slideLeft" type="button" class="btn button"><i class="fa fa-angle-left"></i></button>
            {{-- <span class="badge button btn rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" data-bs-toggle="modal" data-bs-target="#draft">Draft</span> --}}
            <a href="/schedule-history" class="badge button btn rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">History</a>
            <button id="editEvents" class="badge button btn rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">Edit</button>
            <a href="{{ route('therapy-schedule.create') }}" class="badge button btn rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">Create New</a>
            <span class="badge button btn rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" onclick="hourSummary($('#kindergartenFilter').val());">Hours</span>
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
        $(document).on('click', '#editEvents', function() {
            var kindergartenId = getQueryParam('kindergarten_id');
            var url = "{{ route('therapy-schedule.create') }}?edit=true&kindergarten_id="+kindergartenId;
            window.location.href = url;
        });
    </script>
    @include('components.calendar-js', ['type' => 'view']);
    <script src="{{ asset('assets/js/daypilot/helpers/v2/app.js?v=2025.1.6333')}}"></script>
@endpush
