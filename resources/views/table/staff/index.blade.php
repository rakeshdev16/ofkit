@extends('layout.master')
@push('customLink')
@endpush
@section('section')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="card-body">
                <ul class="nav nav-tabs nav-primary mb-0 tables" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" data-type="profession" data-bs-toggle="tab" href="#profession" role="tab" aria-selected="false" tabindex="-1">
                            <div class="d-flex align-items-center">
                                <div class="tab-icon"><i class="bx bx-comment-detail font-18 me-1"></i>
                                </div>
                                <div class="tab-title"> Academic Profession </div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-type="role" data-bs-toggle="tab" href="#role" role="tab" aria-selected="false" tabindex="-1">
                            <div class="d-flex align-items-center">
                                <div class="tab-icon"><i class="bx bx-bookmark-alt font-18 me-1"></i>
                                </div>
                                <div class="tab-title"> Professional Role</div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-type="association" data-bs-toggle="tab" href="#association" role="tab" aria-selected="false" tabindex="-1">
                            <div class="d-flex align-items-center">
                                <div class="tab-icon"><i class="bx bx-bookmark-alt font-18 me-1"></i>
                                </div>
                                <div class="tab-title"> Association</div>
                            </div>
                        </a>
                    </li>
                </ul>
                <div class="tab-content pt-3">
                    <div class="tab-pane fade active show" id="profession" role="tabpanel">
                        @include('table.staff.profession.index', ['professions' => $professions])
                    </div>
                    <div class="tab-pane fade" id="role" role="tabpanel">
                        @include('table.staff.association.index', ['roles' => $roles])
                    </div>
                    <div class="tab-pane fade" id="association" role="tabpanel">
                        @include('table.staff.association.index', ['roles' => $roles])
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
                url : "{{ route('staff-table.tab') }}",
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
                toastr.warning("Please select at least one profession");
                return false
            }
            var url = "{{ route('staff-table.destroy', ':ids') }}";
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