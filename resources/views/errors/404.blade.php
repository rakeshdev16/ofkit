@extends('errors.errors')
@section('section')
    @php
        $lang = \App\Models\Setting::where('key', 'lang')->pluck('value')->first();
    @endphp
    <h1>{{ $lang == 'hb' ? 'עמוד לא נמצא' : 'Page Not Found' }}</h1>
    <p class="text mt-4">{{ $lang == 'hb' ? 'אנא לחץ כאן כדי להמשיך' : 'Please click here to continue' }}</p>
    <a class="btn" style="background: #ffd681;" href="{{ url('/children') }}">{{ $lang == 'hb' ? 'לְהַמשִׁיך' : 'Continue' }}</a>
@endsection