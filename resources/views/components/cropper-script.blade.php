<script>
    $(document).on('click', '#previewImage', function() {
        $('#profileInp').click();
    });

    $(document).on('click', '.close', function() {
        $('#cropImageModal').modal('toggle');
    });

    $(document).on('click', '.remobeDisable', function() {
        $(this).attr('disabled', false);
    });

    $(document).ready(function () {
        var $avatar = $('#previewImage');
        var $image = $('#imageForCrop');
        var $input = $('#profileInp');
        var $cropBtn = $('#crop');
        var $modal = $('#cropImageModal');
        var cropper;

        $('[data-toggle="tooltip"]').tooltip();

        $input.on('change', function (e) {
            var files = e.target.files;
            var done = function (url) {
                $image.attr('src', url);
                $modal.modal('show');
            };
            if (files && files.length > 0) {
                var file = files[0];
                var reader = new FileReader();
                reader.onload = function (e) {
                    done(reader.result);
                };
                reader.readAsDataURL(file);
                $input.val('');
            }
        });

        $modal.on('shown.bs.modal', function () {
            cropper = new Cropper($image[0], {
                aspectRatio: 1,
                viewMode: 1, // Change viewMode to 1 or 2
                responsive: true, // Ensure the cropper is responsive
                background: false, // Optional: Hide the background of the cropper canvas
                autoCropArea: 1, // Ensure the entire image is visible within the cropper canvas
            });
        }).on('hidden.bs.modal', function () {
            if (cropper) {
                cropper.destroy();
                cropper = null;
                $image.attr('src', '');
            }
        });

        $cropBtn.on('click', function () {
            $modal.modal('hide');
            if (cropper) {
                var canvas = cropper.getCroppedCanvas({
                    width: 160,
                    height: 160,
                });
                canvas.toBlob(function (blob) {
                    var formData = new FormData();
                    var type = $('#type').val();
                    var url = $('#url').val();
                    var user_id = $('#userId').val();
                    formData.append('user_id', user_id);
                    formData.append('type', type);
                    formData.append('image', blob);
                    formData.append('extension', blob.type.replace("image/", " "));
                    $.ajax({
                        url: url,
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            $avatar.attr('src', data.src);
                        },
                        error: function () {
                            console.error('Upload error');
                        },
                    });
                });
            }
        });
    });


</script>