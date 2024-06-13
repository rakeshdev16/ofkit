@extends('layout.master')
@push('customLink')
    
@endpush
@section('section')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @include('components.bread-crumb', ['title' => 'Children', 'subTitle' => 'Childrens'])
                
                @livewire('children-list')
            </div>
        </div>
        @include('layout.footer')
    </div>
@endsection
@push('customScript')
    
@endpush