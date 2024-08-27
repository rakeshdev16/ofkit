@extends('layout.master')
@push('customLink')
    
@endpush
@section('section')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="mb-4 page-info">
                <div>
                    <h3 class="mb-0 text-uppercase">Children Documents ({{ __('comon.'.Auth::user()->getRoleNames()->first()) }})</h3>
                    <div class="row my-2 mx-1">
                        <div class="col-md-6"><label for=""><b>Child Name:</b></label> {{ $childrens->name }}</div>
                        <div class="col-md-6"><label for=""><b>I.D:</b></label> {{ $childrens->identification }}</div>
                        <div class="col-md-6"><label for=""><b>Kindergarten:</b></label> {{ getKindergartenNameById($childrens->kindergarten_id) }}</div>
                        <div class="col-md-6"><label for=""><b>Child's Birthday:</b></label> {{ $childrens->date_of_birth }}</div>
                        <div class="col-md-6"><label for=""><b>Child's Age:</b></label> {{ $childrens->age }}</div>
                    </div>
                </div>
                <div class="mt-3">
                    <button data-url="{{ route('children.show', Request::segment(2)) }}" class="btn button exit">{{ __('comon.back') }}</button>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive full-width-table">
                        @include('components.table-search', ['label' => "Children Documents", 'count' => @$documentationCount])
                        <div id="dataTable">
                            @include('children.document.documentation-table', ['documentations' => $documentations])
                        </div>
                    </div>
                    <div class="lising d-none" id="accordion">
                        @include('children.document.documentation-accordion', ['documentations' => $documentations])
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
