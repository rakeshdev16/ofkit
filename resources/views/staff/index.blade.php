@extends('layout.master')
@push('customLink')
    <link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
@endpush
@section('section')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="mb-4 page-info">
                <div>
                    <h3 class="mb-0 text-uppercase">{{ __('staff.staff') }} ({{ __('staff.admin') }})</h3>
                    <select name="" class="select-filter">
                        <option value="">Kindergarten Name</option>
                        <option value="">One</option>
                        <option value="">Two</option>
                    </select>
                </div>
                <div class="mt-2 buttons">
                    <a href="{{ route('staff.create') }}" class="btn button">{{ __('staff.addBtnText') }}</a>
                    <button class="btn button">{{ __('staff.editBtnText') }}</button>
                    <button class="btn button">{{ __('staff.moveBtnText') }}</button>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-style table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>{{ __('staff.nameTh') }}</th>
                                    <th>{{ __('staff.birthDateTh') }}</th>
                                    <th>{{ __('staff.addressTh') }}</th>
                                    <th>{{ __('staff.telephoneTh') }}</th>
                                    <th>{{ __('staff.emailTh') }}</th>
                                    <th>{{ __('staff.professionTh') }}</th>
                                    <th>{{ __('staff.licenceNumberTh') }}</th>
                                    <th>{{ __('staff.roleTh') }}</th>
                                    <th>{{ __('staff.kindergartenTh') }}</th>
                                    {{-- <th class="left-top"><input type="checkbox" class="" name="" id=""></th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 1; $i < 50; $i++)
                                    <tr>
                                        <td>Tiger Nixon {{ $i }}</td>
                                        <td>2011/04/25</td>
                                        <td>Chandigarh</td>
                                        <td>987456321{{ $i }}</td>
                                        <td>test@yopmail.com</td>
                                        <td>Therapist</td>
                                        <td>100-153</td>
                                        <td>Professional Therapist</td>
                                        <td>Hatsav</td>
                                        {{-- <td><input type="checkbox" class="" name="" id=""></td> --}}
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>

                    <div class="lising d-none">
                        @include('components.listing')
                        @include('components.pagination')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('customScript')
    <script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
    <script>
        $(document).ready(function() {
            var table = $('#example2').DataTable({
                lengthChange: false,
                buttons: ['copy', 'excel', 'pdf', 'print']
            });

            table.buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush
