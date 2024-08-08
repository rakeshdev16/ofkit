@extends('layout.master')
@push('customLink')
    
@endpush
@section('section')
<div class="wrapper">
    <div class="header-wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <div class="page-breadcrumb d-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">{{ __('cluster.editBtnText') }}</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('children-table.index') }}?type=diagnosis">
                                    <img class="p-1" src="{{ asset('assets/icons/fi_2887367.png') }}"/>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Diagnosis</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="">
                        <a href="{{ route('children-table.index') }}?type=diagnosis" class="btn button">{{ __('cluster.back') }}</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 mx-auto">
                    <div class="card">
                        <div class="card-body p-4">
                            <h5 class="mb-4">Update Diagnosis Detail</h5>
                            <form class="row g-3" action="{{ route('children-table.update', $diagnosis->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="col-md-12">
                                    @include('components.text-input', ['label' => 'Name', 'name' => 'name', 'icon' => 'user', 'value' => $diagnosis->name])
                                </div>
                                <div class="col-md-12">
                                    <div class="d-md-flex d-grid align-items-center gap-3">
                                        <input type="hidden" name="type" value="diagnosis">
                                        <button type="submit" class="btn button px-4">{{ __('cluster.editBtnText') }}</button>
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
