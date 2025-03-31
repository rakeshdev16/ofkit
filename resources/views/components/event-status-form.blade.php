@php
    $isDisbale = isset($data['form_type']) && $data['form_type'] == 'edit' ? 'disbaled' : '';
@endphp
<div class="modal-header">
    <h5 class="modal-title form-heading" style="text-transform: capitalize;">{{ isset($data['type']) ? str_replace('-', ' ', $data['type']) : '' }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form method="POST" id="eventForm" enctype="multipart/form-data">
        @csrf
        <div class="d-flex mb-3">
            <div class="w-100">
                <select name="kindergarten_id" class="form-control border-1 {{ $isDisbale }}" id="kindergartenFilter">
                    @foreach (Auth::user()->staffKindergartens as $kindergarten)
                        @php
                            $kindergarten = $kindergarten->kindergartens;
                        @endphp
                        <option
                            {{ @$data['kindergarten_id'] == $kindergarten['id'] ? 'selected' : '' }}
                            value="{{ $kindergarten['id'] }}"
                        >
                            {{ $kindergarten['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="d-flex mb-3 gap-1">
            <div class="w-50">
                <select name="day" class="form-control border-1 day {{ $isDisbale }}">
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
                @include('components.time-picker', [
                    'name' => 'start_time',
                    'class' => "startTime event-time $isDisbale",
                    'label' => 'Start time',
                    'value' => @$data['start_time'],
                ])
            </div>
            <div class="w-50">
                @include('components.time-picker', [
                    'name' => 'end_time',
                    'class' => "endTime event-time $isDisbale",
                    'label' => 'End time',
                    'value' => @$data['end_time'],
                ])
            </div>
        </div>
        <div class="mb-3">
            <select
                id="appointmentFrequency"
                name="frequency_repeat"
                class="form-control {{ $isDisbale }}"
            >
                <option {{ @$data['frequency_repeat'] == 'Weekly' ? 'selected' : '' }} value="Weekly">Weekly</option>
                <option {{ @$data['frequency_repeat'] == 'Bi-weekly' ? 'selected' : '' }} value="Bi-weekly">Bi-weekly</option>
                <option {{ @$data['frequency_repeat'] == 'Monthly' ? 'selected' : '' }} value="Monthly">Monthly</option>
            </select>
        </div>
        <div class="mb-3">
            <select
                id="Monthly"
                name="{{ @$data['frequency_repeat'] == 'Monthly' ? 'frequency_repeat_at' : '' }}"
                class="form-control {{ $isDisbale }}"
                style="display: {{ @$data['frequency_repeat'] == 'Monthly' ? 'block' : 'none' }}"
            >
                <option {{ @$data['frequency_repeat_at'] == 'Week 1' ? 'selected' : '' }} value="Week 1">{{ __('schedule.monthly1') }}</option>
                <option {{ @$data['frequency_repeat_at'] == 'Week 2' ? 'selected' : '' }} value="Week 2">{{ __('schedule.monthly2') }}</option>
                <option {{ @$data['frequency_repeat_at'] == 'Week 3' ? 'selected' : '' }} value="Week 3">{{ __('schedule.monthly3') }}</option>
                <option {{ @$data['frequency_repeat_at'] == 'Week 4' ? 'selected' : '' }} value="Week 4">{{ __('schedule.monthly4') }}</option>
            </select>
        </div>
        <div class="mb-3">
            <select
                id="Bi-weekly"
                name="{{ @$data['frequency_repeat'] == 'Bi-weekly' ? 'frequency_repeat_at' : '' }}"
                class="form-control {{ $isDisbale }}"
                style="display: {{ @$data['frequency_repeat'] == 'Bi-weekly' ? 'block' : 'none' }}"
            >
                <option {{ @$data['frequency_repeat_at'] == 'Week 1' ? 'selected' : '' }} value="Week 1">{{ __('schedule.biweekly1') }}</option>
                <option {{ @$data['frequency_repeat_at'] == 'Week 2' ? 'selected' : '' }} value="Week 2">{{ __('schedule.biweekly2') }}</option>
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
            ])
        </div>
        <span class="therapists"></span>
        <div class="therapist-attendance border py-1 pl-2 {{ isset($data['therapistIds']) ? 'd-flex' : 'd-none' }}">
            @if (in_array(@$data['type'], ['group', 'staff-meeting']))
                @if (isset($data['therapistIds']))
                    @foreach ($data['therapistIds'] as $therapistId)
                        <x-is-user-attended id="{{ $therapistId }}" name="{{ getUserNameById($therapistId) }}" eventId="{{ $data['id'] }}" />
                    @endforeach
                @endif
            @else
                <x-is-user-attended id="{{ @$data['therapistIds'][0] }}" name="Participated?" eventId="{{ @$data['id'] }}"/>
            @endif
        </div>
        @if (!in_array(@$data['type'], ['documentation-break', 'preparation', 'tutorial', 'other']))
            <div class="mt-3">
                @include('components.multi-select-input', [
                    'name' => "children_ids[]",
                    'class' => 'selectChildrens',
                    'id' => 'children',
                    'icon' => 'buildings',
                    'options' => @$data['allChildrens'],
                    'value' => @$data['childrenId'],
                ])
            </div>
            <span class="childrens mb-3"></span>
            <div class="children-attendance">
                @if (isset($data['childrenId']))
                    @foreach ($data['childrenId'] as $childrenId)
                        @php
                            $childrenData = isChildrenAttended($childrenId, $data['id']);
                        @endphp
                        @include('components.children-participated', [
                            'index' => $loop->iteration,
                            'id' => @$childrenData->id,
                            'name' => getChildrenNameById($childrenId),
                            'data' => $childrenData,
                            'childrenId' => @$childrenId,
                        ])
                    @endforeach
                @endif
            </div>
            {{-- <div class="mt-3 d-flex align-items-center">
                <input type="file" class="form-control" name="" id="">
            </div> --}}
        @endif
        <input type="hidden" name="schedule_id" value="{{ @$data['schedule_id'] }}">
        <input type="hidden" name="event_id" id="eventId" value="{{ @$data['id'] }}">
        <input type="hidden" name="type" id="type" value="{{ @$data['type'] }}">
        <div class=" mt-4">
            <button type="submit" class="btn button" id="eventModalBtn">Save</button>
            <button type="button" class="btn button me-2" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
        </div>
    </form>
</div>
<script>
    var type = $('#appointmentType').val();
    var isMultiple = (type === 'group' || type === 'staff-meeting');
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

    $("#eventForm").validate({
        rules: {
            type: { required: true },
            day: { required: true },
            time: { required: true },
            "therapist_ids[]": { required: true },
            "children_ids[]": {
                required: false,
                minChildren: true
            },
            start_time: { required: true },
            end_time: { required: true },
        },
        messages: {
            type: { required: "Please enter type!" },
            day: { required: "Please enter schedule day!" },
            time: { required: "Please enter schedule time!" },
            "therapist_ids[]": { required: "Please choose therapist!" },
            "children_ids[]": {
                required: "Please choose at least one child!",
                minChildren: "Please choose at least two children!"
            },
            start_time: { required: "Please enter start time!" },
            end_time: { required: "Please enter end time!" },
        },
        errorPlacement: function (error, element) {
            var name = element.attr("name");
            if (name == 'therapist_ids[]') {
                $('.therapists').html(error);
            } else if (name == 'children_ids[]') {
                $('.childrens').html(error);
            } else {
                error.insertAfter($(element));
            }
        },
        submitHandler: function (form, e) {
            e.preventDefault();
            var submitForm = function() {
                var formData = new FormData(form);
                formData.append('month', getQueryParam('month'));
                formData.append('week', getQueryParam('week'));
                $('#eventModalBtn').html('Processing');
                fetch("{{ route('documentation.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: formData
                }).then(response => response.json()).then(data => {
                    $('#eventModalBtn').html('Save');
                    if (data.status == true) {
                        toastr.success(data.message);
                        if (data.deletedIds) {
                            data.deletedIds.map((id, index) => {
                                let existEvent = window.dp.events.find(id);
                                if (existEvent) {
                                    window.dp.events.remove(existEvent);
                                }
                            });
                        }
                        data.event.map((item, index) => {
                            let existEvent = window.dp.events.find(item.id);
                            if (existEvent) {
                                window.dp.events.remove(existEvent);
                            }
                            window.dp.events.add(item);
                        });
                        $('#eventStatusModal').modal('toggle');
                    } else {
                        toastr.error(data.message);
                    }
                }).catch(error => toastr.error('An error occurred while processing the request.'));
            };

            let confirmMsg = isTimeOutSide == true ?
                                "This appointment is outside the therapist's availability hours. Are you sure you want to add this appointment?" :
                                "Are you sure you want to add this appointment?";

            if (isTimeOutSide && isTimeOutSide == true) {
                Swal.fire({
                    title: confirmMsgTitle,
                    text: "This appointment is outside the therapist's availability hours. Are you sure you want to add this appointment?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, continue it",
                    cancelButtonText: cancelButtonText
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitForm();
                    }
                });
            } else {
                submitForm();
            }
        }
    });
</script>