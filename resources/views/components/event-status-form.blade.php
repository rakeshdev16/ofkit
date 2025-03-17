<div class="modal-header">
    <h5 class="modal-title form-heading" style="text-transform: capitalize;">{{ str_replace('-', ' ', $data['type']) }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form action="" enctype="multipart/form-data">
        <div class="d-flex mb-3">
            <div class="w-100">
                <select name="kindergarten_id" class="form-control border-1" id="kindergartenFilter">
                    @foreach (Auth::user()->staffKindergartens as $kindergarten)
                        @php
                            $kindergarten = $kindergarten->kindergartens;
                        @endphp
                        <option {{ @$data['day'] == 'Sunday' ? 'selected' : '' }} value="{{ $kindergarten['id'] }}<">{{ $kindergarten['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="d-flex mb-3">
            <div class="w-50">
                <select id="day" name="day" class="form-control border-1">
                    <option value="">Select Day</option>
                    <option {{ @$data['day'] == 'Sunday' ? 'selected' : '' }} value="Sunday">Sunday</option>
                    <option {{ @$data['day'] == 'Monday' ? 'selected' : '' }} value="Monday">Monday</option>
                    <option {{ @$data['day'] == 'Tuesday' ? 'selected' : '' }} value="Tuesday">Tuesday</option>
                    <option {{ @$data['day'] == 'Wednesday' ? 'selected' : '' }} value="Wednesday">Wednesday</option>
                    <option {{ @$data['day'] == 'Thursday' ? 'selected' : '' }} value="Thursday">Thursday</option>
                    <option {{ @$data['day'] == 'Friday' ? 'selected' : '' }} value="Friday">Friday</option>
                    <option {{ @$data['day'] == 'Saturday' ? 'selected' : '' }} value="Saturday">Saturday</option>
                </select>
            </div>
            <div class="w-50">
                @include('components.time-picker', ['name' => 'start_time', 'class' => 'startTime event-time', 'label' => 'Start time', 'value' => @$data['start_time']])
            </div>
            <div class="w-50">
                @include('components.time-picker', ['name' => 'end_time', 'class' => 'endTime event-time', 'label' => 'End time', 'value' => @$data['end_time']])
            </div>
        </div>
        <div class="mb-3">
            <select
                id="appointmentFrequency"
                name="frequency_repeat"
                class="form-control"
            >
                <option {{ @$data['frequencyRepeat'] == 'Weekly' ? 'selected' : '' }} value="Weekly">Weekly</option>
                <option {{ @$data['frequencyRepeat'] == 'Bi-weekly' ? 'selected' : '' }} value="Bi-weekly">Bi-weekly</option>
                <option {{ @$data['frequencyRepeat'] == 'Monthly' ? 'selected' : '' }} value="Monthly">Monthly</option>
            </select>
        </div>
        <div class="mb-3">
            <select
                id="Monthly"
                name="{{ @$data['frequencyRepeat'] == 'Monthly' ? 'frequency_repeat_at' : '' }}"
                class="form-control"
                style="display: {{ @$data['frequencyRepeat'] == 'Monthly' ? 'block' : 'none' }}"
            >
                <option {{ @$data['frequencyRepeatAt'] == 'Week 1' ? 'selected' : '' }} value="Week 1">{{ __('schedule.monthly1') }}</option>
                <option {{ @$data['frequencyRepeatAt'] == 'Week 2' ? 'selected' : '' }} value="Week 2">{{ __('schedule.monthly2') }}</option>
                <option {{ @$data['frequencyRepeatAt'] == 'Week 3' ? 'selected' : '' }} value="Week 3">{{ __('schedule.monthly3') }}</option>
                <option {{ @$data['frequencyRepeatAt'] == 'Week 4' ? 'selected' : '' }} value="Week 4">{{ __('schedule.monthly4') }}</option>
            </select>
        </div>
        <div class="mb-3">
            <select
                id="Bi-weekly"
                name="{{ @$data['frequencyRepeat'] == 'Bi-weekly' ? 'frequency_repeat_at' : '' }}"
                class="form-control"
                style="display: {{ @$data['frequencyRepeat'] == 'Bi-weekly' ? 'block' : 'none' }}"
            >
                <option {{ @$data['frequencyRepeatAt'] == 'Week 1' ? 'selected' : '' }} value="Week 1">{{ __('schedule.biweekly1') }}</option>
                <option {{ @$data['frequencyRepeatAt'] == 'Week 2' ? 'selected' : '' }} value="Week 2">{{ __('schedule.biweekly2') }}</option>
            </select>
        </div>
        <div class="mb-3">
            @include('components.multi-select-input', [
                'name' => "therapist_ids[]",
                'class' => 'selectTherapist',
                'id' => 'therapist',
                'icon' => 'buildings',
                'options' => @$data['allTherapists'],
                'value' => @$data['therapistIds'],
                'disabled' => !in_array(@$data['type'], ['group', 'staff-meeting']) ? 'disabled' : ''
            ])
        </div>
        <span class="therapists"></span>
        @if ($data['type'] == 'group' || $data['type'] == 'staff-meeting')
            <div class="mt-3">
                <strong>General Occurred?</strong>
                <div class="d-flex align-items-center gap-3 mt-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="general_occurred" id="generalOccurredYes">
                        <label class="form-check-label" for="generalOccurredYes">Yes</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="general_occurred" id="generalOccurredNo">
                        <label class="form-check-label" for="generalOccurredNo">No</label>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <div class="d-flex align-items-center gap-3 mt-2">
                    <select class="form-select w-100">
                        <option selected>Reason</option>
                        <option value="Child Absent">Child Absent</option>
                        <option value="Therapist Absent">Therapist Absent</option>
                        <option value="Kindergarten Closed">Kindergarten Closed</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>
            <div class="mt-3">
                <textarea class="form-control" rows="3" placeholder="Description Add"></textarea>
            </div>
        @endif
        @if (!in_array(@$data['type'], ['documentation-break', 'preparation', 'tutorial', 'other']))
            <div class="mt-3">
                @include('components.multi-select-input', [
                    'name' => "children_ids[]",
                    'class' => 'selectChildrens',
                    'id' => 'children',
                    'icon' => 'buildings',
                    'options' => @$data['allChildrens'],
                    'value' => @$data['childrenId'],
                    'disabled' => !in_array(@$data['type'], ['group', 'staff-meeting']) ? 'disabled' : ''
                ])
            </div>
            <span class="childrens mb-3"></span>
            <div class="mt-3">
                <strong>{{ $data['type'] == 'group' ? 'Participated' : 'Occurred' }}?</strong>
                <div class="d-flex align-items-center gap-3 mt-2">
                    <div class="form-check">
                        <input class="form-check-input" name="occurred" type="radio" id="occurredYes">
                        <label class="form-check-label" for="occurredYes">Yes</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" name="occurred" type="radio" id="occurredNo">
                        <label class="form-check-label" for="occurredNo">No</label>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <div class="d-flex align-items-center gap-3 mt-2">
                    <select class="form-select w-100">
                        <option selected>Reason</option>
                        <option value="Child Absent">Child Absent</option>
                        <option value="Therapist Absent">Therapist Absent</option>
                        <option value="Kindergarten Closed">Kindergarten Closed</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>
            <div class="mt-3">
                <textarea class="form-control" rows="3" placeholder="Description Add"></textarea>
            </div>
            <div class="mt-3 d-flex align-items-center">
                <input type="file" class="form-control" name="" id="">
            </div>
        @endif
        <div class=" mt-4">
            <button type="submit" class="btn button">Save</button>
            <button type="button" class="btn button me-2" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
        </div>
    </form>
</div>