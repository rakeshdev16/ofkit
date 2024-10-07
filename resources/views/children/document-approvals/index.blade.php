@extends('layout.master')
@push('customLink')
    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" /> --}}
@endpush
@section('section')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="mb-4 page-info">
                <div class="">
                    <h3 class="mb-0 text-uppercase">{{ __('children.documentApprovals') }} </h3>
                    <div class="row my-2 mx-1 children-detail w-100">
                        <div class="col-md-6"><label for=""><b>{{ __('children.childName') }}:</b></label> {{ $children->name . ' ' . $children->family_name }}</div>
                        <div class="col-md-6"><label for=""><b>{{ __('children.ID') }}:</b></label> {{ $children->identification }}</div>
                        <div class="col-md-6"><label for=""><b>{{ __('children.kindergarten') }}:</b></label> {{ getKindergartenNameById($children->kindergarten_id) }}</div>
                        <div class="col-md-6"><label for=""><b>{{ __('children.childBirthday') }}:</b></label> {{ $children->date_of_birth }}</div>
                        <div class="col-md-6"><label for=""><b>{{ __('children.childAge') }}:</b></label> {{ $children->age }}</div>
                    </div>
                </div>
                <div class="mt-3">
                    @if (Auth::user()->hasRole(['admin', 'manager']))
                        <button class="btn button moveToArchive">{{ __('comon.moveToArchive') }}</button>
                    @endif
                    {{-- <a href="#" data-bs-toggle="modal" data-bs-target="#addDocumentModal" class="btn button addDocument">{{ __('comon.addNew') }} +</a> --}}
                    {{-- <a href="#" class="btn button addDocument">{{ __('comon.addNew') }} +</a> --}}
                    <a href="{{ route('documents-approvals.create', $children->id) }}" class="btn button">{{ __('comon.addNew') }} +</a>
                    <a href="{{ route('children.show', $children->id) }}" class="btn button m-top-1">{{ __('comon.back') }}</a>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-xl-3 col-lg-4 col-md-6  my-1">
                    <div class="dropdown dropdown-filter d-flex justify-content-between">
                        @php
                            if (request()->date && strpos(request()->date, ',') !== false) {
                                $date = explode(',', request()->date);
                                $date = date('d/m/Y', strtotime($date[1])) . ' - ' . date('d/m/Y', strtotime($date[0]));
                            } else {
                                $date = request()->date ? date('d/m/Y', strtotime(request()->date)) : __('children.selectDate');
                            }
                        @endphp
                        <button class="btn dropdown-toggle dropdown-filter-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ $date ? $date : __('children.selectDate') }}
                        </button>
                        <button class="btn btn-clear-filter" onclick="clearFilter('date')" type="button">x</button>
                        <ul class="dropdown-menu p-2 date-filters">
                            <li><a class="dropdown-item" onclick="dateFilter({{ $lastWeek }});" href="#">{{ __('children.lastWeek') }}</a></li>
                            <li><a class="dropdown-item" onclick="dateFilter({{ $month }});" href="#">{{ __('children.month') }}</a></li>
                            <li><a class="dropdown-item" onclick="dateFilter({{ $pastThreeMonth }});" href="#">{{ __('children.month3') }}</a></li>
                            <li><a class="dropdown-item" onclick="dateFilter({{ $pastSixMonth }});" href="#">{{ __('children.halfYear') }}</a></li>
                            <li>
                                <a class="dropdown-item specific-date-filter" href="#">{{ __('children.specificDate') }}</a>
                                <input type="date" name="date" class="form-control doc-filter specificDate" style="display: none">
                            </li>
                            {{-- <li>
                                <a class="dropdown-item specific-date-range-filter" href="#">Date Range</a>
                                <input type="text" name="date_range" class="form-control doc-filter dateRangePicker" placeholder="Select Date Range" style="display: none">
                            </li> --}}
                        </ul>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6  my-1">
                    <select class="form-control doc-filter" name="file_type_id">
                        <option value="">{{ __('children.selectFileType') }}</option>
                        @foreach ($fileTypes as $fileType)
                            <option value="{{ $fileType->key }}">{{ $fileType->value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive full-width-table">
                        @include('components.table-search', ['label' => 'Documents', 'count' => @$count])
                        <div id="dataTable">
                            @include('children.document-approvals.table', ['documents' => @$documents])
                        </div>
                    </div>
                    <div class="lising d-none">
                        @include('components.table-search', ['label' => 'Documents', 'count' => $count])
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
    {{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script> --}}
    <script>
        $(document).ready(function() {
            $('.specific-date-filter').on('click', function(e) {
                e.stopPropagation();
                e.preventDefault();
                $('.dateRangePicker').hide();
                $('.specificDate').toggle();
            });
            // $('.specific-date-range-filter').on('click', function(e) {
            //     e.stopPropagation();
            //     e.preventDefault();
            //     $('.dateRangePicker').toggle();
            //     $('.specificDate').hide();
            // });

            $('.specificDate').on('change', function(e) {
                $('.dropdown-filter-toggle').html(dateFormat($(this).val()));
            });

            // $('.dateRangePicker').daterangepicker({
            //     locale: {
            //         format: 'DD/MM/YYYY'
            //     }
            // });

            // $('.dateRangePicker').on('apply.daterangepicker', function(ev, picker) {
            //     $('.dropdown-filter-toggle').html(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            // });
        });

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
            var url = "{{ route('documents-approvals.delete', ':ids') }}";
            var msg = "Please choose at least one document";
            moveToArchive(url, msg);
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
