@extends('layout.master')
@push('customLink')
    <link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
@endpush
@section('section')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="mb-4 d-flex justify-content-between">
                <div>
                    <h3 class="mb-0 text-uppercase">Staff (Admin)</h3>
                    <select name="" class="select-filter">
                        <option value="">Kindergarten Name</option>
                        <option value="">One</option>
                        <option value="">Two</option>
                    </select>
                </div>
                <div class="mt-5 pt-2">
                    <a href="{{ route('staff.create') }}" class="btn button">Add New</a>
                    <button class="btn button">Edit</button>
                    <button class="btn button">Move to Archive</button>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-style table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Birth Date</th>
                                    <th>Address</th>
                                    <th>Telephone</th>
                                    <th>Email</th>
                                    <th>Profession</th>
                                    <th>Licence Number</th>
                                    <th>Role</th>
                                    <th>Kindergarten</th>
                                    {{-- <th class="left-top"><input type="checkbox" class="" name="" id=""></th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 1; $i < 50; $i++)
                                    <tr>
                                        <td>Tiger Nixon {{$i}}</td>
                                        <td>2011/04/25</td>
                                        <td>Chandigarh</td>
                                        <td>987456321{{$i}}</td>
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
		  } );
	</script>
	<script>
		$(document).ready(function() {
			var table = $('#example2').DataTable( {
				lengthChange: false,
				buttons: [ 'copy', 'excel', 'pdf', 'print']
			} );
		 
			table.buttons().container().appendTo( '#example2_wrapper .col-md-6:eq(0)' );
		} );
	</script>
@endpush
