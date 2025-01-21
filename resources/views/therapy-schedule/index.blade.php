@extends('layout.master')
@push('customLink')
    <link href="{{ asset('assets/css/main.css')}}" type="text/css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <link href="{{ asset('assets/js/daypilot/helpers/v2/main.css') }}" type="text/css" rel="stylesheet" />
    <script src="{{ asset('assets/js/daypilot/daypilot-all.min.js')}}"></script>
@endpush
@section('section')

<div class="container-fluid" style="margin-top: 140px;">
    <div class="filters d-flex flex-wrap  gap-3">
        <select id="kindergartenFilter" onchange="filterCalendar({ 'kindergarten_id': this.value })" class="form-select rounded-pill px-5 my-2 w-auto">
            @foreach ($kindergartens as $kindergarten)
                @php
                    $value = $kindergarten->id;
                @endphp
                <option value="{{ $value }}" {{ (request('kindergarten_id') ?? '') == $value ? 'selected' : '' }}>{{ $kindergarten->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="card">
        <div class="row">
            <div class="col-md-9">
                <ul class="child-info">
                    <li><label>First Name: </label><b>{{ $therapist->first_name }}</b></li>
                    <li><label>Last Name: </label><b>{{ $therapist->family_name }}</b></li>
                    <li><label>ID: </label><b>{{ $therapist->identification }}</b></li>
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

@endsection
@push('customScript')
    <script type="text/javascript">
        
    </script>
    @include('therapy-schedule.calendar-js', ['type' => 'view']);
    <script src="{{ asset('assets/js/daypilot/helpers/v2/app.js')}}"></script>
@endpush
