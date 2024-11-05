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


<div class="pull-right" style="background: #fff; cursor: pointer; padding: 0px 10px; border: 1px solid #cccccc7d; width: 100%; display: grid; grid-template-columns: 1fr auto; align-items: center; height: 37px;">
    <div id="reportrange">
        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
        <span></span> <b class="caret"></b>
    </div>
    <button id="clearFilters" class="btn btn-clear-filter">X</button>
</div>