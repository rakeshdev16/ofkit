<div class="mb-3">
    <select id="appointmentType" name="type" class="form-control border-1">
        <option value="">Choose Appointment</option>
        <option {{ @$data['type'] == 'individual' ? 'selected' : '' }} value="individual">Individual</option>
        <option {{ @$data['type'] == 'group' ? 'selected' : '' }} value="group">Group</option>
        <option {{ @$data['type'] == 'parental-guidance' ? 'selected' : '' }} value="parental-guidance">Parental Guidance</option>
        <option {{ @$data['type'] == 'staff-meeting' ? 'selected' : '' }} value="staff-meeting">Staff Meeting</option>
        <option {{ @$data['type'] == 'documentation-break' ? 'selected' : '' }} value="documentation-break">Documentation/break</option>
        <option {{ @$data['type'] == 'preparation' ? 'selected' : '' }} value="preparation">Preparation</option>
        <option {{ @$data['type'] == 'tutorial' ? 'selected' : '' }} value="tutorial">Tutorial</option>
        <option {{ @$data['type'] == 'other' ? 'selected' : '' }} value="other">Other</option>
    </select>
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
        <input type="text" name="start_time" class="form-control startTime event-time" id="" value="{{ @$data['startTime'] }}">
        {{-- @include('components.time-picker', ['name' => 'start_time', 'class' => 'startTime event-time', 'label' => 'Start time', 'value' => @$data['startTime']]) --}}
    </div>
    <div class="w-50">
        <input type="text" name="end_time" class="form-control endTime event-time" id="" value="{{ @$data['endTime'] }}">
        {{-- @include('components.time-picker', ['name' => 'end_time', 'class' => 'endTime event-time', 'label' => 'End time', 'value' => @$data['endTime']]) --}}
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
        'options' => @$data['therapists'],
        'value' => @$data['therapistIds']
    ])
</div>
<span class="therapists"></span>
<div id="otherFields" style="display: {{ in_array(@$data['type'], ['individual', 'group', 'parental-guidance', 'staff-meeting']) ? 'block' : 'none' }}">
    <div class="mb-3" id="groupName" style="display: {{ @$data['type'] == 'group' ? 'block' : 'none' }}">
        <input
            type="text"
            name="group_name"
            id="appointmentGroupName"
            class="w-100 form-control border-1"
            placeholder="Group Name"
            value="{{ @$data['groupName'] }}"
        >
    </div>
    <div class="mt-3">
        @include('components.multi-select-input', [
            'name' => "children_ids[]",
            'class' => 'selectChildrens',
            'id' => 'children',
            'icon' => 'buildings',
            'options' => @$data['childrens'],
            'value' => @$data['childrenId']
        ])
    </div>
    <span class="childrens mb-3"></span>
    <div class="my-3">
        <textarea class="form-control w-100" placeholder="Add Description" rows="5" name="description" id="description">{{ @$data['description'] }}</textarea>
    </div>
    <div class="mb-3">
        <input type="file" id="eventFile" name="image" class="form-control" onchange="selectedEventFile(this)">
        <input type="hidden" id="eventOldFile" name="old_image" class="form-control">
        <div class="event-file" style="display: {{ isset($data['file']) ? 'block' : 'none' }}">
            @if (isset($data['file']))
                <div class="document my-1">{{ explode("therapy-schedule/", $data['file'])[1] }}<i class="bx bx-x" onclick="removeEventFile()" data-file-name="" data-id=""></i></div>
            @endif
        </div>
    </div>
</div>
<input type="hidden" name="resource" id="resource" value="{{ @$data['resource'] }}">
<input type="hidden" name="unique_id" id="uniqueId" value="{{ @$data['uniqueId'] }}">
<input type="hidden" name="color" value="{{ @json_encode($data['color']) }}">
<input type="hidden" name="is_continue" id="isTimeOutSide" value="false">
<div class="d-flex gap-3">
    <button type="submit" class="button p-2 px-4 rounded-pill border-0" id="createEventModalBtn">Save</button>
    <button type="button" class="button p-2 px-4 rounded-pill border-0" id="cancelEventModalBtn">Cancel</button>
</div>
<script>    
    var type = $('#appointmentType').val();
    var isMultiple = (type === 'group' || type === 'staff-meeting');
    // $(document).on('change', '.startTime', function() {
    //     const startTimeSelect = document.querySelector('select[name="start_time"]');
    //     const endTimeSelect = document.querySelector('select[name="end_time"]');
    //     let startTime = startTimeSelect.value;
    //     endTimeSelect.value = '';
    //     Array.from(endTimeSelect.options).forEach((option) => {
    //         if (option.value && option.value <= startTime) {
    //             option.disabled = true;
    //         } else {
    //             option.disabled = false;
    //         }
    //     });
    // })

    $('.selectChildrens').select2({
        dropdownParent: $("#createEventModal"),
        placeholder: "Select Children",
        allowClear: true,
        maximumSelectionLength: !isMultiple ?? 1,
        language: {
            maximumSelected: function (args) {
                return "You can only select one children";
            }
        }
    });

    $('.selectTherapist').select2({
        dropdownParent: $("#createEventModal"),
        placeholder: "Select Therapist",
        allowClear: true,
        maximumSelectionLength: !isMultiple ?? 1,
        language: {
            maximumSelected: function (args) {
                return "You can only select one therapist";
            }
        }
    });

    $('.startTime').timepicker({
        timeFormat: 'HH:mm',
        interval: 60,
        minTime: '07:00',
        maxTime: '17:00',
        defaultTime: "{{ @$data['startTime'] }}" || "07:00",
        startTime: '07:00',
        dynamic: false,
        dropdown: true,
        scrollbar: true,
        zindex: 9999999,
        interval: 15,
        change: function(time) {
            if (time) {
                let selectedStartTime = $(this).val();
                let startTime = new Date("1970-01-01T" + selectedStartTime);
                let endTime = new Date(startTime.getTime() + 15 * 60000);
                let formattedEndTime = endTime.toTimeString().substring(0, 5);
                $('.endTime').timepicker('option', 'minTime', selectedStartTime);
                $('.endTime').val(formattedEndTime);
                $('#therapist, #children').val(null).trigger('change');
            }
        }
    });

    $('.endTime').timepicker({
        timeFormat: 'HH:mm',
        interval: 60,
        minTime: '07:00',
        maxTime: '17:00',
        defaultTime: "{{ @$data['endTime'] }}" || "07:15",
        startTime: '07:00',
        dynamic: false,
        dropdown: true,
        scrollbar: true,
        zindex: 9999999,
        interval: 15,
        change: function(time) {
            if (time) {
                $('#therapist, #children').val(null).trigger('change');
            }
        }
    });
</script>