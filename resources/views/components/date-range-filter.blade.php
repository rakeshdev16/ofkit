{{-- <div class="dropdown dropdown-filter d-flex justify-content-between">
    @php
        if ($date && strpos($date, ',') !== false) {
            $date = explode(',', $date);
            $date = $date[1] . ' - ' . $date[0];
        } else {
            $date = $date ? $date : __('children.selectDate');
        }
    @endphp
    <button class="btn dropdown-toggle dropdown-filter-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        {{ $date ? $date : __('children.selectDate') }}
    </button>
    <button class="btn btn-clear-filter" onclick="clearFilter('date')" type="button">x</button>
    <ul class="dropdown-menu p-2 date-filters">
        <li><a class="dropdown-item this-filter {{ request('dateType') == 'lastWeek' ? 'active-filter' : '' }}" onclick="dateFilter({{ filterDate()['lastWeek'] }}, 'lastWeek');" href="#">{{ __('children.lastWeek') }}</a></li>
        <li><a class="dropdown-item this-filter {{ request('dateType') == 'month' ? 'active-filter' : '' }}" onclick="dateFilter({{ filterDate()['month'] }}, 'month');" href="#">{{ __('children.month') }}</a></li>
        <li><a class="dropdown-item this-filter {{ request('dateType') == 'pastThreeMonth' ? 'active-filter' : '' }}" onclick="dateFilter({{ filterDate()['pastThreeMonth'] }}, 'month3');" href="#">{{ __('children.month3') }}</a></li>
        <li><a class="dropdown-item this-filter {{ request('dateType') == 'pastSixMonth' ? 'active-filter' : '' }}" onclick="dateFilter({{ filterDate()['pastSixMonth'] }}, 'halfYear');" href="#">{{ __('children.halfYear') }}</a></li>
        <li>
            <a class="dropdown-item this-filter {{ request('dateType') == 'specificDate' ? 'active-filter' : '' }} specific-date-filter" href="#">{{ __('children.specificDate') }}</a>
            <input type="date" name="date" class="form-control doc-filter specificDate" value="{{ request('dateType') == 'specificDate' ? date('Y-m-d', strtotime($date)) : '' }}" data-type="specificDate" style="display: none">
        </li>
        <li>
            <a class="dropdown-item this-filter {{ request('dateType') == 'dateRange' ? 'active-filter' : '' }} specific-date-range-filter" href="#">{{ __('children.dateRange') }}</a>
            <input type="text" name="date" class="form-control doc-filter dateRangePicker" placeholder="{{ __('children.selectDateRange') }}" data-type="dateRange" style="display: none">
        </li>
    </ul>
</div> --}}


{{-- <div class="pull-right" style="background: #fff; cursor: pointer; padding: 0px 10px; border: 1px solid #cccccc7d; width: 100%; display: grid; grid-template-columns: 1fr auto; align-items: center; height: 37px;">
    <div id="reportrange">
        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
        <span></span> <b class="caret"></b>
    </div>
    <button id="clearFilters" class="btn btn-clear-filter">X</button>
</div> --}}

<div class="dropdown filter-date-dropdown position-relative">
    <button class="btn dropdown-toggle show w-100 text-start bg-white text-dark" id="filter-date-dropdown-text" type="button" data-bs-toggle="dropdown" data-bs-auto-close="inside" aria-expanded="false">{{request('dateType') ?? 'Select Date'}}</button>
    <ul class="dropdown-menu w-100" data-popper-placement="bottom-end" style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(0px, 40px);">
        <li><a class="dropdown-item this-filter {{ request('dateType') == 'lastWeek' ? 'active-filter' : '' }}" onclick="dateFilter({{ filterDate()['lastWeek'] }}, 'lastWeek');" href="#">{{ __('children.lastWeek') }}</a></li>
        <li><a class="dropdown-item this-filter {{ request('dateType') == 'month' ? 'active-filter' : '' }}" onclick="dateFilter({{ filterDate()['month'] }}, 'month');" href="#">{{ __('children.month') }}</a></li>
        <li><a class="dropdown-item this-filter {{ request('dateType') == 'pastThreeMonth' ? 'active-filter' : '' }}" onclick="dateFilter({{ filterDate()['pastThreeMonth'] }}, 'month3');" href="#">{{ __('children.month3') }}</a></li>
        <li><a class="dropdown-item this-filter {{ request('dateType') == 'pastSixMonth' ? 'active-filter' : '' }}" onclick="dateFilter({{ filterDate()['pastSixMonth'] }}, 'halfYear');" href="#">{{ __('children.halfYear') }}</a></li>
        <li class="px-4">
            <div class="mt-3">
                <h5>Custom range</h5>
                <input type="text" class="form-control filterDateRange mt-2" id="startDate" placeholder="From yyyy/mm/dd" />
                <input type="text" class="form-control filterDateRange mt-2" id="endDate" placeholder="To yyyy/mm/dd" />
                <button class="btn custom-date-range btn-primary mt-3" onclick="dateFilter([$('#startDate').val(), $('#endDate').val()], 'dateRange')">Display</button>
            </div>
        </li>
    </ul>
    <button id="clearFilters" class="btn btn-clear-filter position-absolute end-0">X</button>
</div>

@push('customScript')
    <script>
        $(".filter-date-dropdown li a").click(function(){
            $("#filter-date-dropdown-text").text($(this).text());
        });
        $(".filter-date-dropdown li a").click(function(){
            $("#filter-date-dropdown-text").text($(this).text());
        });
        $(".custom-date-range").click(function(){
            let start = $('#startDate').val();
            let end = $('#endDate').val();
            $("#filter-date-dropdown-text").text(start + ' - ' + end);
        });
        $("#clearFilters").click(function(){
            $("#filter-date-dropdown-text").text('Select Date');
            var url = queryParam('dateType', '');
            url = queryParam('date', '', url);
            filter(url);
        });
    </script>
@endpush
