<tr class="{{ $day }} {{ $day }}-tr-{{ $id }}" data-index="{{ $index }}">
    <td>
        <h6 class="pt-2">{{ $name }}</h6>
        <input type="hidden" name="schedule[{{ $day }}][{{ $index }}][kindergarten_id]" value="{{ $id }}">
        <input type="hidden" name="schedule[{{ $day }}][{{ $index }}][id]" value="{{ @$data['id'] }}">
    </td>
    <td>
        @include('components.time-picker', [
            'name' => "schedule[$day][$index][start_time]",
            'label' => 'Start time',
            'class' => 'time-picker startTime',
            'value' => @$data['start_time'],
            'index' => $index,
        ])
        {{-- <input type="text" name="schedule[{{ $day }}][{{ $index }}][start_time]" class="form-control time-picker startTime" value="{{ @$data['start_time'] }}" data-index="{{ $index }}"> --}}
        <span id="schedule[{{ $day }}][{{ $index }}][start_time]"></span>
    </td>
    <td>
        @include('components.time-picker', [
            'name' => "schedule[$day][$index][end_time]",
            'label' => 'End time',
            'class' => "time-picker endTime$index",
            'value' => @$data['end_time'],
            'index' => $index
        ])
        {{-- <input type="text" name="schedule[{{ $day }}][{{ $index }}][end_time]" class="form-control time-picker endTime{{ $index }}" value="{{ @$data['end_time'] }}" data-index="{{ $index }}"> --}}
        <span id="schedule[{{ $day }}][{{ $index }}][end_time]"></span>
    </td>
</tr>
