@extends('layout.master')
@push('customLink')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-width{
            min-width: 300px !important;
        }
    </style>
@endpush
@section('section')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="mb-4 d-sm-flex page-info">
                <div class="d-sm-flex">
                    <div class="select2-width">
                        <h3 class="mb-2 text-uppercase">{{ __('children.children') }}</h3>
                        <select name="[]" class="select-filter kindergardenFilter form-select" multiple>
                            <option value="">{{ __('comon.allKindergartens') }}</option>
                            @foreach (authKindergartens() as $kindergarten)
                                <option {{ in_array($kindergarten['key'], explode(',', request()->kindergarten_id)) ? 'selected' : '' }} value="{{ $kindergarten['key'] }}">{{ $kindergarten['value'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @include('components.active-inactive')
                </div>
                <div class="mt-3 align-self-end">
                    @if (Auth::user()->hasRole('admin'))
                        @include('components.table-button')
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    <script>
        $(".kindergardenFilter").select2({
            width: '100%',
            placeholder: "{{ __('comon.allKindergartens') }}",
        });
        $(document).on('click', '.button', function() {
            $(this).attr('disabled', false);
        });

        $(document).on('click', '.moveToArchive', function() {
            var msg = "{{ __('cluster.selectMsg') }}";
            var status = $('.status').val();
            var model = "Children";
            moveToArchive( msg, status , model);
        });
    </script>
    <script src="{{ asset('assets/js/custom-datatable.js') }}"></script>
@endpush
