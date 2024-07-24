<label for="input16" class="form-label">{{ $label }}</label>
<div class="position-relative input-icon">
    <textarea
        name="{{ $name }}"
        class="form-control  @error($name) is-invalid @enderror {{ @$class }}"
        placeholder="{{ $label }}"
        cols="30"
        rows="2"
        {{ @$readonly == true ? 'readonly' : '' }}
        {{ @$disabled }}
    >
        {{ old($name) ? old($name) : @$value }}
    </textarea>
    <span class="position-absolute top-50 translate-middle-y">
        <i class="bx bx-{{ $icon }}"></i>
    </span>
</div>
@error($name)
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
@enderror
