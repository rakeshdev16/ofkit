@extends('layout.master')
@push('customLink')
@endpush
@section('section')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="card-body">
                <ul class="nav nav-tabs nav-primary mb-0 tables" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ request()->type == 'parents-status' ? 'active' : '' }}" data-type="parents-status" data-bs-toggle="tab" href="#parents-status" role="tab" aria-selected="false" tabindex="-1">
                            <div class="d-flex align-items-center">
                                <div class="tab-icon"><i class="bx bx-comment-detail font-18 me-1"></i>
                                </div>
                                <div class="tab-title"> Parents Status </div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ request()->type == 'hmo' ? 'active' : '' }}" data-type="hmo" data-bs-toggle="tab" href="#hmo" role="tab" aria-selected="false" tabindex="-1">
                            <div class="d-flex align-items-center">
                                <div class="tab-icon"><i class="bx bx-bookmark-alt font-18 me-1"></i>
                                </div>
                                <div class="tab-title"> HMO</div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ request()->type == 'diagnosis' ? 'active' : '' }}" data-type="diagnosis" data-bs-toggle="tab" href="#diagnosis" role="tab" aria-selected="false" tabindex="-1">
                            <div class="d-flex align-items-center">
                                <div class="tab-icon"><i class="bx bx-bookmark-alt font-18 me-1"></i>
                                </div>
                                <div class="tab-title"> Diagnosis</div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ request()->type == 'functionality' ? 'active' : '' }}" data-type="functionality" data-bs-toggle="tab" href="#functionality" role="tab" aria-selected="false" tabindex="-1">
                            <div class="d-flex align-items-center">
                                <div class="tab-icon"><i class="bx bx-bookmark-alt font-18 me-1"></i>
                                </div>
                                <div class="tab-title"> Functionality</div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ request()->type == 'status' ? 'active' : '' }}" data-type="status" data-bs-toggle="tab" href="#status" role="tab" aria-selected="false" tabindex="-1">
                            <div class="d-flex align-items-center">
                                <div class="tab-icon"><i class="bx bx-bookmark-alt font-18 me-1"></i>
                                </div>
                                <div class="tab-title"> Status</div>
                            </div>
                        </a>
                    </li>
                </ul>
                <div class="tab-content pt-3">
                    <div class="tab-pane fade {{ request()->type == 'parents-status' ? 'active show' : '' }}" id="parents-status" role="tabpanel">
                        @if (request()->type == 'parents-status')
                            @include('table.children.parents-status.index', ['parentsStatus' => $parentsStatus])
                        @endif
                    </div>
                    <div class="tab-pane fade {{ request()->type == 'hmo' ? 'active show' : '' }}" id="hmo" role="tabpanel">
                        @if (request()->type == 'hmo')
                            @include('table.children.hmo.index', ['hmos' => $hmos])
                        @endif
                    </div>
                    <div class="tab-pane fade {{ request()->type == 'diagnosis' ? 'active show' : '' }}" id="diagnosis" role="tabpanel">
                        @if (request()->type == 'diagnosis')
                            @include('table.children.diagnosis.index', ['diagnosises' => $diagnosises])
                        @endif
                    </div>
                    <div class="tab-pane fade {{ request()->type == 'functionality' ? 'active show' : '' }}" id="functionality" role="tabpanel">
                        @if (request()->type == 'functionality')
                            @include('table.children.functionality.index', ['statuses' => $statuses])
                        @endif
                    </div>
                    <div class="tab-pane fade {{ request()->type == 'status' ? 'active show' : '' }}" id="status" role="tabpanel">
                        @if (request()->type == 'status')
                            @include('table.children.status.index', ['statuses' => $statuses])
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
            console.log(type);
            queryParam('type', type);
            $('#parents-status').html('');
            $('#hmo').html('');
            $('#diagnosis').html('');
            $('#functionality').html('');
            $('#status').html('');
            $.ajax({
                type : 'GET',
                url : "{{ route('children-table.tab') }}",
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