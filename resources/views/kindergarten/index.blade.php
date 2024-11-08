@extends('layout.master')
@push('customLink')
@endpush
@section('section')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="mb-4 page-info">
                <div>
                    <h3 class="mb-2 text-uppercase">{{ __('kindergarten.kindergarten') }} </h3>
                    @include('components.active-inactive')
                </div>
                <div class="mt-3">
                    @if (Auth::user()->hasRole('admin'))
                        @include('components.table-button')
                        <a href="{{ route('kindergarten.create') }}" class="btn button">{{ __('kindergarten.addNew') }} +</a>
                    @endif
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive full-width-table">
                        @include('components.table-search', ['label' => __('kindergarten.kindergartens'), 'count' => $count])
                        <div id="dataTable">
                            @include('kindergarten.table', ['kindergartens' => $kindergartens])
                        </div>
                    </div>
                    <div class="lising d-none">
                        @include('components.table-search', ['label' => __('kindergarten.kindergartens'), 'count' => $count])
                        <div id="accordion">
                            @include('kindergarten.accordion', ['kindergartens' => $kindergartens])
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
            var model = "Kindergarten";
            moveToArchive( msg, status , model);
        });
    </script>
    <script src="{{ asset('assets/js/custom-datatable.js') }}"></script>
@endpush
