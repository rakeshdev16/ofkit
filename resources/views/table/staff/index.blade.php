@extends('layout.master')
@push('customLink')
@endpush
@section('section')
    <div class="page-wrapper">
        <div class="page-content">
            <ul class="nav nav-fill nav-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="professionTab" data-bs-toggle="tab" href="#profession" role="tab"
                        aria-controls="profession" aria-selected="true"> Profession </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="roleTab" data-bs-toggle="tab" href="#role" role="tab"
                        aria-controls="role" aria-selected="false"> Role </a>
                </li>
                {{-- <li class="nav-item" role="presentation">
                    <a class="nav-link" id="associationTab" data-bs-toggle="tab" href="#association" role="tab"
                        aria-controls="association" aria-selected="false"> Association </a>
                </li> --}}
            </ul>
            <div class="tab-content pt-5" id="tab-content">
                <div class="tab-pane active" id="profession" role="tabpanel" aria-labelledby="professionTab">
                    @include('table.staff.profession.index', ['professions' => $professions])
                </div>
                <div class="tab-pane" id="role" role="tabpanel" aria-labelledby="roleTab">
                    @include('table.staff.role.index', ['roles' => $roles])
                </div>
                {{-- <div class="tab-pane" id="association" role="tabpanel" aria-labelledby="associationTab">
                    @include('table.staff.association.index')
                </div> --}}
            </div>
        </div>
    </div>
@endsection
@push('customScript')
    <script src="{{ asset('assets/js/custom-datatable.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    <script>
        $(document).on('click', '.nav-link', function() {
            window.location.href.split('?')[0];
            var type = $(this).attr('aria-controls');
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
                title: "{{ __('staff-table.confirmTitle') }}",
                text: "{{ __('staff-table.confirmText') }}",
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