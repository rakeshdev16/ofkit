<table class="table table-{{ $id }} table-borderd" style="width:100%;"  data-index="{{ $index }}">
    @php
        $sunday = isset($schedule) ? (clone $schedule)->where('day', 'sunday')->where('kindergarten_id', $id)->first() : null;
        $monday = isset($schedule) ? (clone $schedule)->where('day', 'monday')->where('kindergarten_id', $id)->first() : null;
        $tuesday = isset($schedule) ? (clone $schedule)->where('day', 'tuesday')->where('kindergarten_id', $id)->first() : null;
        $wednesday = isset($schedule) ? (clone $schedule)->where('day', 'wednesday')->where('kindergarten_id', $id)->first() : null;
        $thursday = isset($schedule) ? (clone $schedule)->where('day', 'thursday')->where('kindergarten_id', $id)->first() : null;
        $friday = isset($schedule) ? (clone $schedule)->where('day', 'friday')->where('kindergarten_id', $id)->first() : null;
        $saturday = isset($schedule) ? (clone $schedule)->where('day', 'saturday')->where('kindergarten_id', $id)->first() : null;
    @endphp
    <thead>
        <tr>
            <th>{{ __('staff.name') }}</th>
            <th>{{ __('staff.professionalRole') }}</th>
            <th>{{ __('staff.association') }}</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <h6 class="pt-2">{{ getKindergartenNameById($id) }}</h6>
                <input type="hidden" name="kindergarten[{{ $index }}][kindergarten_id]" value="{{ $id }}">
            </td>
            <td>
                @if (Auth::user()->hasRole('admin'))
                    @include('components.select-input', [
                        'name' => "kindergarten[$index][role_id]",
                        'icon' => 'buildings',
                        'options' => $memberRoles,
                        'disabled' => Route::currentRouteName() == 'staff.show' ? 'disabled' : '',
                        'value' => old('kindergarten.' . $index . '.role_id') ?? @$data['role_id'],
                    ])
                @else
                    @php
                        $keys = array_column($memberRoles, 'key');
                        $index = array_search(@$data['role_id'], $keys);
                    @endphp
                    <div class="position-relative input-icon">
                        <input type="text" class="form-control" value="{{ $index !== false ? $memberRoles[$index]['value'] : '' }}" readonly="">
                        <span class="position-absolute top-50 translate-middle-y">
                            <i class="bx bx-buildings"></i>
                        </span>
                        <input type="hidden" name="kindergarten[{{ $index }}][role_id]" value="{{ @$data['role_id'] }}">
                    </div>
                @endif
                @error('kindergarten.' . $index . '.role_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </td>
            <td>
                @if (Auth::user()->hasRole('admin'))
                    @include('components.select-input', [
                        'name' => "kindergarten[$index][association_id]",
                        'icon' => 'buildings',
                        'options' => $associations,
                        'value' => @$data['association_id'],
                        'disabled' => Route::currentRouteName() == 'staff.show' ? 'disabled' : '',
                        'value' => old('kindergarten.' . $index . '.association_id') ?? @$data['association_id'],
                    ])
                @else
                    @php
                        $keys = array_column($associations, 'key');
                        $index = array_search(@$data['association_id'], $keys);
                    @endphp
                    <div class="position-relative input-icon">
                        <input type="text" class="form-control" value="{{ $index !== false ? $associations[$index]['value'] : '' }}" readonly="">
                        <span class="position-absolute top-50 translate-middle-y">
                            <i class="bx bx-buildings"></i>
                        </span>
                        <input type="hidden" name="kindergarten[{{ $index }}][association_id]" value="{{ @$data['association_id'] }}">
                    </div>
                @endif

                @error('kindergarten.' . $index . '.association_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </td>
        </tr>
        <tr>
            <td style="background: #80808000">Sunday</td>
            <td class="w-50" style="background: #80808000">
                <input
                    type="text"
                    class="form-control timepicker"
                    name="schedule[sunday][{{$id}}][start_time]"
                    data-index="sunday{{$id}}"
                    data-user-id="{{ @$data['user_id'] }}"
                    data-day="sunday"
                    value="{{ @$sunday->start_time }}"
                    placeholder="Enter Start Time"
                    {{ Route::currentRouteName() == 'staff.show' ? 'disabled' : '' }}
                >
            </td>
            <td class="w-50" style="background: #80808000">
                <input
                    type="text"
                    class="form-control end-timepicker sunday{{$id}}"
                    name="schedule[sunday][{{$id}}][end_time]"
                    data-index="{{$id}}"
                    value="{{ @$sunday->end_time }}"
                    placeholder="Enter End Time"
                    {{ Route::currentRouteName() == 'staff.show' ? 'disabled' : '' }}
                >
                <input type="hidden" name="schedule[sunday][{{$id}}][id]" value="{{ @$sunday->id }}">
            </td>
        </tr>
        <tr>
            <td style="background: #80808000">Monday</td>
            <td class="w-50" style="background: #80808000">
                <input
                    type="text"
                    class="form-control timepicker"
                    name="schedule[monday][{{$id}}][start_time]"
                    data-index="monday{{$id}}"
                    data-user-id="{{ @$data['user_id'] }}"
                    data-day="monday"
                    value="{{ @$monday->start_time }}"
                    placeholder="Enter Start Time"
                    {{ Route::currentRouteName() == 'staff.show' ? 'disabled' : '' }}
                >
            </td>
            <td class="w-50" style="background: #80808000">
                <input
                    type="text"
                    class="form-control end-timepicker monday{{$id}}"
                    name="schedule[monday][{{$id}}][end_time]"
                    data-index="{{$id}}"
                    value="{{ @$monday->end_time }}"
                    placeholder="Enter End Time"
                    {{ Route::currentRouteName() == 'staff.show' ? 'disabled' : '' }}
                >
                <input type="hidden" name="schedule[monday][{{$id}}][id]" value="{{ @$monday->id }}">
            </td>
        </tr>
        <tr>
            <td style="background: #80808000">Tuesday</td>
            <td class="w-50" style="background: #80808000">
                <input
                    type="text"
                    class="form-control timepicker"
                    name="schedule[tuesday][{{$id}}][start_time]"
                    data-index="tuesday{{$id}}"
                    data-user-id="{{ @$data['user_id'] }}"
                    data-day="tuesday"
                    value="{{ @$tuesday->start_time }}"
                    placeholder="Enter Start Time"
                    {{ Route::currentRouteName() == 'staff.show' ? 'disabled' : '' }}
                >
            </td>
            <td class="w-50" style="background: #80808000">
                <input
                    type="text"
                    class="form-control end-timepicker tuesday{{$id}}"
                    name="schedule[tuesday][{{$id}}][end_time]"
                    data-index="{{$id}}"
                    value="{{ @$tuesday->end_time }}"
                    placeholder="Enter End Time"
                    {{ Route::currentRouteName() == 'staff.show' ? 'disabled' : '' }}
                >
                <input type="hidden" name="schedule[tuesday][{{$id}}][id]" value="{{ @$tuesday->id }}">
            </td>
        </tr>
        <tr>
            <td style="background: #80808000">Wednesday</td>
            <td class="w-50" style="background: #80808000">
                <input
                    type="text"
                    class="form-control timepicker"
                    name="schedule[wednesday][{{$id}}][start_time]"
                    data-index="wednesday{{$id}}"
                    data-user-id="{{ @$data['user_id'] }}"
                    data-day="wednesday"
                    value="{{ @$wednesday->start_time }}"
                    placeholder="Enter Start Time"
                    {{ Route::currentRouteName() == 'staff.show' ? 'disabled' : '' }}
                >
            </td>
            <td class="w-50" style="background: #80808000">
                <input
                    type="text"
                    class="form-control end-timepicker wednesday{{$id}}"
                    name="schedule[wednesday][{{$id}}][end_time]"
                    data-index="{{$id}}"
                    value="{{ @$wednesday->end_time }}"
                    placeholder="Enter End Time"
                    {{ Route::currentRouteName() == 'staff.show' ? 'disabled' : '' }}
                >
                <input type="hidden" name="schedule[wednesday][{{$id}}][id]" value="{{ @$wednesday->id }}">
            </td>
        </tr>
        <tr>
            <td style="background: #80808000">Thursday</td>
            <td class="w-50" style="background: #80808000">
                <input
                    type="text"
                    class="form-control timepicker"
                    name="schedule[thursday][{{$id}}][start_time]"
                    data-index="thursday{{$id}}"
                    data-user-id="{{ @$data['user_id'] }}"
                    data-day="thursday"
                    value="{{ @$thursday->start_time }}"
                    placeholder="Enter Start Time"
                    {{ Route::currentRouteName() == 'staff.show' ? 'disabled' : '' }}
                >
            </td>
            <td class="w-50" style="background: #80808000">
                <input
                    type="text"
                    class="form-control end-timepicker thursday{{$id}}"
                    name="schedule[thursday][{{$id}}][end_time]"
                    data-index="{{$id}}"
                    value="{{ @$thursday->end_time }}"
                    placeholder="Enter End Time"
                    {{ Route::currentRouteName() == 'staff.show' ? 'disabled' : '' }}
                >
                <input type="hidden" name="schedule[thursday][{{$id}}][id]" value="{{ @$thursday->id }}">
            </td>
        </tr>
        <tr>
            <td style="background: #80808000">Friday</td>
            <td class="w-50" style="background: #80808000">
                <input
                    type="text"
                    class="form-control timepicker"
                    name="schedule[friday][{{$id}}][start_time]"
                    data-index="friday{{$id}}"
                    data-user-id="{{ @$data['user_id'] }}"
                    data-day="friday"
                    value="{{ @$friday->start_time }}"
                    placeholder="Enter Start Time"
                    {{ Route::currentRouteName() == 'staff.show' ? 'disabled' : '' }}
                >
            </td>
            <td class="w-50" style="background: #80808000">
                <input
                    type="text"
                    class="form-control end-timepicker friday{{$id}}"
                    name="schedule[friday][{{$id}}][end_time]"
                    data-index="{{$id}}"
                    value="{{ @$friday->end_time }}"
                    placeholder="Enter End Time"
                    {{ Route::currentRouteName() == 'staff.show' ? 'disabled' : '' }}
                >
                <input type="hidden" name="schedule[friday][{{$id}}][id]" value="{{ @$friday->id }}">
            </td>
        </tr>
        <tr>
            <td style="background: #80808000">Saturday</td>
            <td class="w-50" style="background: #80808000">
                <input
                    type="text"
                    class="form-control timepicker"
                    name="schedule[saturday][{{$id}}][start_time]"
                    data-index="saturday{{$id}}"
                    data-user-id="{{ @$data['user_id'] }}"
                    data-day="saturday"
                    value="{{ @$saturday->start_time }}"
                    placeholder="Enter Start Time"
                    {{ Route::currentRouteName() == 'staff.show' ? 'disabled' : '' }}
                >
            </td>
            <td class="w-50" style="background: #80808000">
                <input
                    type="text"
                    class="form-control end-timepicker saturday{{$id}}"
                    name="schedule[saturday][{{$id}}][end_time]"
                    data-index="{{$id}}"
                    value="{{ @$saturday->end_time }}"
                    placeholder="Enter End Time"
                    {{ Route::currentRouteName() == 'staff.show' ? 'disabled' : '' }}
                >
                <input type="hidden" name="schedule[saturday][{{$id}}][id]" value="{{ @$saturday->id }}">
            </td>
        </tr>
    </tbody>
</table>
<script>
    $(function () {
        let isRendering = true;
        let staffSlot = {};
        $(".timepicker").each(function () {
            var $this = $(this);
            var existingStartTime = $this.val();
            $this.timepicker({
                timeFormat: "H:mm",
                interval: 15,
                minTime: "07",
                maxTime: "23:00",
                defaultTime: existingStartTime ? existingStartTime : null,
                startTime: null,
                dynamic: true,
                dropdown: true,
                scrollbar: false,
                change: function () {
                    // Object.keys(staffSlot).forEach(key => delete staffSlot[key]);
                    // staffSlot.user_id = $(this).data('user-id');
                    // staffSlot.day = $(this).data('day');
                    // staffSlot.time = $this.val();
                    // fetch("{{ route('check.staff-slot') }}", {
                    //     method: "POST",
                    //     headers: {
                    //         "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    //         "Content-Type": "application/json",
                    //     },
                    //     body: JSON.stringify(staffSlot),
                    // }).then((response) => response.json()).then((data) => {
                    //     console.log("data", data);
                    //     if (data.status == true) {
                    //         $(this).val('');
                    //         toastr.error("Not Avilable");
                    //     }
                    // });

                    if (isRendering) return;
                    var selectedTime = $this.val();
                    var endTimeClass = $this.attr("data-index");
                    var $endTimePicker = $("." + endTimeClass);
                    if ($endTimePicker.length) {
                        var existingEndTime = $endTimePicker.data("end-time");
                        var minEndTime = addMinutes(selectedTime, 15);
                        $endTimePicker.timepicker("option", "minTime", minEndTime);
                        if (existingEndTime) {
                            $endTimePicker.val(existingEndTime);
                        } else {
                            $endTimePicker.val("").prop("required", true);;
                        }
                    }
                }
            });

            if (existingStartTime) {
                $this.val(existingStartTime);
            }
        });

        function addMinutes(time, minutes) {
            var [hour, min] = time.split(":").map(Number);
            var date = new Date();
            date.setHours(hour);
            date.setMinutes(min + minutes);
            return date.getHours().toString().padStart(2, '0') + ":" + date.getMinutes().toString().padStart(2, '0');
        }

        setTimeout(() => {
            isRendering = false;
        }, 500);


        $(".end-timepicker").each(function () {
            var $this = $(this);
            var existingEndTime = $this.val();
            $this.timepicker({
                timeFormat: "H:mm",
                interval: 15,
                minTime: "07:15",
                maxTime: "23:00",
                defaultTime: existingEndTime ? existingEndTime : "07",
                startTime: "01:00",
                dynamic: true,
                dropdown: true,
                scrollbar: false
            });

            if (existingEndTime) {
                $this.val(existingEndTime);
            }
        });

        // async function checkStaffTime(timeSlotData) {
        //     try {
        //         let response = await fetch("{{ route('check.staff-slot') }}", {
        //             method: "POST",
        //             headers: {
        //                 "X-CSRF-TOKEN": "{{ csrf_token() }}",
        //                 "Content-Type": "application/json",
        //             },
        //             body: JSON.stringify(timeSlotData),
        //         });

        //         let data = await response.json();
        //         return data.status;
        //     } catch (error) {
        //         console.error("Error checking staff time:", error);
        //         return false;
        //     }
        // }

        // async function isAvailable() {
        //     return await checkStaffTime({ startTime: "08:00", endTime: "09:00" });
        // }
        // console.log("isAvailable()", isAvailable() );

    });

</script>