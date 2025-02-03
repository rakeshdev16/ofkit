<style>
    table, td, th {
        border-spacing: 0;
        border: 1px solid black;
    }

    #collapseTable {
        border-collapse: collapse;
    }
</style>
<table id="separateTable" width="100%">
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
                <td style="text-align: center; width: 50px">{{ $timeSlot }}</td>
                @foreach ($days as $day)
                    @if (isset($staffSchedules[strtolower($day)]))
                        @foreach ($staffSchedules[strtolower($day)] as $user)
                            <th></th>
                        @endforeach
                    @endif
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>

{{-- <table>
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
</table> --}}
