<script>
    let timeSlots = [];
    $(document).ready(function() {
        let isTimeChanged = false;
        // $(document).on('change', '.timepicker', function() {
        //     isTimeChanged = true;
        // });
        // $('.startTime').each(function() {
        //     updateEndTimeOptions(this, false);
        // });
        $('.kindergarten').select2();
        $('.scheduleKindergarten').select2();

        var selectedKindergartenOptions = $('.kindergarten').select2('data');
        if (selectedKindergartenOptions) {
            selectedKindergartenOptions.forEach(function(option, index) {
                var id = option.id;
                var name = option.text;
                weeklyKindergartenOptions(id, name);
            });
        }

        $.validator.addMethod("regex", function (value, element, param) {
            if (this.optional(element)) {
                return true;
            }
            const pattern = new RegExp(param);
            return pattern.test(value);
        });

        $("#addStaffForm").validate({
            rules: {
                first_name: {
                    required: true
                },
                identification: {
                    digits: true,
                    minlength: 9,
                    maxlength: 9,
                    remote: {
                        url: "{{ route('validate.staff.field') }}",
                        type: "post",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            id: "{{ @$staff->id }}",
                            field: 'identification',
                            value: function () {
                                return $("input[name='identification']").val();
                            }
                        }
                    }
                },
                email: {
                    required: function () {
                        return $("#roles").val() !== "support";
                    },
                    email: true,
                    remote: {
                        url: "{{ route('validate.staff.field') }}",
                        type: "post",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            id: "{{ @$staff->id }}",
                            field: 'email',
                            value: function () {
                                return $("input[name='email']").val();
                            }
                        }
                    }
                },
                licence_number: {
                    required: false,
                    regex: "^[0-9-]+$",
                },
                telephone: {
                    required: function () {
                        return $("#roles").val() !== "support";
                    },
                    // pattern: new RegExp("^[0-9-]{8,14}$")
                },
                role: {
                    required: true
                },
            },
            messages: {
                first_name: {
                    required: "{{ __('staff.requiredName') }}"
                },
                identification: {
                    digits: "{{ __('staff.nullableIdentification') }}",
                    minlength: "{{ __('staff.nullableIdentification') }}",
                    maxlength: "{{ __('staff.nullableIdentification') }}",
                    remote: "This identification has already been taken",
                },
                email: {
                    required: "{{ __('staff.requiredEmail') }}",
                    email: "{{ __('staff.validEmail') }}",
                    remote: "{{ __('staff.existsEmail') }}",
                },
                licence_number: {
                    regex: "{{ __('staff.licenceRegex') }}",
                },
                telephone: {
                    required: "{{ __('staff.requiredTelephone') }}"
                },
                role: {
                    required: "{{ __('staff.requiredRole') }}"
                },
            },
            errorPlacement: function (error, element) {
                var name = element.attr("name");
                if (name == 'first_name' || name == 'identification' || name == 'email' || name == 'telephone' || name == 'role' || name == 'licence_number') {
                    $('<div>', { id: name + '_error', class: 'error' }).insertAfter(element);
                    $('#' + name + '_error').html(error);
                } else {
                    error.insertAfter($(element));
                }
            },
            submitHandler: function (form) {
                let route = "{{ Route::currentRouteName(); }}"
                if (isTimeChanged) {
                    Swal.fire({
                        title: confirmMsgTitle,
                        text: "Appointment may exists for this therapist outside of working hours. Do you still want to continue?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, continue it",
                        cancelButtonText: cancelButtonText
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                } else {
                    form.submit();
                }
            }
        });

        var allFiles = [];

        $('.documents').change(function(event) {
            $('.document-section').show();
            const files = event.target.files;
            for (let i = 0; i < files.length; i++) {
                if (documentExists(files[i].name) == false) {
                    allFiles.push(files[i]);
                }
            }
            let fileList = $('.choosenDocument');
            $.each(allFiles, function(index, file) {
                var extensionArr = ['jpeg', 'jpg', 'png', 'jfif', 'pjpeg', 'pjp', 'gif', 'svg', 'pdf', 'docx', 'doc'];
                var validFile = extensionArr.includes(file.name.split('.').pop());
                if (validFile) {
                    if (documentExists(file.name) == false) {
                        // fileList.append('<div class="document mt-1">'+ file.name +'<i class="bx bx-x staffDocument" data-file-name="' + file.name + '"></i></div>');
                        fileList.append(`@include('components.document-detail', ['index' => '${index}', 'name' => '${file.name}', 'class' => 'staffDocument'])`);
                    }
                } else {
                    allFiles = allFiles.filter(doc => doc.name !== file.name);
                    toastr.error(file.name, ' is not supported');
                }
            });
            event.target.value = '';
            updateFileInput(allFiles);
        });

        function documentExists(fileName) {
            const documents = document.querySelectorAll('.choosenDocument .document');
            for (const document of documents) {
                const fileElement = document.querySelector('i.staffDocument');
                if (fileElement && fileElement.getAttribute('data-file-name') === fileName) {
                    return true;
                }
            }
            return false;
        }

        $(document).on('click', '.staffDocument', function() {
            let parentDiv = $(this).parent().parent().parent();
            let fileName = $(this).data('file-name');
            parentDiv.remove();
            allFiles = allFiles.filter(file => file.name !== fileName);
            updateFileInput(allFiles);
        })

        function updateFileInput(documents) {
            const dataTransfer = new DataTransfer();
            documents.forEach(file => {
                dataTransfer.items.add(file);
            });
            document.getElementById('documents').files = dataTransfer.files;
        }
    });

    $('.kindergarten').on('select2:select', function(e) {
        var id = e.params.data.id;
        var name = e.params.data.text;
        var user_id = $('#userId').val();
        var index = $('.selected-kindergarten table').length;
        getKindergaternRow(id, user_id, index);
        // weeklyKindergartenOptions(id, name);
        // setTimeout(() => {
        //     kindergartenValidationRules(index);
        // }, 100);
    });

    var unselectedKindergarten = [];
    $('.kindergarten').on('select2:unselect', function(e) {
        var id = e.params.data.id;
        // const days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        // days.map((day) => {
        //     console.log(day);
        //     $('.scheduleKindergarten').find('option[value="' + id + '"]').remove();
        //     $('.' + day + '-tr-' + id).remove();
        //     if ($('.'+day).length == 0) {
        //         $('.'+day+'-section').hide();
        //     }
        // });
        if (id) {
            unselectedKindergarten.push(id);
        }
        $('#unselectedKindergarten').val(unselectedKindergarten);
        $('.table-' + id).remove();
        if ($('.selected-kindergarten table').length === 0) {
            $('.kindergarten-section').hide();
        }
        updateIndexes();
    });

    function weeklyKindergartenOptions(id, name) {
        var $scheduleSelect = $('.scheduleKindergarten');
        if (!$scheduleSelect.find(`option[value="${id}"]`).length) {
            $scheduleSelect.append(`<option value="${id}">${name}</option>`);
        }
    }

    function getKindergaternRow(id, user_id, index) {
        $.ajax({
            type: 'GET',
            url: "{{ route('selected.kindergarten') }}",
            data: {
                id: id,
                user_id: user_id,
                index: index
            },
            success: function(data) {
                if (data.status == true) {
                    if ($('.table-' + id).length == 0) {
                        $('.selected-kindergarten').append(data.data);
                        updateIndexes();
                    }
                    $('.kindergarten-section').show();
                    inilizeTimePicker();
                } else {
                    $('.selected-kindergarten').html('');
                    $('.kindergarten-section').hide();
                }
            }
        });
    }

    function updateIndexes() {
        $('.selected-kindergarten table').each(function(index, element) {
            $(this).find('input[name^="kindergarten"]').each(function() {
                var name = $(this).attr('name').replace(/\[\d+\]/, '[' + index + ']');
                $(this).attr('name', name);
            });
            $(this).find('select[name^="kindergarten"]').each(function() {
                var name = $(this).attr('name').replace(/\[\d+\]/, '[' + index + ']');
                $(this).attr('name', name);
            });
        });
    }

    // Weekly schedule script

    $('.scheduleKindergarten').on('select2:select', function(e) {
        var day = $(this).data('name');
        var section = $('.'+day+'-section');
        var body = $('.'+day+'-body');
        var id = e.params.data.id;
        var name = e.params.data.text;
        var index = $('.'+day).length;
        body.append(`@include('components.staff-schedule', [
            'id' => '${id}',
            'day' => '${day}',
            'index' => '${index}',
            'name' => '${name}',
            'data' => ['start_time' => '', 'end_time' => '']
        ])`);
        section.show();
        setTimeout(() => {
            // updateEndTimeOptions(index)
            scheduleValidationRules(day, index);
        }, 1000);
    });

    $('.scheduleKindergarten').on('select2:unselect', function(e) {
        var id = e.params.data.id;
        var day = $(this).data('name');
        $('.'+day+'-tr-' + id).remove();
        if ($('.'+day).length == 0) {
            $('.'+day+'-section').hide();
        }
    });

    function kindergartenValidationRules(index) {
        var professionalRole = `kindergarten[${index}][role_id]`;
        var association = `kindergarten[${index}][association_id]`;
        $(`[name="${professionalRole}"]`).rules("add", {
            required: true,
            messages: {
                required: "{{ __('staff.requiredRoleId') }}"
            }
        });

        $(`[name="${association}"]`).rules("add", {
            required: true,
            messages: {
                required: "{{ __('staff.requiredAssociation') }}"
            }
        });
    }

    function scheduleValidationRules(day, index) {
        var startTimeField = `schedule[${day}][${index}][start_time]`;
        var endTimeField = `schedule[${day}][${index}][end_time]`;

        $(`[name="${startTimeField}"]`).rules("add", {
            required: true,
            messages: {
                required: "Please enter start time."
            }
        });

        $(`[name="${endTimeField}"]`).rules("add", {
            required: true,
            messages: {
                required: "Please enter end time."
            }
        });
    }

    function updateEndTimeOptions(startTimeElement, shouldClearEndTime) {
        const endTimeClass = $(startTimeElement).data('index');
        const endTimeSelect = document.querySelector('.' + endTimeClass);
        let startTime = $(startTimeElement).val();
        if (endTimeSelect !== null) {
            if (shouldClearEndTime) {
                endTimeSelect.value = "";

                 $(endTimeSelect).rules("add", {
                    required: function () {
                        return startTime !== "";
                    },
                    messages: {
                        required: "End time is required if start time is selected.",
                    }
                });
                $(endTimeSelect).valid();
            }
            Array.from(endTimeSelect.options).forEach((option) => {
                if (option.value && option.value <= startTime) {
                    option.disabled = true;
                } else {
                    option.disabled = false;
                }
            });
        }
    }

    $(document).on('change', '.startTime', function() {
        updateEndTimeOptions(this, true);
    });


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
