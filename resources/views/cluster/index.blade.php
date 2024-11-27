@extends('layout.master')
@push('customLink')
@endpush
@section('section')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="mb-4 page-info">
                <div>
                    <h3 class="mb-2 text-uppercase">{{ __('cluster.cluster') }} </h3>
                    {{-- @include('components.active-inactive') --}}
                </div>
                <div class="mt-3">
                    @include('components.table-button')
                    <a href="{{ route('cluster.create') }}" class="btn button">{{ __('cluster.addBtnText') }} +</a>
                </div>
            </div>
            <div class="card small-table">
                <div class="card-body">
                    <div class="table-responsive full-width-table">
                        @include('components.table-search', ['label' => __('cluster.clusters'), 'count' => $count])
                        <div id="dataTable">
                            @include('cluster.table', ['clusters' => $clusters])
                        </div>
                    </div>
                    <div class="lising d-none">
                        @include('components.table-search', ['label' => __('cluster.clusters'), 'count' => $count])
                        <div id="accordion">
                            @include('cluster.accordion', ['clusters' => $clusters])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('customScript')
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    <script>
        $(document).on('click', '.button', function() {
            $(this).attr('disabled', false);
        });
        $(document).on('click', '.moveToArchive', function() {
            var msg = "{{ __('cluster.selectMsg') }}";
            var status = $('.status').val();
            var model = "Cluster";
            moveToArchive( msg, status , model);
        });
    </script>
    <script src="{{ asset('assets/js/custom-datatable.js') }}"></script>
@endpush
