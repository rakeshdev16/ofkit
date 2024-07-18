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
                            <option value="{{ $kindergarten['key'] }}">{{ $kindergarten['value'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-2 buttons">
                    @if (Auth::user()->hasRole('admin'))
                        <button class="btn button moveToArchive">{{ __('staff.moveBtnText') }}</button>
                        <a href="{{ route('staff.create') }}" class="btn button">{{ __('staff.addBtnText') }} +</a>
                    @endif
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive" >
                        <div class="table-search">
                            <label>Search: <input type="search" class="search" value="{{ request()->search }}" placeholder=""></label>
                        </div>
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
    <script src="{{ asset('assets/js/custom-datatable.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    <script>
        $(document).on('change', '.select-filter', function () {
            var kindergartenId = $(this).val();
            var url = queryParam('kindergarten_id', kindergartenId);
            filter(url);
        });
        
        $(document).on('click', '.moveToArchive', function() {
            var ids = [];
            $(".checkbox:checked").map(function(){
                ids.push($(this).val());
            });
            if (ids.length == 0) {
                toastr.warning("{{ __('staff.selectMsg') }}");
                return false
            }
            var url = "{{ route('staff.destroy', ':ids') }}";
            url = url.replace(':ids', ids);
            Swal.fire({
                title: "Are you sure?",
                // text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, archive it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        type: 'DELETE',
                        url: url,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        success: function (data) {
                            if (data.status == true) {
                                data.ids.map(function(id) {
                                    $('.tr-'+id).remove();
                                });
                                toastr.success(data.message);
                            }
                        }
                    });               
                }
            });
        });
    </script>
@endpush
