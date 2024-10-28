@extends('layout.master')
@push('customLink')
@endpush
@section('section')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="mb-4 page-info">
                <div>
                    <h3 class="mb-0 text-uppercase">{{ __('cluster.cluster') }} </h3>
                </div>
                <div class="mt-3">
                    <button class="btn button moveToArchive">{{ __('cluster.moveBtnText') }}</button>
                    <a href="{{ route('cluster.create') }}" class="btn button">{{ __('cluster.addBtnText') }} +</a>
                </div>
            </div>
            <div class="card small-table">
                <div class="card-body">
                    <div class="table-responsive full-width-table">
                        @include('components.table-search', ['label' => __('cluster.clusters'), 'count' => $count])
                        <div id="dataTable">
                            @include('cluster.table', ['clusters' => $clusters])
                        </div>
                    </div>
                    <div class="lising d-none">
                        @include('components.table-search', ['label' => __('cluster.clusters'), 'count' => $count])
                        <div id="accordion">
                            @include('cluster.accordion', ['clusters' => $clusters])
                        </div>
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
            var url = "{{ route('cluster.destroy', ':ids') }}";
            var msg = "{{ __('cluster.selectMsg') }}";
            moveToArchive(url, msg);
        });

        // $(document).on('click', '.moveToArchive', function() {
        //     var url = "{{ route('activeInactive.records') }}";
        //     var msg = "{{ __('cluster.selectMsg') }}";
        //     var status = "{{ request('status', 'active') }}";
        //     var model = "Cluster";
        //     moveToArchive(url, msg, status , model);
        // });

        // function moveToArchive(url, msg, status, model) {
        //     var ids = [];
        //     $(".checkbox:checked").each(function () {
        //         var value = $(this).val();
        //         if (value) {  // Only push non-empty values
        //             ids.push(value);
        //         }
        //     });
        //     $.unique(ids.sort());

        //     if (ids.length == 0) {
        //         toastr.warning(msg);
        //         return false
        //     }
        //     var url = url + "?ids=" + ids.join(',') + "&status=" + status + "&model=" + model;
        //     // console.log(url);

        //     Swal.fire({
        //         title: confirmMsgTitle,
        //         icon: "warning",
        //         showCancelButton: true,
        //         confirmButtonColor: "#3085d6",
        //         cancelButtonColor: "#d33",
        //         confirmButtonText: confirmButtonText,
        //         cancelButtonText: cancelButtonText
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             $.ajax({
        //                 headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        //                 type: 'POST',
        //                 url: url,
        //                 processData: false,
        //                 contentType: false,
        //                 dataType: 'json',
        //                 success: function (data) {
        //                     if (data.status == true) {
        //                         data.ids.map(function (id) {
        //                             $('.tr-' + id).remove();
        //                         });
        //                         toastr.success(data.message);
        //                     }
        //                 }
        //             });
        //         }
        //     });
        // }
    </script>
    <script src="{{ asset('assets/js/custom-datatable.js') }}"></script>
@endpush
