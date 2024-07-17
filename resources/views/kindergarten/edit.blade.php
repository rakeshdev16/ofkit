@extends('layout.master')
@push('customLink')
    
@endpush
@section('section')
<div class="wrapper">
    <div class="header-wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">{{ __('kindergarten.editBtnText') }}</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('kindergarten.index') }}">
                                    <img class="p-1" src="{{ asset('assets/icons/fi_2887367.png') }}"/>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('kindergarten.staff') }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="">
                        <a href="{{ route('kindergarten.index') }}" class="btn button">{{ __('kindergarten.back') }}</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 mx-auto">
                    <div class="card">
                        <div class="card-body p-4">
                            <h5 class="mb-4">{{ __('kindergarten.updateFormHeading') }}</h5>
                            <form class="row g-3" action="{{ route('kindergarten.update', $kindergarten->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="col-md-6">
                                    @include('components.text-input', ['label' => __('kindergarten.nameTh'), 'name' => 'name', 'icon' => 'user', 'value' => $kindergarten->name])
                                </div>
                                <div class="col-md-6">
                                    @include('components.text-input', ['label' => __('kindergarten.symbolTh'), 'name' => 'symbol', 'icon' => 'border-none', 'value' => $kindergarten->symbol])
                                </div>
                                <div class="col-md-6">
                                    @include('components.select-input', [
                                        'label' => __('kindergarten.frameworkTh'),
                                        'name' => 'framework_type_id',
                                        'icon' => 'code',
                                        'options' => $frameworks,
                                        'value' => @$kindergarten->framework_type_id
                                    ])
                                </div>
                                <div class="col-md-6">
                                    @include('components.select-input', [
                                        'label' => __('kindergarten.typeTh'),
                                        'name' => 'kindergarten_type_id',
                                        'icon' => 'buildings',
                                        'options' => $types,
                                        'value' => @$kindergarten->kindergarten_type_id
                                    ])
                                </div>
                                <div class="col-md-6">
                                    @include('components.select-input', [
                                        'label' => __('kindergarten.clusterTh'),
                                        'name' => 'cluster_id',
                                        'class' => 'cluster',
                                        'icon' => 'network-chart',
                                        'options' => $clusters,
                                        'value' => @$kindergarten->cluster_id
                                    ])
                                </div>
                                <div class="col-md-6">
                                    @include('components.text-input', [
                                        'label' => __('kindergarten.clusterManagerTh'),
                                        'name' => '',
                                        'class' => 'clusterManager',
                                        'icon' => 'user',
                                        'readonly' => true,
                                        'value' => @getUserNameById($kindergarten->cluster->manager_id)
                                    ])
                                </div>
                                <div class="col-md-6">
                                    @include('components.select-input', [
                                        'label' => __('kindergarten.kindergartenManagerTh'),
                                        'name' => 'manager_id', 
                                        'icon' => 'user', 
                                        'options' => $managers,
                                        'value' => @$kindergarten->kindergartenUser->user_id
                                    ])
                                </div>
                                <div class="col-md-6">
                                    @include('components.text-input', ['label' => __('kindergarten.telephoneTh'), 'name' => 'telephone', 'icon' => 'phone', 'value' => $kindergarten->telephone])
                                </div>
                                <div class="col-md-12">
                                    @include('components.text-input', ['label' => __('kindergarten.addressTh'), 'name' => 'address', 'icon' => 'current-location', 'value' => $kindergarten->address])
                                </div>
                                <div class="col-md-12">
                                    <div class="d-md-flex d-grid align-items-center gap-3">
                                        <button type="submit" class="btn button px-4">{{ __('kindergarten.updateBtnText') }}</button>
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
<script>
    $(document).on('change', '.cluster', function() {
        var cluster_id = $(this).val();
        $.ajax({
            type : 'GET',
            url : "{{ route('cluster-manager.name') }}",
            data : { cluster_id: cluster_id },
            success : function(data){
                if (data.status == true) {
                    $('.clusterManager').val(data.data.name);
                } else {
                    $('.clusterManager').val('');
                }
            }
        });
    });
</script>
@endpush
