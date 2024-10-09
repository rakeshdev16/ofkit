<div class="dropdown dropdown-filter d-flex justify-content-between">
    @php
        if ($date && strpos($date, ',') !== false) {
            $date = explode(',', $date);
            $date = date('d/m/Y', strtotime($date[1])) . ' - ' . date('d/m/Y', strtotime($date[0]));
        } else {
            $date = $date ? date('d/m/Y', strtotime($date)) : __('children.selectDate');
        }
    @endphp
    <button class="btn dropdown-toggle dropdown-filter-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        {{ $date ? $date : __('children.selectDate') }}
    </button>
    <button class="btn btn-clear-filter" onclick="clearFilter('date')" type="button">x</button>
    <ul class="dropdown-menu p-2 date-filters">
        <li><a class="dropdown-item" onclick="dateFilter({{ filterDate()['lastWeek'] }});" href="#">{{ __('children.lastWeek') }}</a></li>
        <li><a class="dropdown-item" onclick="dateFilter({{ filterDate()['month'] }});" href="#">{{ __('children.month') }}</a></li>
        <li><a class="dropdown-item" onclick="dateFilter({{ filterDate()['pastThreeMonth'] }});" href="#">{{ __('children.month3') }}</a></li>
        <li><a class="dropdown-item" onclick="dateFilter({{ filterDate()['pastSixMonth'] }});" href="#">{{ __('children.halfYear') }}</a></li>
        <li>
            <a class="dropdown-item specific-date-filter" href="#">{{ __('children.specificDate') }}</a>
            <input type="date" name="date" class="form-control doc-filter specificDate" style="display: none">
        </li>
        <li>
            <a class="dropdown-item specific-date-range-filter" href="#">{{ __('children.dateRange') }}</a>
            <input type="text" name="date" class="form-control doc-filter dateRangePicker" placeholder="{{ __('children.selectDateRange') }}" style="display: none">
        </li>
    </ul>
</div>
