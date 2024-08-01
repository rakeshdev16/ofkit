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
            var file = e.target.files;

            var extensionArr = ['jpeg', 'jpg', 'png', 'jfif', 'pjpeg', 'pjp', 'gif', 'svg'];
            var image = extensionArr.includes(file[0].name.split('.').pop());
            $('.cropperImageError').hide();
            if (!image) {
                $('.cropperImageError').show();
                return false;
            }

            var done = function (url) {
                $image.attr('src', url);
                $modal.modal('show');
            };
            if (file && file.length > 0) {
                var file = file[0];
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
                            $('.deletePhoto').prop('hidden', false);
                        },
                        error: function () {
                            console.error('Upload error');
                        },
                    });
                });
            }
        });
    });
    $(document).on('click', '.deletePhoto', function() {
        var url = $(this).data('url');
        var id = $('#userId').val();
        Swal.fire({
            title: "Are you sure?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, archive it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url: url,
                    data: { id: id },
                    success: function(data) {
                        if (data.status == true) {
                            $('#previewImage').attr('src', data.src);
                            $('.deletePhoto').prop('hidden', true);
                            toastr.success(data.message);
                        }
                    }
                });
            }
        });
    });

</script>