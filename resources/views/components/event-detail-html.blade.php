<div style="word-wrap: break-word; white-space: normal; direction: rtl; text-align: right;">
    <div class="row mb-2 fs-6 text-dark">
        <div class="col-md-1"><i class="fa fa-info fa-lg" style="margin-right: 5px"></i></div>
        <div class="col-md-9"><div>{{ ucfirst(str_replace('-', ' ', $data->type)) }}</div></div>
        <div class="col-md-2">
            @if ((request('status') == 'draft' || request('schedule_id') !== null))
                <div class="d-flex gap-2 justify-content-end">
                    <i class="fa fa-edit" onclick="window.handleAction('edit', {{ $data }})" style="cursor: pointer;"></i>
                    <i class="fa fa-trash" onclick="window.handleAction('delete', '{{ $data['unique_id']}}')" style="cursor: pointer;"></i>
                </div>
            @endif
        </div>
    </div>
    @if (in_array($data->type, ['individual', 'group', 'staff-meeting', 'parental-guidance']))
        <div class="row mb-2 fs-6 text-dark">
            <div class="col-md-1"><i class="fa fa-${event.icon}"></i></i></div>
            <div class="col-md-11"><div>{{ getChildrenNamesById($data->childrens->pluck('children_id')->toArray()) }}</div></div>
        </div>
    @endif
    <div class="row mb-2 fs-6 text-dark">
        <div class="col-md-1"><i class="fa fa-calendar"></i></div>
        <div class="col-md-11">{{ date('H:i', strtotime($data->start_time)) }} - {{ date('H:i', strtotime($data->end_time)) }}</div>
    </div>
    <div class="row mb-2 fs-6 text-dark">
        <div class="col-md-1"><i class="fa fa-clock-o"></i></div>
        <div class="col-md-11">{{ $data->frequency_repeat }} {{ $data->frequency_repeat_at}}</div>
    </div>
    <div class="row mb-2 fs-6 text-dark">
        <div class="col-md-1"><i class="fa fa-users"></i></div>
        <div class="col-md-11">{{ getUserNameByIds($data->where('unique_id', $data->unique_id)->pluck('therapist_id')->toArray())}}</div>
    </div>
    <div class="row mb-2 fs-6 text-dark">
        <div class="col-md-1"><i class="fa fa-align-justify"></i></div>
        <div class="col-md-11">{{ $data->description }}</div>
    </div>
</div>