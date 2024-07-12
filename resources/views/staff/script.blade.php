<script>
    $(document).on('click', '.close', function() {
        $('#cropImageModal').modal('toggle');
    });
    $(document).ready(function () {
        var $avatar = $('#previewStaffImage');
        var $image = $('#imageForCrop');
        var $input = $('#staffProfileInp');
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
                viewMode: 3,
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
                    var user_id = $('#userId').val();
                    formData.append('user_id', user_id);
                    formData.append('type', type);
                    formData.append('image', blob);
                    formData.append('extension', blob.type.replace("image/", " "));
                    $.ajax({
                        url: "{{ route('uploadStaffProfile') }}",
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