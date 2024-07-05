@extends('layout.master')
@push('customLink')
    
@endpush
@section('section')
<div class="wrapper">
    <div class="header-wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Update</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('staff-table.index') }}">
                                    <img class="p-1" src="{{ asset('assets/icons/fi_2887367.png') }}"/>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Profession</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="">
                        <a href="{{ route('staff-table.index') }}" class="btn button">Back</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 mx-auto">
                    <div class="card">
                        <div class="card-body p-4">
                            <h5 class="mb-4">Update Profession Detail</h5>
                            <form class="row g-3" action="{{ route('staff-table.update', $profession->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="col-md-12">
                                    @include('components.text-input', [
                                        'label' => 'Profession',
                                        'name' => 'name',
                                        'icon' => 'user-circle',
                                        'value' => $profession->name,
                                    ])
                                </div>
                                <div class="col-md-6">
                                    <div class="d-md-flex d-grid align-items-center gap-3">
                                        <input type="hidden" name="type" value="profession">
                                        <button type="submit" class="btn button px-4">Add</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('customScript')
    
@endpush
