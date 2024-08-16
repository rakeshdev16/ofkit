<div class="profile-pic-wrapper">
    <div class="pic-holder">
        <img id="previewImage" class="pic" src="{{ @$src }}">
        <label for="newProfilePhoto" class="upload-file-block">
            <div class="text-center">
                <div class="">
                    <i class="fa fa-trash fa-2x deletePhoto mx-1" data-url="{{ $deleteUrl }}" {{ empty(@$is_uploaded) ? 'hidden' : '' }}></i>
                    <i class="fa fa-camera fa-2x mx-1" id="previewImage"></i>
                </div>
            </div>
        </label>
        <span class="text-danger cropperImageError" role="alert" style="display: none !important;">
            <strong>{{ __('comon.invalidImageFormat') }}</strong>
        </span>
    </div>
</div>
<input type="hidden" name="user_id" id="userId" value="{{ @$userId }}">
<input type="hidden" id="type" value="{{ $type }}">
<input type="hidden" id="url" value="{{ $updateUrl }}">
<input type="file" style="visibility: hidden" name="member_photo" id="profileInp" accept="image/png, image/gif, image/jpeg">