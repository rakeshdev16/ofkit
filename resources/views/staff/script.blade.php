<script>
    $(document).ready(function() {
        var selectedOrder = [];

        $('.kindergarten').select2();

        var allFiles = [];

        $('.documents').change(function(event) {
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
                        fileList.append('<div class="document mt-1">'+ file.name +'<i class="bx bx-x staffDocument" data-file-name="' + file.name + '"></i></div>');
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
            let parentDiv = $(this).parent();
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

        function updateSelect2Order() {
            var currentValues = $('.kindergarten').val() || [];
            selectedOrder = currentValues.filter(value => selectedOrder.includes(value)).concat(currentValues.filter(value => !selectedOrder.includes(value)));
            $('.kindergarten').val(selectedOrder).trigger('change');
        }

        $('.kindergarten').on('select2:select', function(e) {
            var id = e.params.data.id;

            var selectedValues = $(this).val() || [];
            selectedValues = selectedValues.filter(function(value) {
                return value !== id;
            });
            selectedValues.unshift(id);
            $(this).val(selectedValues).trigger('change');
            updateSelect2Order();
            var user_id = $('#userId').val();
            var index = $('.selected-kindergarten tr').length;
            $.ajax({
                type: 'GET',
                url: "{{ route('selected.kindergarten') }}",
                data: { id: id, user_id: user_id, index: index },
                success: function(data) {
                    if (data.status == true) {
                        if ($('.tr-' + id).length == 0) {
                            $('.selected-kindergarten').append(data.data);
                        }
                        $('.kindergarten-section').show();
                    } else {
                        $('.selected-kindergarten').html('');
                        $('.kindergarten-section').hide();
                    }
                }
            });
        });

        $('.kindergarten').on('select2:unselect', function(e) {
            var id = e.params.data.id;

            var selectedValue = e.params.data.id;
            if (e.type === 'select2:select') {
                selectedOrder = [id].concat(selectedOrder.filter(value => value !== id));
            } else {
                selectedOrder = selectedOrder.filter(value => value !== id);
            }
            updateSelect2Order();

            $('.tr-' + id).remove();
            var length = $('.selected-kindergarten tr').length;
            if (length == 0) {
                $('.kindergarten-section').hide();
            }
        });
    });

    var oldValues = @json(old('kindergarten_id', []));
    if (oldValues.length > 0) {
        selectedOrder = oldValues;
        $('.kindergarten').val(oldValues).trigger('change');
    }


    // $(document).on('change', '.kindergarten', function() {
    //     var ids = $(this).val();
    //     console.log(ids);
    //     $.ajax({
    //         type: 'GET',
    //         url: "{{ route('selected.kindergarten') }}",
    //         data: { ids: ids },
    //         success: function(data) {
    //             if (data.status == true) {
    //                 data.data.forEach(function(row, index) {
    //                     if ($('.tr-' + ids[index]).length == 0) {
    //                         $('.selected-kindergarten').append(row);
    //                     }
    //                 });
    //                 $('.kindergarten-section').show();
    //             } else {
    //                 $('.selected-kindergarten').html('');
    //                 $('.kindergarten-section').hide();
    //             }
    //         }
    //     });
    // });
</script>