@extends('layout.master')
@push('customLink')
    
@endpush
@section('section')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="mb-4 page-info">
                <div>
                    <h3 class="mb-0 text-uppercase">{{ __('cluster.cluster') }} ({{ __('comon.'.Auth::user()->getRoleNames()->first()) }})</h3>
                </div>
                <div class="mt-3">
                    <button class="btn button moveToArchive">{{ __('cluster.moveBtnText') }}</button>
                    <a href="{{ route('cluster.create') }}" class="btn button">{{ __('cluster.addBtnText') }} +</a>
                </div>
            </div>
            <div class="card small-table">
                <div class="card-body">
                    <div class="table-responsive">
                        @include('components.table-search', ['label' => 'Clusters', 'count' => $count])
                        <div id="dataTable">
                            @include('cluster.table', ['clusters' => $clusters])
                        </div>
                    </div>
                    <div class="lising d-none" id="accordion">
                        @include('cluster.accordion', ['clusters' => $clusters])
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
        var url = "{{ route('cluster.destroy', ':ids') }}";
        var msg = "{{ __('validation.chose_at_least_one', ['attribute' => 'cluster']) }}";
        moveToArchive(url, msg);
    });
</script>
<script src="{{ asset('assets/js/custom-datatable.js') }}"></script>
@endpush
