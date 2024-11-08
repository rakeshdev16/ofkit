@extends('layout.master')
@push('customLink')
@endpush
@section('section')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="card-body">
                <ul class="nav nav-tabs nav-primary mb-0 tables" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ request()->type == 'profession' ? 'active' : '' }}" data-type="profession" data-bs-toggle="tab" href="#profession" role="tab" aria-selected="false" tabindex="-1">
                            <div class="d-flex align-items-center">
                                <div class="tab-icon"><i class="bx bx-building-house font-18 me-1"></i>
                                </div>
                                <div class="tab-title"> {{ __('tables.academicProfession') }} </div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ request()->type == 'role' ? 'active' : '' }}" data-type="role" data-bs-toggle="tab" href="#role" role="tab" aria-selected="false" tabindex="-1">
                            <div class="d-flex align-items-center">
                                <div class="tab-icon"><i class="bx bx-id-card font-18 me-1"></i>
                                </div>
                                <div class="tab-title"> {{ __('tables.professionalRole') }} </div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ request()->type == 'association' ? 'active' : '' }}" data-type="association" data-bs-toggle="tab" href="#association" role="tab" aria-selected="false" tabindex="-1">
                            <div class="d-flex align-items-center">
                                <div class="tab-icon"><i class="bx bx-group font-18 me-1"></i>
                                </div>
                                <div class="tab-title"> {{ __('tables.association') }} </div>
                            </div>
                        </a>
                    </li>
                </ul>
                <div class="tab-content pt-3">
                    <div class="tab-pane fade {{ request()->type == 'profession' ? 'active show' : '' }}" id="profession" role="tabpanel">
                        @if (request()->type == 'profession')
                            @include('table.staff.profession.index', ['professions' => $professions])
                        @endif
                    </div>
                    <div class="tab-pane fade {{ request()->type == 'role' ? 'active show' : '' }}" id="role" role="tabpanel">
                        @if (request()->type == 'role')
                            @include('table.staff.role.index', ['roles' => $roles])
                        @endif
                    </div>
                    <div class="tab-pane fade {{ request()->type == 'association' ? 'active show' : '' }}" id="association" role="tabpanel">
                        @if (request()->type == 'association')
                            @include('table.staff.association.index', ['associations' => $associations])
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
            $('#profession').html('');
            $('#role').html('');
            $.ajax({
                type: 'GET',
                url: "{{ route('staff-table.tab') }}",
                data: {
                    type: type
                },
                success: function(data) {
                    if (data.status == true) {
                        $('#' + type).html(data.data);
                    }
                }
            });
        });

        $(document).on('click', '.moveToArchive', function() {
            var msg = "{{ __('cluster.selectMsg') }}";
            var status = $('.status').val();
            var type = "{{request()->type}}"
            if(type == 'profession'){
                var model = "Profession";
            }
            if(type == 'role'){
                var model = "MemberRole";
            }
            if(type == 'association'){
                var model = "Association";
            }
            moveToArchive( msg, status , model);
        });
    </script>
@endpush
