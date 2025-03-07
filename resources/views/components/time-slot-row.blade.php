<tr class="{{ $key }}{{ @$id }}">
    @php
        $index = isset($day) ? $loop->index : 0;
        $route = Route::currentRouteName();
    @endphp
    @if ($route !== 'staff.show')
        @if (isset($loopIndex) && $loopIndex > 0)
        <td style="background: #80808000">
            <button
                type="button"
                class="btn btn-danger"
                onclick="removeDay(this);"
                data-id="{{ $key.$index }}"
            >
                <i class="fa fa-minus"></i>
            </button>
        </td>
        @else
            <td style="background: #80808000">
                <button
                    type="button"
                    class="btn button"
                    onclick="addMoreTime(this, '{{ $key }}');"
                    data-id="{{$id}}"
                    data-key="{{$id}}{{ $index }}"
                >
                    <i class="fa fa-plus"></i>
                </button>
            </td>
        @endif
    @endif
    <td style="background: #80808000">{{ ucfirst($key) }}</td>
    <td class="w-50" style="background: #80808000">
        <input
            type="text"
            class="form-control timepicker"
            name="schedule[{{ $id }}][{{ $key }}][{{$index}}][start_time]"
            data-index="{{ $id.$key.$index }}"
            data-user-id="{{ @$data['user_id'] }}"
            data-key="0"
            data-day="{{ $key }}"
            value="{{ @$day->start_time }}"
            placeholder="Enter Start Time"
            autocomplete="off"
            {{ $route == 'staff.show' ? 'disabled' : '' }}
        >
    </td>
    <td class="w-50" style="background: #80808000">
        <input
            type="text"
            class="form-control end-timepicker {{ $key }}{{$id}}"
            name="schedule[{{ $id }}][{{ $key }}][{{$index}}][end_time]"
            data-index="{{ $id.$key.$index }}"
            data-key="0"
            value="{{ @$day->end_time }}"
            data-day="{{ $key }}"
            placeholder="Enter End Time"
            autocomplete="off"
            disabled
        >
        <input type="hidden" name="schedule[{{ $id }}][{{ $key }}][{{ $index }}][id]" value="{{ @$day->id }}">
    </td>
</tr>