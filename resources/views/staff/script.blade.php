<script>
    window.addEventListener('DOMContentLoaded', function () {
        var avatar = document.getElementById('previewStaffImage');
        var image = document.getElementById('imageForCrop');
        var input = document.getElementById('staffProfileInp');
        var cropBtn = document.getElementById('crop');
        var $modal = $('#cropAvatarmodal');
        var cropper;
        $('[data-toggle="tooltip"]').tooltip();
        input.addEventListener('change', function (e) {
            var files = e.target.files;
            var done = function (url) {
                image.src = url;
                $modal.modal('show');
            };
            if (files && files.length > 0) {
                let file = files[0];
                reader = new FileReader();
                reader.onload = function (e) {
                    done(reader.result);
                };
                reader.readAsDataURL(file);
                input.value = '';
            }
        });
        $modal.on('shown.bs.modal', function () {
            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 3,
            });
        }).on('hidden.bs.modal', function () {
            cropper.destroy();
            cropper = null;
            image.src = '';
        });
        cropBtn.addEventListener('click', function () {
            var canvas;
            $modal.modal('hide');
            if (cropper) {
                canvas = cropper.getCroppedCanvas({
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
                    $.ajax("{{ route('uploadStaffProfile') }}", {
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            avatar.src = data.src;
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