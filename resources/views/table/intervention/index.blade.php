@extends('layout.master')
@push('customLink')
@endpush
@section('section')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="card-body">
                <ul class="nav nav-tabs nav-primary mb-0 tables" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ request()->type == 'intervention-type' ? 'active' : '' }}" data-type="intervention-type" data-bs-toggle="tab" href="#intervention-type" role="tab" aria-selected="false" tabindex="-1">
                            <div class="d-flex align-items-center">
                                <div class="tab-icon"><i class="bx bx-comment-detail font-18 me-1"></i>
                                </div>
                                <div class="tab-title"> Occurrence/Intervention Type </div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ request()->type == 'documents-and-approval' ? 'active' : '' }}" data-type="documents-and-approval" data-bs-toggle="tab" href="#documents-and-approval" role="tab" aria-selected="false" tabindex="-1">
                            <div class="d-flex align-items-center">
                                <div class="tab-icon"><i class="bx bx-bookmark-alt font-18 me-1"></i>
                                </div>
                                <div class="tab-title"> Documents and Approval</div>
                            </div>
                        </a>
                    </li>
                </ul>
                <div class="tab-content pt-3">
                    <div class="tab-pane fade {{ request()->type == 'intervention-type' ? 'active show' : '' }}" id="intervention-type" role="tabpanel">
                        @if (request()->type == 'intervention-type')
                            @include('table.intervention.intervention-type.index', ['interventionTypes' => $interventionTypes])
                        @endif
                    </div>
                    <div class="tab-pane fade {{ request()->type == 'documents-and-approval' ? 'active show' : '' }}" id="documents-and-approval" role="tabpanel">
                        @if (request()->type == 'documents-and-approval')
                            @include('table.intervention.documents-and-approval.index', ['documents' => $documents])
                        @endif
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
            $('#intervention-type').html('');
            $('#documents-and-approval').html('');
            $.ajax({
                type : 'GET',
                url : "{{ route('intervention.tab') }}",
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
            var url = "{{ route('intervention.destroy', ':ids') }}";
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