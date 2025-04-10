@push('customLink')
<style>
    html[dir="rtl"] .page-wrapper {
        direction: rtl;
    }
    .custom-scroll-wrapper {
        overflow-x: auto;
    }
    /* Set min-width for each column (except time column) */
    table th:not(.time-col),
    table td:not(:first-child) {
        min-width: 160px;
    }
    table {
      table-layout: fixed;
      width: 100%;
      direction: rtl;
    }
    th, td {
        text-align: center;
        vertical-align: top;
        border: 1px solid #dee2e6;
        padding: 2px; /* reduce padding */
        height: 16px; /* or min-height: 16px; */
    }
    .time-col {
      width: 60px;
      background-color: #f8f9fa;
    }
    .header-cell {
        background-color: #005a5a !important;
        color: white !important;
        text-align: center !important;
        vertical-align: top;
        padding: 0 !important;
        min-width: 100px;
    }
    .child-cell {
        background-color: #006b6b;
        border-color: #004d4d;
        color: #fff;
        font-size: 0.85rem;
        text-align: center;
    }
    .event {
      padding: 4px;
      border-radius: 4px;
      margin: 2px 0;
    }
    .other { background-color: #d19700; }
    .section { background-color: #f4a5a5; }
    .prep { background-color: #9871d0; }
    .kit { background-color: #d6b9aa; color: #000; }
    .multi { background-color: #b58fb3; }
    /* RTL Icon adjustment */
    .icon {
      font-size: 0.9em;
      margin-left: 3px; /* switched from margin-right for RTL */
    }
    /* For child names shown inline */
    .child-inline-wrapper {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 4px;
        margin-top: 4px;
        max-width: 100%;
        border-top: 1px solid #fff;
        border-top: 1px solid #fff;
    }
    .child-badge {
        background-color: #006b6b;
        color: #fff;
        padding: 2px 6px;
        font-size: 0.75rem;
        border-radius: 4px;
        white-space: nowrap;
    }

    @media print {
        body {
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            word-break: break-word;
            padding: 4px;
        }

        .wrapper {
            width: 100%;
        }
    }
</style>
@endpush
<div class="table-responsive custom-scroll-wrapper">
    @php
        $times = ['07:00', '07:15', '07:30', '07:45','08:00', '08:15', '08:30', '08:45','09:00', '09:15', '09:30', '09:45','10:00', '10:15', '10:30', '10:45','11:00', '11:15', '11:30', '11:45','12:00', '12:15', '12:30', '12:45','13:00', '13:15', '13:30', '13:45','14:00', '14:15', '14:30', '14:45','15:00', '15:15', '15:30', '15:45','16:00', '16:15', '16:30', '16:45','17:00', '17:15', '17:30', '17:45'];
    @endphp
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="time-col header-cell"><span>Time</span></th>
                @foreach ($data['calenderHeader'] as $header)
                    <th
                        class="header-cell"
                        width="{{ count($header['children']) + 1 }}00"
                        colspan="{{ count($header['children']) == 0 ? 1 : count($header['children']) }}"
                    >
                        <div class="fw-bold p-1">{{ $header['name'] }}</div>
                        <div class="child-inline-wrapper mt-1">
                                @foreach ($header['children'] as $child)
                                <div class="p-1" style="min-width: 100px">
                                    <span>{{ $child['name'] }}</span> <hr style="margin: 2px">
                                    <span>{{ $child['profession'] }}</span> <hr style="margin: 2px">
                                    <span>{{ $child['association'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($times as $time)
                <tr>
                    <td style="padding: 5px;">{{ $time }}</td>
                    @foreach ($data['calenderHeader'] as $header)
                        @if ($header['children'])
                            @foreach ($header['children'] as $child)
                                @php
                                    $cellEvents = collect($data['calenderEvents'])
                                        ->filter(function ($event) use ($time, $child, $header) {
                                            $scheduleEvent = $event['data'] ?? null;
                                            return $scheduleEvent &&
                                                ($scheduleEvent->start_time === $time . ':00') &&
                                                in_array($child['user_id'], $scheduleEvent->therapistIds ?? []) &&
                                                ($header['name'] === $scheduleEvent->day);
                                        })
                                        ->pipe(function ($collection) {
                                            $groups = $collection->filter(function ($event) {
                                                return isset($event['data']) && $event['data']->type === 'group';
                                            })->unique(fn($event) => $event['data']->unique_id ?? null);
                                            $others = $collection->reject(function ($event) {
                                                return isset($event['data']) && $event['data']->type === 'group';
                                            });
                                            return $groups->merge($others);
                                        });
                                    $rowSpan = 1;
                                    foreach ($cellEvents as $event) {
                                        $rowSpan = $event['data']->event_time / 15;
                                    }
                                @endphp
                                <td style="padding: 0; height: {{ 4*$rowSpan }}px" rowspan="{{ $rowSpan }}">
                                    @if ($cellEvents->isNotEmpty())
                                        <div class="d-flex" style="height: 100%;">
                                            @foreach ($cellEvents as $event)
                                                @php $schedule = $event['data']; @endphp
                                                <div style="width: {{ 100/count($cellEvents) }}%; border: 1px solid #ffff">
                                                    {!! $event['eventSlotHtml'] !!}
                                                    @if ($rowSpan > 1)
                                                        <br/>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        @else
                            <td></td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>