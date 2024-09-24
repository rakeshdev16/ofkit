@extends('layout.master')
@push('customLink')
@endpush
@section('section')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="mb-4 page-info">
                <div>
                    <h3 class="mb-0 text-uppercase">{{ __('children.childrenDocuments') }} </h3>
                    <div class="row my-2 mx-1">
                        <div class="col-md-6"><label for=""><b>{{ __('children.childName') }}:</b></label> {{ @$children->name.' '.$children->family_name }}</div>
                        <div class="col-md-6"><label for=""><b>{{ __('children.ID') }}:</b></label> {{ @$children->identification }}</div>
                        <div class="col-md-6"><label for=""><b>{{ __('children.kindergarten') }}:</b></label> {{ getKindergartenNameById(@$children->kindergarten_id) }}</div>
                        <div class="col-md-6"><label for=""><b>{{ __('children.childBirthday') }}:</b></label> {{ @$children->date_of_birth }}</div>
                        <div class="col-md-6"><label for=""><b>{{ __('children.childAge') }}:</b></label> {{ @$children->age }}</div>
                    </div>
                </div>
                <div class="mt-3">
                    @if (Auth::user()->hasRole('admin'))
                        <button class="btn button moveToArchive">{{ __('comon.moveToArchive') }}</button>
                    @endif
                    <button data-url="{{ route('children.show', Request::segment(2)) }}" class="btn button exit">{{ __('comon.back') }}</button>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-xl-3 col-lg-4 col-md-6  my-1">
                    <div class="dropdown dropdown-filter d-flex justify-content-between">
                        @php
                            if (request()->date && strpos(request()->date, ',') !== false) {
                                $date = explode(',', request()->date);
                                $date = date('d/m/Y', strtotime($date[1])) . ' - ' . date('d/m/Y', strtotime($date[0]));
                            } else {
                                $date = request()->date ? date('d/m/Y', strtotime(request()->date)) : __('children.selectDate');
                            }
                        @endphp
                        <button class="btn dropdown-toggle dropdown-filter-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ $date ? $date : __('children.selectDate') }}
                        </button>
                        <button class="btn btn-clear-filter" onclick="clearFilter('date')" type="button">x</button>
                        <ul class="dropdown-menu p-2 date-filters">
                            <li><a class="dropdown-item" onclick="dateFilter({{ $lastWeek }});" href="#">{{ __('children.lastWeek') }}</a></li>
                            <li><a class="dropdown-item" onclick="dateFilter({{ $month }});" href="#">{{ __('children.month') }}</a></li>
                            <li><a class="dropdown-item" onclick="dateFilter({{ $pastThreeMonth }});" href="#">{{ __('children.month3') }}</a></li>
                            <li><a class="dropdown-item" onclick="dateFilter({{ $pastSixMonth }});" href="#">{{ __('children.halfYear') }}</a></li>
                            <li>
                                <a class="dropdown-item specific-date-filter" href="#">{{ __('children.specificDate') }}</a>
                                <input type="date" name="date" class="form-control doc-filter specificDate" style="display: none">
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6  my-1">
                    <select class="form-control doc-filter" name="role">
                        <option value="">{{ __('children.selectProfession') }}</option>
                        @foreach ($roles as $role)
                            <option {{ request()->role == $role->name ? 'selected' : '' }} value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6  my-1">
                    <select class="form-control doc-filter" name="therapist_id">
                        <option value="">{{ __('children.selectTherapist') }}</option>
                        @foreach ($therapists as $therapist)
                            <option {{ request()->therapist_id == $therapist->id ? 'selected' : '' }} value="{{ $therapist->id }}">{{ $therapist->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6  my-1">
                    <select class="form-control doc-filter" name="type">
                        <option value="">{{ __('children.selectIntervention') }}</option>
                        <option {{ request()->type == 'individual' ? 'selected' : '' }} value="individual">{{ __('children.individual') }}</option>
                        <option {{ request()->type == 'group' ? 'selected' : '' }} value="group">{{ __('children.group') }}</option>
                        <option {{ request()->type == 'parental guidance' ? 'selected' : '' }} value="parental guidance">{{ __('children.parentalGuidance') }}</option>
                        <option {{ request()->type == 'staff-meeting' ? 'selected' : '' }} value="staff-meeting">{{ __('children.staffMeeting') }}</option>
                        <option {{ request()->type == 'initial-evaluation' ? 'selected' : '' }} value="initial-evaluation">{{ __('children.initialEvaluation') }}</option>
                        <option {{ request()->type == 'final-evaluation' ? 'selected' : '' }} value="final-evaluation">{{ __('children.finalEvaluation') }}</option>
                    </select>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive full-width-table">
                        @include('components.table-search', ['label' => __('children.childrenDocuments'), 'count' => @$documentationCount])
                        <div id="dataTable">
                            @include('children.document.documentation-table', ['documentations' => $documentations, 'children' => $children])
                        </div>
                    </div>
                    <div class="lising d-none">
                        @include('components.table-search', ['label' => __('children.childrenDocuments'), 'count' => $documentationCount])
                        <div id="accordion">
                            @include('children.document.documentation-accordion', ['documentations' => $documentations, 'children' => $children])
                        </div>
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
            var url = "{{ route('documents.delete', ':ids') }}";
            var msg = "{{ __('children.chooseAtLeastOneDoc') }}";
            moveToArchive(url, msg);
        });
    </script>
    <script src="{{ asset('assets/js/custom-datatable.js') }}"></script>
@endpush
