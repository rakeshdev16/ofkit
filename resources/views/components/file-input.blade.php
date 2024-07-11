<label for="input16" class="form-label">{{ $label }}</label>
<div class="position-relative input-icon">
    <input
        type="file"
        class="form-control @error($name) is-invalid @enderror"
        id="imgInp"
        name="{{ $name }}"
        placeholder="{{ $label }}"
    >
    @if ($fileType = 'document')
        <iframe class="pt-2 {{ @$value ? '' : 'd-none' }}" id="previewImage" src="{{ @$value }}" frameborder="0" width="100%" height="400px"></iframe>
    @else
        <img class="pt-2 {{ @$value ? '' : 'd-none' }}" id="previewImage" src="{{ @$value }}" width="50px" height="50px" alt="">
    @endif
</div>
@error($name)
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
@enderror
