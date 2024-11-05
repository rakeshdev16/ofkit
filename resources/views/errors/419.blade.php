@extends('errors.errors')
@section('section')
    @php
        $lang = \App\Models\Setting::where('key', 'lang')->pluck('value')->first();
    @endphp
    <h1>{{ $lang == 'hb' ? 'פג תוקף העמוד' : 'Page Expired' }}</h1>
    <p class="text mt-4">{{ $lang == 'hb' ? 'אנא לחץ כאן כדי להתחבר' : 'Please click here to login' }}</p>
    <a class="btn" style="background: #ffd681;" href="{{ url()->previous() }}">{{ $lang == 'hb' ? 'לְהַמשִׁיך' : 'Continue' }}</a>
@endsection