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
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex flex-wrap gap-3 lg:flex-row justify-content-between mb-3">
            <div>
                <h3>Weekly Therapy Schedule</h3>
                <div class="filters d-flex flex-wrap  gap-3">
                     @include('components.schedule-filter', ['kindergartens' => $kindergartens])
                 </div>
            </div>
            <div class="">
                {{-- <a href="/schedule-history" class="badge button btn rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">History</a> --}}
                <button
                    class="badge button btn rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer"
                    id="deleteSchedule"
                    data-btn="edit"
                    data-schedule-id="{{ @$schedule->published_by }}"
                >
                    Edit
                </button>
                <button
                    class="badge button btn rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer"
                    id="deleteSchedule"
                    data-btn="create"
                    data-schedule-id=""
                >
                    Create New
                </button>
                <buttn
                    class="badge button btn rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer"
                    onclick="appointmentSummary($('#kindergartenFilter').val());"
                >
                    Appointment Summary
                </span>
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
    <script type="text/javascript">
        const status = "published";
        const scheduleId = "{{ @$schedule->id }}";
        $(document).ready(function() {
            var kindergartenId = $('#kindergartenFilter').val();
            var params = {
                'status': 'published',
                'kindergarten_id': kindergartenId,
                "mode": "{{ explode('.', Route::currentRouteName())[1] }}"
            };
            filterCalendar(params);
        })
        $(document).on('click', '#deleteSchedule', function() {
            const btn = $(this).data('btn');
            const scheduleId = $(this).data('schedule-id');
            let kindergartenId = getQueryParam('kindergarten_id');

            let url = "{{ route('schedule.create') }}?status=draft&kindergarten_id="+kindergartenId;
            // if (btn == 'create') url += "&edit=true";
            if (getQueryParam('status') == 'draft') {
                return window.location.href = url;
            }

            let query = btn == 'edit' ?? "edit=true";
            Swal.fire({
                title: confirmMsgTitle,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it",
                html: "It will delete the existing draft schedule",
                cancelButtonText: cancelButtonText
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("{{ route('delete.schedule') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            scheduleId: scheduleId,
                            type: btn,
                            kindergarten_id: kindergartenId,
                        })
                    }).then(response => response.json()).then(data => {
                        window.location.href = url;
                    }).catch(error => toastr.error('An error occurred while processing the request.'));
                }
            });
        });
        $(document).on('click', '#editEvents', function() {
            var kindergartenId = getQueryParam('kindergarten_id');
            var status = getQueryParam('status');
            var query = status == 'published' ? "&status=published" : "&status=draft";
            var url = "{{ route('schedule.create') }}?kindergarten_id="+kindergartenId+"&edit=true"+query;
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
