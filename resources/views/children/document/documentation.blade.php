@extends('layout.master')
@push('customLink')
    
@endpush
@section('section')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="mb-4 page-info">
                <div>
                    <h3 class="mb-0 text-uppercase">Children Documents ({{ __('comon.'.Auth::user()->getRoleNames()->first()) }})</h3>
                </div>
                <div class="mt-3">
                    <a href="{!! URL::previous() !!}" class="btn button">{{ __('staff.back') }}</a>
                    @if (Auth::user()->hasRole('admin'))
                        {{-- <button class="btn button moveToArchive">{{ __('comon.moveToArchive') }}</button> --}}
                    @endif
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
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
