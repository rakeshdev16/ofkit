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
                    @if (Auth::user()->hasRole('admin'))
                        <button class="btn button moveToArchive">{{ __('comon.moveToArchive') }}</button>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#addDocumentModal" class="btn button addDocument">{{ __('comon.addNew') }} +</a>
                        <a href="{{ route('children.show', $children->id) }}" class="btn button m-top-1">{{ __('comon.back') }}</a>
                    @endif
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
                            <label for="">{{ __('children.document') }}</label>
                            <input type="file" name="document" class="form-control file" required>
                            <input type="hidden" name="children_id" value="{{ $children->id }}">
                            <div class="my-3">
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
    </script>
    <script src="{{ asset('assets/js/custom-datatable.js') }}"></script>
@endpush
