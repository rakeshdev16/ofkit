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
    <link rel="stylesheet" href="/resources/demos/style.css">
    <style>
        .select2-container[dir="rtl"] .select2-selection--single .select2-selection__rendered {
            padding-right: 20px;
            padding-left: 20px;
        }

        .select2-container .select2-selection--multiple {
            min-height: 38px !important;
        }

        .page-loader{
            width: 100%;
            height: 100vh;
            position: absolute;
            background: #272727;
            z-index: 1000;
            .txt{
                color: #666;
                text-align: center;
                top: 40%;
                position: relative;
                text-transform: uppercase;
                letter-spacing: 0.3rem;
                font-weight: bold;
                line-height: 1.5;
            }
        }

        .spinner {
            position: relative;
            top: 35%;
        width: 80px;
        height: 80px;
        margin: 0 auto;
        background-color: #fff;

        border-radius: 100%;  
        -webkit-animation: sk-scaleout 1.0s infinite ease-in-out;
        animation: sk-scaleout 1.0s infinite ease-in-out;
        }

        @-webkit-keyframes sk-scaleout {
        0% { -webkit-transform: scale(0) }
        100% {
            -webkit-transform: scale(1.0);
            opacity: 0;
        }
        }

        @keyframes sk-scaleout {
            0% {
                -webkit-transform: scale(0);
                transform: scale(0);
            } 100% {
                -webkit-transform: scale(1.0);
                transform: scale(1.0);
                opacity: 0;
            }
        }
    </style>
@endpush
@section('section')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex flex-wrap gap-3 lg:flex-row justify-content-between mb-3">
            <div>
                @if (request('edit') == true)
                    <h3>Edit Draft Kindergarten Weekly Schedule</h3>
                @else
                    <h3>New Kindergarten Weekly Schedule</h3>
                @endif
                <div class="filters d-flex flex-wrap  gap-3">
                     @include('components.schedule-filter', ['kindergartens' => $kindergartens])
                </div>
            </div>
            <div class="">
                <button class="btn badge button rounded-pill p-2 my-1 px-4 fs-6 fw-normal cursor-pointer updateEventStatus" data-status="published">Publish</button>
                <button class="btn badge button rounded-pill p-2 my-1 px-4 fs-6 fw-normal cursor-pointer" id="newAppointment">New Appointment</button>
                <span class="badge button btn rounded-pill p-2 my-1 px-4 fs-6 fw-normal cursor-pointer" onclick="appointmentSummary($('#kindergartenFilter').val());">Appointment Summary</span>
                <a href="{{ route('schedule.index') }}" class="badge button btn rounded-pill p-2 my-1 px-4 fs-6 fw-normal cursor-pointer">Exit</a>
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
        const status = "{{ request('status') }}";
        $(document).ready(function () {
            var params = {
                'status': getQueryParam('status'),
                'kindergarten_id': getQueryParam('kindergarten_id'),
                "mode": "{{ explode('.', Route::currentRouteName())[1] }}"
            };
            filterCalendar(params);
        });
    </script>
    @include('components.calendar-js', ['type' => 'create', 'filterRoute' => route('schedule.calendar')])
    @include('schedule.script')
    <script src="{{ asset('assets/js/daypilot/helpers/v2/app.js')}}"></script>
@endpush
