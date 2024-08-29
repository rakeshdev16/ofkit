@extends('layout.master')
@push('customLink')
    
@endpush
@section('section')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="mb-4 page-info">
                <div>
                    <h3 class="mb-0 text-uppercase">Children Documents ({{ __('comon.'.Auth::user()->getRoleNames()->first()) }})</h3>
                    <div class="row my-2 mx-1">
                        <div class="col-md-6"><label for=""><b>Child Name:</b></label> {{ @$children->name }}</div>
                        <div class="col-md-6"><label for=""><b>I.D:</b></label> {{ @$children->identification }}</div>
                        <div class="col-md-6"><label for=""><b>Kindergarten:</b></label> {{ getKindergartenNameById(@$children->kindergarten_id) }}</div>
                        <div class="col-md-6"><label for=""><b>Child's Birthday:</b></label> {{ @$children->date_of_birth }}</div>
                        <div class="col-md-6"><label for=""><b>Child's Age:</b></label> {{ @$children->age }}</div>
                    </div>
                </div>
                <div class="mt-3">
                    <button data-url="{{ route('children.show', Request::segment(2)) }}" class="btn button exit">{{ __('comon.back') }}</button>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-md-2 my-1">
                    {{-- <label>Select Date</label> --}}
                    {{-- <input type="date" name="date" value="{{ request()->date }}" class="form-control doc-filter"> --}}
                    <div class="dropdown dropdown-filter">
                        @php
                            if (strpos(request()->date, ',') !== false) {
                                $date = explode(',', request()->date);
                                $date = date('d/m/Y', strtotime($date[1])).' - '.date('d/m/Y', strtotime($date[0]));
                            } else {
                                $date = date('d/m/Y', strtotime(request()->date));
                            }
                        @endphp
                        <button class="btn dropdown-toggle dropdown-filter-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">{{ $date ?? 'Select Date' }}</button>
                        <ul class="dropdown-menu p-2 date-filters" style="">
                            <li><a class="dropdown-item" onclick="dateFilter({{ $lastWeek }}, 'Last Week');" href="#">Last Week</a></li>
                            <li><a class="dropdown-item" onclick="dateFilter({{ $month }}, 'Month');" href="#">Month</a></li>
                            <li><a class="dropdown-item" onclick="dateFilter({{ $pastThreeMonth }}, 'Month 3');" href="#">Month 3</a></li>
                            <li><a class="dropdown-item" onclick="dateFilter({{ $pastSixMonth }}, 'Half a Year');" href="#">Half a Year</a></li>
                            <li>
                                <a class="dropdown-item specific-date-filter" href="#">Specific Date​</a>
                                <input type="date" name="date" class="form-control doc-filter specificDate" style="display: none">
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-2 my-1">
                    {{-- <label>Select Profession</label> --}}
                    <select class="form-control doc-filter" name="role">
                        <option value="">Select Profession</option>
                        @foreach ($roles as $role)
                            <option {{ request()->role == $role->name ? 'selected' : '' }} value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 my-1">
                    {{-- <label>Select Therapist</label> --}}
                    <select class="form-control doc-filter" name="therapist_id">
                        <option value="">Select Therapist</option>
                        @foreach ($therapists as $therapist)
                            <option {{ request()->therapist_id == $therapist->id  ? 'selected' : '' }} value="{{ $therapist->id }}">{{ $therapist->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 my-1">
                    {{-- <label>Select Intervention</label> --}}
                    <select class="form-control doc-filter" name="type">
                        <option value="">Select Intervention</option>
                        <option {{ request()->type == 'individual'  ? 'selected' : '' }} value="individual">Individual</option>
                        <option {{ request()->type == 'initial-evaluation'  ? 'selected' : '' }} value="initial-evaluation">Initial evaluation</option>
                        <option {{ request()->type == 'group'  ? 'selected' : '' }} value="group">group</option>
                        <option {{ request()->type == 'staff-meeting'  ? 'selected' : '' }} value="staff-meeting">Staff meeting</option>
                        <option {{ request()->type == 'parental guidance'  ? 'selected' : '' }} value="parental guidance">Parental Guidance</option>
                        <option {{ request()->type == 'final-evaluation'  ? 'selected' : '' }} value="final-evaluation">Final Evaluation</option>
                    </select>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive full-width-table">
                        @include('components.table-search', ['label' => "Children Documents", 'count' => @$documentationCount])
                        <div id="dataTable">
                            @include('children.document.documentation-table', ['documentations' => $documentations, 'children' => $children])
                        </div>
                    </div>
                    <div class="lising d-none" id="accordion">
                        @include('children.document.documentation-accordion', ['documentations' => $documentations, 'children' => $children])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('customScript')
<script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.specific-date-filter').on('click', function(e) {
            e.stopPropagation();
            e.preventDefault();
            $('.specificDate').toggle();
        });
        
        $('.specificDate').on('change', function(e) {
            $('.dropdown-filter-toggle').html(dateFormat($(this).val()));
        });

    });

    $(document).on('click', '.button', function() {
        $(this).attr('disabled', false);
    });
    $(document).on('click', '.moveToArchive', function() {
        var url = "{{ route('children.destroy', ':ids') }}";
        var msg = "{{ __('children.chooseAtLeastOne') }}";
        moveToArchive(url, msg);
    });
</script>
<script src="{{ asset('assets/js/custom-datatable.js') }}"></script>
@endpush
