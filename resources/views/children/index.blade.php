@extends('layout.master')
@push('customLink')
    <style>

    </style>
@endpush
@section('section')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="mb-4 page-info">
                <div>
                    <h3 class="mb-0 text-uppercase">{{ __('children.children') }}</h3>
                    <select name="" class="select-filter">
                        <option value="">{{ __('comon.allKindergartens') }}</option>
                        @foreach (authKindergartens() as $kindergarten)
                            <option {{ request()->kindergarten_id == $kindergarten['key'] ? 'selected' : '' }} value="{{ $kindergarten['key'] }}">{{ $kindergarten['value'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-3">
                    @if (Auth::user()->hasRole('admin'))
                        <button class="btn button moveToArchive">{{ __('comon.moveToArchive') }}</button>
                        <a href="{{ route('children.create') }}" class="btn button">{{ __('comon.addNew') }} +</a>
                    @endif
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive full-width-table">
                        @include('components.table-search', ['label' => __('children.childrens'), 'count' => $count])
                        <div id="dataTable">
                            @include('children.table', ['childrens' => $childrens])
                        </div>
                    </div>
                    <div class="lising d-none">
                        @include('components.table-search', ['label' => __('children.childrens'), 'count' => $count])
                        <div id="accordion">
                            @include('children.accordion', ['childrens' => $childrens])
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
            var url = "{{ route('children.destroy', ':ids') }}";
            var msg = "{{ __('children.chooseAtLeastOne') }}";
            moveToArchive(url, msg);
        });
    </script>
    <script src="{{ asset('assets/js/custom-datatable.js') }}"></script>
@endpush
