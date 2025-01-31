<table>
    <thead>
        <tr>
            <th rowspan="2" style="vertical-align: middle; text-align: center; background-color: #095f59; color: #ffffff;">Time</th>
            @foreach ($days as $day)
                <th colspan="{{ isset($staffSchedules[strtolower($day)]) ? count($staffSchedules[strtolower($day)]) : 0 }}" style="text-align: center; background-color: #095f59; color: #ffffff;">{{ $day }}</th>
            @endforeach
        </tr>
        <tr>
            @foreach ($days as $day)
                @if (isset($staffSchedules[strtolower($day)]))
                    @foreach ($staffSchedules[strtolower($day)] as $user)
                        <th style="text-align: center; background-color: #095f59; color: #ffffff;">{{ $user->user->name }}</th>
                    @endforeach
                @endif
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($timeSlots as $timeSlot)
            <tr>
                <td>{{ $timeSlot }}</td>
                @foreach ($days as $day)
                    <td>
                        @if (isset($events[$day]))
                            @for ($i = 0; $i < count($events[$day]); $i++)
                                {{ $events[$day][$i]['type'] ?? '-' }}
                            @endfor
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
