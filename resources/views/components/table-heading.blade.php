<th
    class="{{ isset($key) ? 'sortTable' : '' }}"
    data-key="{{ @$key }}"
    data-value="{{ request()->sort == @$key ? request()->sorting : 'desc' }}"
    style="width: {{ @$width }}"
>
    <div class="d-flex justify-content-between">
        {{ $label }}
        @isset($key)
            <div class="th-sorting">
                <i class="bx bx-chevron-up {{ request()->sort == $key && request()->sorting == 'asc' ? 'disabled' : '' }}"></i><br>
                <i class="bx bx-chevron-down {{ request()->sort == $key && request()->sorting == 'desc' ? 'disabled' : '' }}"></i>
            </div>
        @endisset
    </div>
</th>