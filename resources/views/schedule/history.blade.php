@extends('layout.master')
@push('customLink')
{{-- if need to add any cdn --}}
@endpush
@section('section')
<div class="container-fluid" style="margin-top: 130px;">
    <h3 class="text-start text-dark">History</h3>
    <div class=" table-container">
        <table class="table mb-0 table-bordered table-responsive">
            <thead class="table-header">
                <tr>
                    <th>Actions</th>
                    <th>Kindergarten</th>
                    <th><i class="fa fa-filter fs-6"></i> To</th>
                    <th><i class="fa fa-filter fs-6"></i> From</th>
                    <th><input type="checkbox" class="table-checkbox"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="d-flex gap-3 justify-content-center">
                            <i class="fa fa-clone icon"></i>
                            <i class="fa fa-eye icon"></i>
                        </div>

                    </td>
                    <td>15/06/2024</td>
                    <td>15/06/1999</td>
                    <td>First Grade</td>
                    <td><input type="checkbox" class="table-checkbox"></td>
                </tr>
                <!-- Repeat rows as necessary -->
            </tbody>
        </table>
    </div>
</div>
@endsection
@push('customScript')
{{-- if need to add any script --}}
@endpush