<script>
    $(document).ready(function() {
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
    });

    $('.kindergarten').on('select2:select', function(e) {
        var id = e.params.data.id;
        var user_id = $('#userId').val();
        var index = $('.selected-kindergarten tr').length;
        getKindergaternRow(id, user_id, index);
    });

    $('.kindergarten').on('select2:unselect', function(e) {
        var id = e.params.data.id;
        $('.tr-' + id).remove();
        var length = $('.selected-kindergarten tr').length;
        if (length == 0) {
            $('.kindergarten-section').hide();
        }
    });

    function getKindergaternRow(id, user_id, index) {
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