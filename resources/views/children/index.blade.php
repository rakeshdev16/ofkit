@extends('layout.master')
@push('customLink')
    <link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
@endpush
@section('section')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="d-flex justify-content-between">
                <h6 class="mb-0 text-uppercase">CHILDRENS</h6>
                <div>
                    <a href="{{ route('children.create') }}" class="btn button">Add New</a>
                    <button class="btn button">Edit</button>
                    <button class="btn button">Move to Archive</button>
                </div>
            </div>
            <hr />
            {{-- <table class="table table-style">
                <thead>
                    <tr>
                        <th>Access Records</th>
                        <th>Kindergarten</th>
                        <th>Address</th>
                        <th>Date of Birth</th>
                        <th>I.D.</th>
                        <th>Family Name</th>
                        <th>First Name</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 1; $i < 8; $i++)
                        <tr>
                            <td>System Architect {{$i}}</td>
                            <td>Edinburgh {{$i}}</td>
                            <td>Chandigarh</td>
                            <td>2011/04/25</td>
                            <td>${{100*$i}}</td>
                            <td>Tiger {{$i}}</td>
                            <td>Tiger Nixon {{$i}}</td>
                        </tr>
                    @endfor
                </tbody>
            </table> --}}
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-style table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Office</th>
                                    <th>Age</th>
                                    <th>Start date</th>
                                    <th>Salary</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 1; $i < 50; $i++)
                                    <tr>
                                        <td>Tiger Nixon {{$i}}</td>
                                        <td>System Architect {{$i}}</td>
                                        <td>Edinburgh {{$i}}</td>
                                        <td>{{20+$i}}</td>
                                        <td>2011/04/25</td>
                                        <td>${{100*$i}}</td>
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
