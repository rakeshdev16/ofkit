@extends('layout.master')
@push('customLink')
    
@endpush
@section('section')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="mb-4 page-info">
                <div>
                    <h3 class="mb-0 text-uppercase">{{ __('cluster.cluster') }} ({{ __('comon.'.Auth::user()->getRoleNames()->first()) }})</h3>
                </div>
                <div class="">
                    <a href="{{ route('cluster.create') }}" class="btn button">{{ __('cluster.addBtnText') }}</a>
                    <button class="btn button moveToArchive">{{ __('cluster.moveBtnText') }}</button>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive" id="dataTable">
                        @include('cluster.table', ['clusters' => $clusters])
                    </div>
                    <div class="lising d-none" id="accordion">
                        @include('cluster.accordion', ['clusters' => $clusters])
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
        $(document).on('click', '.moveToArchive', function() {
            var ids = [];
            $(".checkbox:checked").map(function(){
                ids.push($(this).val());
            });
            if (ids.length == 0) {
                toastr.warning("{{ __('cluster.selectMsg') }}");
                return false
            }
            var url = "{{ route('cluster.destroy', ':ids') }}";
            url = url.replace(':ids', ids);
            Swal.fire({
                title: "{{ __('cluster.confirmTitle') }}",
                text: "{{ __('cluster.confirmText') }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
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
