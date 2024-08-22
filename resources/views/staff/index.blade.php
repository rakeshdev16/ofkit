@extends('layout.master')
@push('customLink')
    
@endpush
@section('section')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="mb-4 page-info">
                <div>
                    <h3 class="mb-0 text-uppercase">{{ __('staff.staff') }} ({{ __('comon.'.Auth::user()->getRoleNames()->first()) }})</h3>
                    <select name="" class="select-filter">
                        <option value="">All Kindergartens</option>
                        @foreach ($kindergartens as $kindergarten)
                            <option {{ request()->kindergarten_id == $kindergarten['key'] ? 'selected' : '' }} value="{{ $kindergarten['key'] }}">{{ $kindergarten['value'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-3 buttons">
                    @if (Auth::user()->hasRole('admin'))
                        <button class="btn button moveToArchive">{{ __('staff.moveBtnText') }}</button>
                        <a href="{{ route('staff.create') }}" class="btn button">{{ __('staff.addBtnText') }} +</a>
                    @endif
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive" >
                        @include('components.table-search', ['label' => 'Staff', 'count' => $count])
                    <div id="dataTable">
                            @include('staff.table', ['members' => $members])
                        </div>
                    </div>
                    <div class="lising d-none" id="accordion">
                        @include('staff.accordion', ['members' => $members])
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
        var url = "{{ route('staff.destroy', ':ids') }}";
        var msg = "{{ __('validation.chose_at_least_one', ['attribute' => 'staff member']) }}";
        moveToArchive(url, msg);
    });
</script>
<script src="{{ asset('assets/js/custom-datatable.js') }}"></script>
@endpush
