@extends('layout.master')
@push('customLink')
@endpush
@section('section')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="card-body">
                <ul class="nav nav-tabs nav-primary mb-0 tables" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ request()->type == 'kindergarten-type' ? 'active' : '' }}" data-type="kindergarten-type" data-bs-toggle="tab" href="#kindergartenType" role="tab" aria-selected="false" tabindex="-1">
                            <div class="d-flex align-items-center">
                                <div class="tab-icon"><i class="bx bx-comment-detail font-18 me-1"></i>
                                </div>
                                <div class="tab-title"> Kindergarten Type </div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ request()->type == 'framework-type' ? 'active' : '' }}" data-type="framework-type" data-bs-toggle="tab" href="#frameworkType" role="tab" aria-selected="false" tabindex="-1">
                            <div class="d-flex align-items-center">
                                <div class="tab-icon"><i class="bx bx-bookmark-alt font-18 me-1"></i>
                                </div>
                                <div class="tab-title"> Framework Type</div>
                            </div>
                        </a>
                    </li>
                </ul>
                <div class="tab-content pt-3">
                    <div class="tab-pane fade {{ request()->type == 'kindergarten-type' ? 'active show' : '' }}" id="kindergartenType" role="tabpanel">
                        @include('table.framework.kindergarten-type.index', ['kindergartenTypes' => $kindergartenTypes])
                    </div>
                    <div class="tab-pane fade {{ request()->type == 'framework-type' ? 'active show' : '' }}" id="frameworkType" role="tabpanel">
                        @include('table.framework.framework-type.index', ['frameworkTypes' => $frameworkTypes])
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
        $(document).on('click', '.nav-link', function() {
            var uri = window.location.toString();
            
            if (uri.indexOf("?") > 0) {
                var clean_uri = uri.substring(0, uri.indexOf("?"));
                window.history.replaceState({}, document.title, clean_uri);
            }
            var type = $(this).data('type');
            queryParam('type', type);
            $('#profession').html('');
            $('#role').html('');
            $.ajax({
                type : 'GET',
                url : "{{ route('framework-table.tab') }}",
                data : { type: type },
                success : function(data){
                    if (data.status == true) {
                        $('#'+type).html(data.data);
                    }
                }
            });
        });

        $(document).on('click', '.moveToArchive', function() {
            var type = $(this).data('type');
            var ids = [];
            $(".checkbox:checked").map(function() {
                ids.push($(this).val());
            });
            if (ids.length == 0) {
                toastr.warning("Please select at least one "+type);
                return false
            }
            var url = "{{ route('framework-table.destroy', ':ids') }}";
            url = url.replace(':ids', ids);
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
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
                        url: url+'?type='+type,
                        success: function(data) {
                            if (data.status == true) {
                                data.ids.map(function(id) {
                                    $('.tr-' + id).remove();
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