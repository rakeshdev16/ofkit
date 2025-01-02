<div class="mb-3">
    <select id="appointmentType" name="type" class="form-control border-1">
        <option value="">Choose Appointment</option>
        <option {{ @$data['type'] == 'individual' ? 'selected' : '' }} value="individual">Individual</option>
        <option {{ @$data['type'] == 'group' ? 'selected' : '' }} value="group">Group</option>
        <option {{ @$data['type'] == 'parental-guidance' ? 'selected' : '' }} value="parental-guidance">Parental Guidance</option>
        <option {{ @$data['type'] == 'staff-meeting' ? 'selected' : '' }} value="staff-meeting">Staff Meeting</option>
        <option {{ @$data['type'] == 'initial-evaluation' ? 'selected' : '' }} value="initial-evaluation">Initial Evaluation</option>
        <option {{ @$data['type'] == 'final-evaluation' ? 'selected' : '' }} value="final-evaluation">Final Evaluation</option>
    </select>
</div>
<div class="d-flex mb-3">
    <div class="w-100">
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
    <div>
        <input type="text" class="form-control" name="start_time" id="startTime" placeholder="Start Time" value="{{ @$data['startTime'] }}">
    </div>
    <div>
        <input type="text" class="form-control" name="end_time" id="endTime" placeholder="End Time" value="{{ @$data['endTime'] }}">
    </div>
</div>
<div class="mb-3">
    <select id="appointmentFrequency" name="frequency_repeat" class="form-control">
        <option value="">Select frequency</option>
        <option {{ @$data['frequencyRepeat'] == 'Weekly' ? 'selected' : '' }} value="Weekly">Weekly</option>
        <option {{ @$data['frequencyRepeat'] == 'Bi-weekly' ? 'selected' : '' }} value="Bi-weekly">Bi-weekly</option>
        <option {{ @$data['frequencyRepeat'] == 'Monthly' ? 'selected' : '' }} value="Monthly">Monthly</option>
    </select>
</div>
<div class="mb-3">
    <select id="Monthly" name="start" class="form-control" style="display: {{ @$data['frequencyRepeat'] == 'Monthly' ? 'block' : 'none' }}">
        <option {{ @$data['frequencyRepeatAt'] == 'Start Week' ? 'selected' : '' }} value="Start Week">Start Week</option>
        <option {{ @$data['frequencyRepeatAt'] == 'After 1 Week' ? 'selected' : '' }} value="After 1 Week">After 1 Week</option>
        <option {{ @$data['frequencyRepeatAt'] == 'After 2 Week' ? 'selected' : '' }} value="After 2 Week">After 2 Week</option>
        <option {{ @$data['frequencyRepeatAt'] == 'After 3 Week' ? 'selected' : '' }} value="After 3 Week">After 3 Week</option>
    </select>
</div>
<div class="mb-3">
    <select id="Bi-weekly" name="start" class="form-control" style="display: {{ @$data['frequencyRepeat'] == 'Bi-weekly' ? 'block' : 'none' }}">
        <option {{ @$data['frequencyRepeatAt'] == 'One Week Ofset' ? 'selected' : '' }} value="One Week Ofset">One Week Ofset</option>
        <option {{ @$data['frequencyRepeatAt'] == 'From Start Week' ? 'selected' : '' }} value="From Start Week">From Start Week</option>
    </select>
</div>
@if (@$data['type'] == 'group')
    <div class="mb-3">
        <input type="text" name="group_name" id="appointmentGroupName" class="w-100 form-control border-1" placeholder="Group Name" value="{{ @$data['groupName'] }}">
    </div>
@endif
<div class="mb-3">
    @include('components.multi-select-input', [
        'name' => "therapist_ids[]", 'class' => 'selectTherapist', 'id' => 'therapist', 'icon' => 'buildings', 'options' => @$data['therapists'], 'value' => @$data['therapistIds']
    ])
</div>
<span class="therapists mb-3"></span>
<div class="mb-3">
    @include('components.multi-select-input', [
        'name' => "children_ids[]", 'class' => 'selectChildrens', 'id' => 'children', 'icon' => 'buildings', 'options' => @$data['childrens'], 'value' => @$data['childrenId']
    ])
</div>
<span class="childrens mb-3"></span>
<div class="my-3">
    <textarea class="form-control w-100" placeholder="Add Description" rows="5" name="description" id="description">{{ @$data['description'] }}</textarea>
</div>
<div class="mb-3">
    <input type="file" id="eventFile" name="image" class="form-control">
    <input type="hidden" id="eventOldFile" name="old_image" class="form-control">
    <div class="event-file" style="display: none"></div>
</div>
<input type="hidden" name="resource" id="resource" value="{{ @$data['resource'] }}">
<input type="hidden" name="unique_id" id="uniqueId" value="{{ @$data['uniqueId'] }}">
<div class="d-flex gap-3">
    <button type="submit" class="button p-2 px-4 rounded-pill border-0" id="createEventModalBtn">Save</button>
    <button type="button" data-bs-dismiss="modal" class="button p-2 px-4 rounded-pill border-0">Cancel</button>
</div>
<script>    
    var type = $('#appointmentType').val();
    var isMultiple = (type === 'group' || type === 'staff-meeting');
    flatpickr("#startTime", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        minuteIncrement: 15
    });
    flatpickr("#endTime", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        minuteIncrement: 15
    });
    $('.selectChildrens').select2({
        dropdownParent: $("#createEventModal"),
        placeholder: "Select Children",
        multiple: isMultiple,
        allowClear: true
    });
    $('.selectTherapist').select2({
        dropdownParent: $("#createEventModal"),
        placeholder: "Select Therapist",
        multiple: isMultiple,
        allowClear: true
    });
</script>