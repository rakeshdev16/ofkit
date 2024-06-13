@extends('layout.master')
@section('section')
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                @include('components.bread-crumb', ['title' => 'Children', 'subTitle' => 'Add Children'])

                @livewire('children-form')
            </div>
        </div>
        @include('layout.footer')
    </div>
@endsection
