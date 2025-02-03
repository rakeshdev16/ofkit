@extends('layout.master')
@push('customLink')
    <link href="{{ asset('assets/css/main.css')}}" type="text/css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <link href="{{ asset('assets/js/daypilot/helpers/v2/main.css') }}" type="text/css" rel="stylesheet" />
    <script src="{{ asset('assets/js/daypilot/daypilot-all.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.0.0-rc.5/dist/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
@endpush
@section('section')

<div class="container-fluid" style="margin-top: 148px; height: 500px">
    <h3>Weekly Therapy Schedule</h3>
    @php
        // $status = @json_decode(request('event')['status'])[0] ?? 'published';
        $status = request('status') ?? 'published';
    @endphp
    <div class="d-flex flex-wrap gap-3 lg:flex-row justify-content-between my-3">
        <div class="filters d-flex flex-wrap  gap-3">
            @include('components.schedule-filter', ['kindergartens' => $kindergartens])
        </div>
        <div class="d-flex flex-wrap gap-3">
            {{-- <button class="btn badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" id="export">Export</button> --}}
            <a href="/schedule-history" class="badge button btn rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">History</a>
            <button id="editEvents" class="badge button btn rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">Edit</button>
            <a href="{{ route('schedule.create') }}?status=draft" class="badge button btn rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">Create New</a>
            <span class="badge button btn rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" onclick="appointmentSummary($('#kindergartenFilter').val());">Appointment Summary</span>
        </div>
    </div>
    <div class="mb-5" id="calender-view">
        <div id="scheduleCalendar" style="height: 30% !important;"></div>
    </div>
    {{-- <div class="mb-5" id="export-calender">
        <div class="d-flex gap-2 pb-3" id="exportBtns" style="display: none !important;">
            <button class="button btn" id="exportPDF">Export as PDF</button>
            <button class="button btn" id="exportPng">Export as Png</button>
            <button class="button btn" id="exportSVG">Export as SVG</button>
        </div>
        <div id="output" style="width: 100%; height: 100%; margin-bottom: 100px"></div>
    </div> --}}
</div>
@include('components.calendar-modals')

@endsection
@push('customScript')
    <script type="text/javascript">
        const status = "published";
        const scheduleId = "{{ @$schedule->id }}";
        $(document).on('click', '#editEvents', function() {
            var kindergartenId = getQueryParam('kindergarten_id');
            var status = getQueryParam('status');
            var url = "{{ route('schedule.create') }}?schedule_id="+scheduleId+"&kindergarten_id="+kindergartenId+"&status="+status;
            window.location.href = url;
        });
        $('#exportPDF').on('click', function () {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            const canvas = document.querySelector('#output canvas');
            const imgData = canvas.toDataURL('image/png');
            doc.addImage(imgData, 'PNG', 10, 10, canvas.width / 10, canvas.height / 10);
            doc.save('schedule.pdf');
        });

        $('#exportPng').on('click', function () {
            let canvas = $('#output canvas')[0];
            if (!canvas) {
                alert('Generate the canvas first before exporting!');
                return;
            }
            const link = document.createElement('a');
            link.download = 'calendar.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        });

        $('#exportSVG').on('click', function () {
            let canvas = $('#output canvas')[0];
            if (!canvas) {
                alert('Generate the canvas first before exporting!');
                return;
            }
            const svg = `
                <svg xmlns="http://www.w3.org/2000/svg" width="${canvas.width}" height="${canvas.height}">
                    <image href="${canvas.toDataURL()}" width="100%" height="100%"/>
                </svg>
            `;
            const blob = new Blob([svg], { type: 'image/svg+xml;charset=utf-8' });
            const link = document.createElement('a');
            link.download = 'calendar.svg';
            link.href = URL.createObjectURL(blob);
            link.click();
        });

    </script>
    @include('components.calendar-js', ['type' => 'view', 'filterRoute' => route('schedule.calendar')])
    @include('schedule.script')
    <script src="{{ asset('assets/js/daypilot/helpers/v2/app.js')}}"></script>
@endpush
