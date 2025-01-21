@extends('layout.master')
@push('customLink')
    <link href="{{ asset('assets/css/main.css')}}" type="text/css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <link href="{{ asset('assets/js/daypilot/helpers/v2/main.css') }}" type="text/css" rel="stylesheet" />
    <script src="{{ asset('assets/js/daypilot/daypilot-all.min.js')}}"></script>
@endpush
@section('section')

<div class="container-fluid" style="margin-top: 140px;">
    <h3>Child Schedule</h3>
    @php
        $status = @json_decode(request('event')['status'])[0] ?? 'published';
    @endphp
    <div class="card">
        <div class="row">
            <div class="col-md-9">
                <ul class="child-info">
                    <li><label>First Name: </label><b>{{ $children->name }}</b></li>
                    <li><label>Last Name: </label><b>{{ $children->family_name }}</b></li>
                    <li><label>ID: </label><b>{{ $children->identification }}</b></li>
                    <li><label>Kindergarten: </label><b>{{ $children->kindergarten->name }}</b></li>
                </ul>
                <ul class="child-info">
                    <li><label>Child's Brithday: </label><b>{{ $children->date_of_birth }}</b></li>
                    <li><label>Child's Age: </label><b>{{ $children->calclulated_age }}</b></li>
                </ul>
            </div>
            <div class="col-md-3" style="text-align: left">
                <img src="{{ $children->profile }}" width="120" height="140" alt="">
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
    @include('children.schedule.calendar-js', ['type' => 'view']);
    <script src="{{ asset('assets/js/daypilot/helpers/v2/app.js')}}"></script>
@endpush
