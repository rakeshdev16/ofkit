<script>
    $(document).ready(function() {
        $('.kindergarten').select2();

        var allFiles = [];

        $('.documents').change(function(event) {
            const files = event.target.files;
            for (let i = 0; i < files.length; i++) {
                allFiles.push(files[i]);
            }
            let fileList = $('.choosenDocument');
            $.each(allFiles, function(index, file) {
                fileList.append('<div class="document mt-1"><a href="#" target="_blank" rel="noopener noreferrer">'+ file.name +'</a><i class="bx bx-x staffDocument" data-file-name="' + file.name + '"></i></div>');
            });
            event.target.value = '';
            updateFileInput(allFiles);
        });

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

    $(document).on('change', '.kindergarten', function() {
        var ids = $(this).val();
        $.ajax({
            type: 'GET',
            url: "{{ route('selected.kindergarten') }}",
            data: { ids: ids },
            success: function(data) {
                if (data.status == true) {
                    data.data.forEach(function(row, index) {
                        if ($('.tr-' + ids[index]).length == 0) {
                            $('.selected-kindergarten').append(row);
                        }
                    });
                    $('.kindergarten-section').show();
                } else {
                    $('.selected-kindergarten').html('');
                    $('.kindergarten-section').hide();
                }
            }
        });
    });
</script>