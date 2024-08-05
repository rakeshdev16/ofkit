@extends('layout.master')
@push('customLink')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                                <a href="{!! URL::previous() !!}">
                                    <img class="p-1" src="{{ asset('assets/icons/fi_2887367.png') }}"/>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('cluster.cluster') }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="">
                        <a href="{!! URL::previous() !!}" class="btn button">{{ __('cluster.back') }}</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 mx-auto">
                    <div class="card">
                        <div class="card-body p-4">
                            <h5 class="mb-4">{{ __('cluster.formHeading') }}</h5>
                            <form class="row g-3" action="{{ route('cluster.update', $cluster->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="col-md-6">
                                    @include('components.text-input', ['label' => __('cluster.clusterTh'), 'name' => 'cluster', 'icon' => 'network-chart', 'value' => $cluster->cluster])
                                </div>
                                <div class="col-md-6">
                                    @include('components.select-input', [
                                        'label' => __('cluster.managerTh'), 
                                        'name' => 'manager_id', 
                                        'icon' => 'user', 
                                        'options' => $managers,
                                        'value' => $cluster->manager_id
                                    ])
                                </div>
                                <div class="col-md-12">
                                    @include('components.multi-select-input', [
                                        'label' => __('staff.kindergartenTh'),
                                        'name' => 'kindergarten_id[]',
                                        'class' => 'kindergarten',
                                        'icon' => 'buildings',
                                        'options' => $kindergartens,
                                        'value' =>  old('kindergarten_id') ?? @$cluster->kindergartens->pluck('kindergarten_id')->toArray(),
                                    ])
                                </div>
                                <div class="col-md-6">
                                    <div class="d-md-flex d-grid align-items-center gap-3">
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
            $(document).ready(function() {
                $('.kindergarten').select2();
            });
    </script>
@endpush
