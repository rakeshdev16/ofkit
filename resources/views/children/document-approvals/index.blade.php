@extends('layout.master')
@push('customLink')
@endpush
@section('section')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="mb-4 page-info">
                <div class="">
                    <h3 class="mb-0 text-uppercase">{{ __('children.documentApprovals') }} </h3>
                </div>
                <div class="mt-3">
                    @if (Auth::user()->hasRole(['admin', 'manager']))
                        <button class="btn button moveToArchive">{{ __('comon.moveToArchive') }}</button>
                    @endif
                    {{-- <a href="#" data-bs-toggle="modal" data-bs-target="#addDocumentModal" class="btn button addDocument">{{ __('comon.addNew') }} +</a> --}}
                    <a href="#" class="btn button addDocument">{{ __('comon.addNew') }} +</a>
                    <a href="{{ route('children.show', $children->id) }}" class="btn button m-top-1">{{ __('comon.back') }}</a>
                </div>
            </div>
            <div class="row my-2 mx-1 children-detail">
                <div class="col-md-6"><label for=""><b>{{ __('children.childName') }}:</b></label> {{ $children->name }}</div>
                <div class="col-md-6"><label for=""><b>{{ __('children.ID') }}:</b></label> {{ $children->identification }}</div>
                <div class="col-md-6"><label for=""><b>{{ __('children.kindergarten') }}:</b></label> {{ getKindergartenNameById($children->kindergarten_id) }}</div>
                <div class="col-md-6"><label for=""><b>{{ __('children.childBirthday') }}:</b></label> {{ $children->date_of_birth }}</div>
                <div class="col-md-6"><label for=""><b>{{ __('children.childAge') }}:</b></label> {{ $children->age }}</div>
            </div>
            <div class="card small-table">
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
        @php
            echo '<pre>';
            print_r(Session::get('errors'));
            echo '</pre>';
        @endphp
        <div class="modal fade" id="addDocumentModal" tabindex="-1" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('children.document') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('documents-approvals.post') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <input type="hidden" name="children_id" value="187">
                                <div class="col-md-12">
                                    <label for="input16" class="form-label">Document</label>
                                    <div class="position-relative input-icon">
                                        <input type="file" class="form-control  file" id="file" name="document" placeholder="Document" value="" onchange="">
                                    </div>
                                    <input type="hidden" class="document" name="old_document" value="">
                                </div>
                                <div class="col-md-12 pt-3">
                                    <label for="input16" class="form-label">File Type</label>
                                    <div class="position-relative input-icon">
                                        <select name="file_type_id" class="form-control file-type">
                                            <option value="" selected="">Select</option>
                                            @foreach ($fileTypes as $fileType)
                                                <option value="{{ $fileType['key'] }}">{{ $fileType['value'] }}</option>
                                            @endforeach
                                        </select>
                                        <span class="position-absolute top-50 translate-middle-y">
                                            <i class="bx bx-buildings"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-12 pt-3">
                                    <label for="input16" class="form-label">Document Description</label>
                                    <div class="position-relative input-icon">
                                        <textarea name="description" class="form-control description" id="description" placeholder="Document Description" cols="30" rows="2" style="resize: none;"></textarea>
                                        <span class="position-absolute top-50 translate-middle-y">
                                            <i class="bx bx-network-chart"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="my-3">
                                <input type="hidden" name="id" class="id">
                                <button type="submit" class="btn button">{{ __('comon.submit') }}</button>
                                <button type="button" class="btn button" data-bs-dismiss="modal" aria-label="Close">{{ __('comon.close') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('customScript')
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Show Laravel validation errors using Toastr
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error("{{ $error }}");
                @endforeach
            @endif
        });


        $(document).on('click', '.button', function() {
            $(this).attr('disabled', false);
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
