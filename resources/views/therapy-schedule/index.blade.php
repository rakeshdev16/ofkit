@extends('layout.master')
@push('customLink')
    <link href="{{ asset('assets/css/main.css')}}" type="text/css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <link href="{{ asset('assets/js/daypilot/helpers/v2/main.css') }}" type="text/css" rel="stylesheet" />
    <script src="{{ asset('assets/js/daypilot/daypilot-all.min.js')}}"></script>
@endpush
@section('section')
<div class="page-wrapper">
    <div class="page-content">
        <div class="d-flex flex-wrap gap-3 lg:flex-row justify-content-between my-3">
            <div>
               <h3>Therapist Schedule</h3>
               <div class="filters d-flex flex-wrap  gap-3 my-2">
                   @include('components.select-input', [
                       'name' => '',
                       'id' => 'kindergartenFilter',
                       'icon' => 'buildings',
                       'value' => request('kindergarten_id'),
                       'onchange' => "filterCalendar({ 'kindergarten_id': this.value })",
                       'options' => $kindergartens,
                   ])
               </div>
            </div>
        </div>
        <div class="card">
            <div class="row">
                <div class="col-md-9">
                    <ul class="child-info">
                        <li><label>First Name: </label><b>{{ $therapist->first_name }}</b></li>
                        <li><label>Last Name: </label><b>{{ $therapist->family_name }}</b></li>
                        <li><label>Profession: </label><b>{{ @$therapist->profession->name }}</b></li>
                    </ul>
                    <ul class="child-info">
                        {{-- <li><label>Association: </label><b>{{ $therapist->date_of_birth }}</b></li> --}}
                    </ul>
                </div>
                <div class="col-md-3" style="text-align: left">
                    <img src="{{ $therapist->profile }}" width="120" height="140" alt="">
                </div>
            </div>
        </div>
        <div class="mb-5" id="calender-view">
            <div id="scheduleCalendar"></div>
        </div>
    </div>
</div>

@endsection
@push('customScript')
    <script type="text/javascript">
        let scrollingPosition = 0;
        $(document).ready(function () {
            window.addEventListener('scroll', function() {
                scrollingPosition = this.scrollY;
            });
            filterCalendar({ 'kindergarten_id': $('#kindergartenFilter').val() });
        });
    </script>
    @include('components.calendar-js', ['type' => 'view', 'filterRoute' => route('therapy-schedule.calendar')])
    <script src="{{ asset('assets/js/daypilot/helpers/v2/app.js')}}"></script>
@endpush
