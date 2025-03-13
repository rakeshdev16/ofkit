<script>
    let timeSlots = [];

    function inilizeTimePicker() {
        let isRendering = true;
        $(".timepicker").each(function () {
            var $this = $(this);
            var existingStartTime = $this.val();
            $this.timepicker({
                timeFormat: "H:mm",
                interval: 15,
                minTime: "07",
                maxTime: "17:00",
                defaultTime: existingStartTime ? existingStartTime : null,
                startTime: "07:00",
                dynamic: false,
                dropdown: true,
                scrollbar: true,
                change: function () {
                    if (isRendering) return;
                    let day = $(this).data('day');
                    let key = $(this).data('index');
                    var selectedTime = $this.val();
                    if (!isRendering) {
                        deleteSlot(key);
                    };
                    var $endTimePicker = $(this).parent().siblings().find(".end-timepicker");
                    if (selectedTime == '') {
                        $endTimePicker.val('');
                    }
                    if (isTimeOverlapping(day, selectedTime, null)) {
                        $(this).val('');
                        toastr.error("This time slot not available");
                        $endTimePicker.prop('disabled', true);
                    } else {
                        $endTimePicker.prop('disabled', false);
                    }
                    if ($endTimePicker.length) {
                        var existingEndTime = $endTimePicker.data("end-time");
                        var minEndTime = addMinutes(selectedTime, 15);
                        $endTimePicker.timepicker("option", "minTime", minEndTime);
                        if (existingEndTime) {
                            $endTimePicker.val(existingEndTime);
                        } else {
                            if (selectedTime) $endTimePicker.val("").prop("required", true);
                        }
                    }
                }
            });

            $this.on("change", function () {
                var $input = $(this);
                var timeValue = $input.val().trim();
                var $endTimePicker = $(this).parent().siblings().find(".end-timepicker");
                if (timeValue === '') {
                    $endTimePicker.prop('disabled', true);
                    $endTimePicker.val("").prop("required", false);
                }
            });
            $this.on("blur", function () {
                var $input = $(this);
                var timeValue = $input.val().trim();
                if (timeValue === "") return;
                var timeFormat = /^([0-9]|[01][0-9]|2[0-3]):[0-5][0-9]$/;
                if (!timeFormat.test(timeValue)) {
                    $input.val("");
                    toastr.error("Invalid time format. Please enter in HH:mm format.");
                }
                var $endTimePicker = $(this).parent().siblings().find(".end-timepicker");
                if (timeValue === '') {
                    $endTimePicker.val("").prop("required", false);
                }
            });

            if (existingStartTime) {
                $this.val(existingStartTime);
            }
        });

        function addMinutes(time, minutes) {
            if (time) {
                var [hour, min] = time.split(":").map(Number);
                var date = new Date();
                date.setHours(hour);
                date.setMinutes(min + minutes);
                return date.getHours().toString().padStart(2, '0') + ":" + date.getMinutes().toString().padStart(2, '0');
            }
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
                maxTime: "17:00",
                defaultTime: existingEndTime ? existingEndTime : "07",
                startTime: "01:00",
                dynamic: false,
                dropdown: true,
                scrollbar: true,
                change: function() {
                    let startTime = $(this).parent().parent().find('.timepicker').val();
                    let endTime = $(this).val();
                    let day = $(this).data('day');
                    let key = $(this).data('index');
                    if (!isRendering) {
                        deleteSlot(key);
                    };
                    if (isTimeOverlapping(day, startTime, endTime)) {
                        $(this).val('');
                        toastr.error("This time slot is not available");
                        return true;
                    }
                    if (startTime && endTime && day) {
                        addSlot(key, day, startTime, endTime);
                    }
                }
            });

            $this.on("blur", function () {
                let key = $(this).data('index');
                var $input = $(this);
                var timeValue = $input.val().trim();
                if (timeValue === "") return;
                var timeFormat = /^([0-9]|[01][0-9]|2[0-3]):[0-5][0-9]$/;
                if (!timeFormat.test(timeValue)) {
                    $input.val("");
                    toastr.error("Invalid time format. Please enter in HH:mm format.");
                }
                let startTime = $(this).parent().parent().find('.timepicker').val();
                if (startTime && compareTimes(timeValue, startTime) <= 0) {
                    toastr.error("End time must be greater than start time.");
                    $input.val("");
                    return;
                }
                if (timeValue == '') {
                    deleteSlot(key);
                }
            });

            if (existingEndTime) {
                $this.val(existingEndTime);
            }
        });

        function compareTimes(time1, time2) {
            var t1 = time1.split(":").map(Number);
            var t2 = time2.split(":").map(Number);
            if (t1[0] > t2[0] || (t1[0] === t2[0] && t1[1] > t2[1])) {
                return 1;
            } else if (t1[0] === t2[0] && t1[1] === t2[1]) {
                return 0;
            } else {
                return -1;
            }
        }

        function addSlot(key, day, startTime, endTime) {
            startTime = startTime.replace(':', '');
            endTime = endTime.replace(':', '');

            const existingIndex = timeSlots.findIndex(slot => slot.id === key);

            if (existingIndex !== -1) {
                timeSlots[existingIndex] = { id: key, name: day, startTime, endTime };
            } else {
                timeSlots.push({ id: key, name: day, startTime, endTime });
            }
        }

        function deleteSlot(key) {
            timeSlots = timeSlots.filter(slot => !(slot.id === key));
        }

        function isTimeOverlapping(day, startTime, endTime) {
            if (startTime) {
                startTime = startTime.replace(':', '');
                endTime = endTime ? endTime.replace(':', '') : null;

                return timeSlots.some(slot => {
                    if (slot.name !== day) return false;
                    let existingStart = parseInt(slot.startTime, 10);
                    let existingEnd = parseInt(slot.endTime, 10);
                    let newStart = parseInt(startTime, 10);
                    let newEnd = endTime ? parseInt(endTime, 10) : null;

                    if (!newEnd) {
                        return (newStart >= existingStart && newStart < existingEnd);
                    }

                    return (
                        (newStart >= existingStart && newStart < existingEnd) || // New start falls inside an existing slot
                        (newEnd > existingStart && newEnd <= existingEnd) || // New end falls inside an existing slot
                        (newStart <= existingStart && newEnd >= existingEnd) // New slot completely covers an existing slot
                    );
                });
            }

            return false; // Default case if no valid startTime or endTime is provided
        }
    };

    function addMoreTime(element, day) {
        let id = $(element).data('id');
        let key = $(element).data('key');
        let index = $('tr.'+day+id).length;
        let parentElement = $(element).closest("tr");
        let clonedElement = parentElement.clone();
        // $('.'+day+''+id).find('.form-control').addClass('no-click').attr('readonly', true);
        clonedElement.find(".form-control").val("");
        clonedElement.find("input").each(function () {
            let nameAttr = $(this).attr("name");
            if (nameAttr) {
                nameAttr = nameAttr.replace(/\[(\d+)\]\[([^\]]+)\]\[(\d+)\]/, function(match, firstIndex, day, thirdIndex) {
                    return `[${firstIndex}][${day}][${index}]`; // Increment third index
                });
                $(this).attr("name", nameAttr);
                $(this).attr("data-index", id+day+(index+1));
                if ($(this).attr("type") === 'hidden') {
                    $(this).val('');
                }
                if ($(this).attr("placeholder") === 'Enter End Time') {
                    $(this).prop('disabled', true);
                }
            }
        });
        clonedElement.find("button").replaceWith(`
            <button type="button" class="btn btn-danger" onclick="removeDay(this);" data-id="${key+index}">
                <i class="fa fa-minus"></i>
            </button>
        `);

        $('.'+day+''+id).parent().parent().last().after(clonedElement);
        inilizeTimePicker();
    }

    function removeDay(element) {
        let key = $(element).data('id');
        $(element).closest("tr").remove();
        timeSlots = timeSlots.filter(slot => !(slot.id === key));
    }
</script>