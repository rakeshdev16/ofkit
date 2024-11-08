@extends('layout.master')
@push('customLink')
@endpush
@section('section')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="card-body">
                <ul class="nav nav-tabs nav-primary mb-0 tables" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ request()->type == 'kindergarten-type' ? 'active' : '' }}" data-type="kindergarten-type" data-bs-toggle="tab" href="#kindergarten-type" role="tab" aria-selected="false" tabindex="-1">
                            <div class="d-flex align-items-center">
                                <div class="tab-icon"><i class="bx bx-building-house font-18 me-1"></i>
                                </div>
                                <div class="tab-title"> {{ __('tables.kindergartenType') }} </div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ request()->type == 'framework-type' ? 'active' : '' }}" data-type="framework-type" data-bs-toggle="tab" href="#framework-type" role="tab" aria-selected="false" tabindex="-1">
                            <div class="d-flex align-items-center">
                                <div class="tab-icon"><i class="bx bx-code-block font-18 me-1"></i>
                                </div>
                                <div class="tab-title"> {{ __('tables.frameworkType') }}</div>
                            </div>
                        </a>
                    </li>
                </ul>
                <div class="tab-content pt-3">
                    <div class="tab-pane fade {{ request()->type == 'kindergarten-type' ? 'active show' : '' }}" id="kindergarten-type" role="tabpanel">
                        @if (request()->type == 'kindergarten-type')
                            @include('table.framework.kindergarten-type.index', ['kindergartenTypes' => $kindergartenTypes, 'count' => $kindergartenTypeCount])
                        @endif
                    </div>
                    <div class="tab-pane fade {{ request()->type == 'framework-type' ? 'active show' : '' }}" id="framework-type" role="tabpanel">
                        @if (request()->type == 'framework-type')
                            @include('table.framework.framework-type.index', ['frameworkTypes' => $frameworkTypes, 'count' => $frameworkTypeCount])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('customScript')
    <script src="{{ asset('assets/js/custom-datatable.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    <script>
        $(document).on('click', '.nav-link', function() {
            var uri = window.location.toString();

            if (uri.indexOf("?") > 0) {
                var clean_uri = uri.substring(0, uri.indexOf("?"));
                window.history.replaceState({}, document.title, clean_uri);
            }
            var type = $(this).data('type');
            queryParam('type', type);
            $('#kindergarten-type').html('');
            $('#framework-type').html('');
            $.ajax({
                type: 'GET',
                url: "{{ route('framework-table.tab') }}",
                data: {
                    type: type
                },
                success: function(data) {
                    if (data.status == true) {
                        $('#' + type).html(data.data);
                    }
                }
            });
        });

        $(document).on('click', '.moveToArchive', function() {
            var msg = "{{ __('cluster.selectMsg') }}";
            var status = $('.status').val();
            var model = "{{ request()->type == 'kindergarten-type' ? 'KindergartenType' : 'FrameworkType' }}";
            moveToArchive( msg, status , model);
        });

    </script>
@endpush
