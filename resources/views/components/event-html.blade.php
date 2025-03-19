 <div class="p-1 event-box d-flex flex-column justify-content-between" style="{{ @$data->color[0] }}; {{ @$data->color[1] }}">
    @if ($data['eventCount'] >= 2)
        @php
            $id = $data->id;
            $lastId = $data->last_id;
        @endphp
        <div class="position-absolute {{ $lastId == $id ? 'last-event' : 'old-events' }}">
            <span class="event-icon" style="display: block; font-size: 14px;">
                <i class="fa fa-{{ appointmentIcon($data->type) }}"></i>
            </span>
            <span class="event-time" style="display: block; font-size: 8px; margin-top: 4px;">
                {{ date('H:i', strtotime($data->start_time)) }}
            </span>
        </div>
    @else
        <div class="d-flex justify-content-between">
            <span>{{ date('H:i', strtotime($data->start_time)) }}</span>
            <span><i class="fa fa-{{ appointmentIcon($data->type) }}"></i></span>
        </div>
    @endif
    @if (Route::currentRouteName() == 'documentation.calendar')
        @php
            $color = 'red';
            switch (@$data['type']) {
                case 'individual':
                    $color = 'red';
                break;
                case 'group':
                    $color = 'gray';
                break;
                case 'parental-guidance':
                    $color = 'black';
                break;
                case 'staff-meeting':
                    $color = 'red';
                break;
                case 'documentation-break':
                    $color = 'gray';
                break;
                case 'preparation':
                    $color = 'black';
                break;
            }
        @endphp
        <div style="text-align: left; height:5px;"><span class="event-status" style="background: {{ $color }}"></span></div>
    @endif
    @if ($data->event_time !== 15)
        @if ($data['eventCount'] <= 1)
            <div class="d-flex align-items-center justify-content-center h-100" style="font-size: 12px; text-align: center;">
                {!! $data->cell_title !!}
            </div>
        @endif
        @if ((request('mode') && request('mode') == 'create') && $data['eventCount'] <= 2 && $data->event_time != 30)
            <div class="d-flex justify-content-start mt-auto" style="position: relative; bottom: 0;">
                <i class="fa fa-edit" onclick='event.stopPropagation(); editEvent({{$data}})'></i>&nbsp;
                <i class="fa fa-trash" onclick="event.stopPropagation(); deleteEvent('{{ json_encode(['schedule_id' => $data['schedule_id'], 'unique_id' => $data['unique_id']]) }}')"></i>
            </div>
        @endif
    @endif
</div>