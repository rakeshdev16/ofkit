<script>
    $(document).ready(function() {
        timePicker(0);
        $('.kindergarten').select2();
        $('.scheduleKindergarten').select2();

        var selectedKindergartenOptions = $('.kindergarten').select2('data');
        selectedKindergartenOptions.forEach(function(option, index) {
            var id = option.id;
            var name = option.text;
            weeklyKindergartenOptions(id, name);
        });

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
                form.submit();
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
        var index = $('.selected-kindergarten tr').length;
        getKindergaternRow(id, user_id, index);
        weeklyKindergartenOptions(id, name);
        setTimeout(() => {
            kindergartenValidationRules(index);
        }, 100);
    });

    var unselectedKindergarten = [];
    $('.kindergarten').on('select2:unselect', function(e) {
        var id = e.params.data.id;
        const days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        days.map((day) => {
            console.log(day);
            $('.scheduleKindergarten').find('option[value="' + id + '"]').remove();
            $('.' + day + '-tr-' + id).remove();
            if ($('.'+day).length == 0) {
                $('.'+day+'-section').hide();
            }
        });
        if (id) {
            unselectedKindergarten.push(id);
        }
        $('#unselectedKindergarten').val(unselectedKindergarten);
        $('.tr-' + id).remove();
        if ($('.selected-kindergarten tr').length === 0) {
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
                    if ($('.tr-' + id).length == 0) {
                        $('.selected-kindergarten').append(data.data);
                        updateIndexes();
                    }
                    $('.kindergarten-section').show();
                } else {
                    $('.selected-kindergarten').html('');
                    $('.kindergarten-section').hide();
                }
            }
        });
    }

    function updateIndexes() {
        $('.selected-kindergarten tr').each(function(index, element) {
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
        setTimeout(() => {
            timePicker(index)
        }, 100);
        section.show();
        scheduleValidationRules(day, index);
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
        console.log(professionalRole);
        console.log(association);
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

    function timePicker(index) {
        $(document).on('change', '.startTime', function() {
            const endTimeSelect = document.querySelector('.endTime'+index);
            let startTime = $(this).val();
            Array.from(endTimeSelect.options).forEach((option) => {
                if (option.value && option.value <= startTime) {
                    option.disabled = true;
                } else {
                    option.disabled = false;
                }
            });
        })
    }
</script>
