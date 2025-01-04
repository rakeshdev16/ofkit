@extends('layout.master')
@push('customLink')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush
@section('section')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="mb-4 page-info">
                <div class="">
                    <h3 class="mb-0 text-uppercase">{{ __('children.documentApprovals') }}</h3>
                    <div class="row my-2 mx-1 children-detail w-100">
                        <div class="col-md-6"><label for=""><b>{{ __('children.childName') }}:</b></label> {{ $children->name . ' ' . $children->family_name }}</div>
                        <div class="col-md-6"><label for=""><b>{{ __('children.ID') }}:</b></label> {{ $children->identification }}</div>
                        <div class="col-md-6"><label for=""><b>{{ __('children.kindergarten') }}:</b></label> {{ getKindergartenNameById($children->kindergarten_id) }}</div>
                        <div class="col-md-6"><label for=""><b>{{ __('children.childBirthday') }}:</b></label> {{ $children->date_of_birth }}</div>
                        <div class="col-md-6"><label for=""><b>{{ __('children.childAge') }}:</b></label> {{ $children->calclulated_age }}</div>
                    </div>
                </div>
                <div class="mt-3">
                    @if (Auth::user()->hasRole(['admin', 'manager']))
                        {{-- <button class="btn button moveToArchive">{{ __('comon.moveToArchive') }}</button> --}}
                        @include('components.table-button')
                    @endif
                    {{-- <a href="#" data-bs-toggle="modal" data-bs-target="#addDocumentModal" class="btn button addDocument">{{ __('comon.addNew') }} +</a> --}}
                    {{-- <a href="#" class="btn button addDocument">{{ __('comon.addNew') }} +</a> --}}
                    <a href="{{ route('documents-approvals.create', $children->id) }}" class="btn button">{{ __('comon.addNew') }} +</a>
                    <a href="{{ route('children.show', $children->id) }}" class="btn button m-top-1">{{ __('comon.back') }}</a>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-xl-3 col-lg-4 col-md-6  my-1">
                    @include('components.date-range-filter', ['date' => request('date')])
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6  my-1">
                    <select class="form-control doc-filter" name="file_type_id">
                        <option value="">{{ __('children.selectFileType') }}</option>
                        @foreach ($fileTypes as $fileType)
                            <option value="{{ $fileType->key }}">{{ $fileType->value }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- <div class="col-xl-3 col-lg-4 col-md-6 py-1 my-1">
                    @include('components.active-inactive')
                </div> --}}
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive full-width-table">
                        @include('components.table-search', ['label' => __('children.totalDocuments'), 'count' => @$count])
                        <div id="dataTable">
                            @include('children.document-approvals.table', ['documents' => @$documents])
                        </div>
                    </div>
                    <div class="lising d-none">
                        @include('components.table-search', ['label' => __('children.totalDocuments'), 'count' => $count])
                        <div id="accordion">
                            @include('children.document-approvals.accordion', ['documents' => @$documents])
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="modal fade" id="addDocumentModal" tabindex="-1" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('children.document') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                    </div>
                </div>
            </div>
        </div> --}}
    </div>
@endsection
@push('customScript')
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $(function() {
            var selectedDate = "{{ request('date') }}";
            var date = selectedDate.split(',');
            var start = moment().subtract(29, 'days');
            var end = moment();
            var isUserInteraction = false;

            function cb(start, end, label) {
                $('#reportrange span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
                if (isUserInteraction) {
                    var url = queryParam('date', [start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD')]);
                    filter(url);
                }
            }

            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Last Week': [moment().subtract(6, 'days'), moment()],
                    'Month': [moment().startOf('month'), moment().endOf('month')],
                    'Month 3': [moment().subtract(3, 'months').startOf('month'), moment().endOf('month')],
                    'Half a Year': [moment().subtract(6, 'months').startOf('month'), moment().endOf('month')],
                },
                locale: {
                    format: 'DD/MM/YYYY'
                }
            }, function(start, end, label) {
                isUserInteraction = true;
                cb(start, end, label);
            });
            if (isNaN(date[0]) && isNaN(date[1])) {
                $('#reportrange span').html(moment(date[0]).format('DD/MM/YYYY') + ' - ' + moment(date[1]).format('DD/MM/YYYY'));
            } else {
                $('#reportrange span').html('Select Date');
            }
        });

        $(document).on('click', '.btn-clear-filter', function() {
            var url = queryParam('date', '');
            filter(url);
            $('#reportrange span').html('Select Date');
        });
        // $('.dateRangePicker').daterangepicker({
        //     locale: {
        //         format: 'DD/MM/YYYY'
        //     }
        // });
        // $(document).ready(function() {
        //     $('.specific-date-filter').on('click', function(e) {
        //         e.stopPropagation();
        //         e.preventDefault();
        //         $('.dateRangePicker').hide();
        //         $('.specificDate').toggle();
        //     });
        //     $('.specific-date-range-filter').on('click', function(e) {
        //         e.stopPropagation();
        //         e.preventDefault();
        //         $('.dateRangePicker').toggle();
        //         $('.specificDate').hide();
        //     });

        //     $('.specificDate').on('change', function(e) {
        //         $('.dropdown-filter-toggle').html(dateFormat($(this).val()));
        //     });

        //     $('.dateRangePicker').on('apply.daterangepicker', function(ev, picker) {
        //         $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        //         $('.dropdown-filter-toggle').html(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        //     });
        // });

        // $(document).ready(function() {
        //     $('.specific-date-filter').on('click', function(e) {
        //         e.stopPropagation();
        //         e.preventDefault();
        //         $('.specificDate').toggle();
        //     });

        //     $('.specificDate').on('change', function(e) {
        //         $('.dropdown-filter-toggle').html(dateFormat($(this).val()));
        //     });

        // });

        $(document).ready(function() {
            // Show Laravel validation errors using Toastr
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error("{{ $error }}");
                @endforeach
            @endif
        });

        $(document).on('click', '.addDocument', function() {
            $('.file').val('');
        });

        $(document).on('click', '.moveToArchive', function() {
            var msg = "{{ __('cluster.selectMsg') }}";
            var status = $('.status').val();
            var model = "ChildrenDocumentAndApproval";
            var children_id = "{{$children->id}}";
            moveToArchive( msg, status , model, children_id);
        });

        $(document).on('click', '.addDocument', function() {
            $('#addDocumentModal').find('input, select, textarea').val('');
            $('#addDocumentModal').modal('toggle');
        });

        $(document).on('click', '.editDocument', function() {
            var id = $(this).data('id');
            var doc = $(this).data('document');
            var fileTypeId = $(this).data('file-type-id');
            var description = $(this).data('description');
            console.log(fileTypeId);

            $('.id').val(id);
            $('.document').val(doc);
            $('.file-type').val(fileTypeId).trigger('change');

            $('.description').val(description);
            $('#addDocumentModal').modal('toggle');
        });
    </script>
    <script src="{{ asset('assets/js/custom-datatable.js') }}"></script>
@endpush
