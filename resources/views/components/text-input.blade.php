<label for="input16" class="form-label">{{ $label }}</label>
<div class="position-relative input-icon">
    <input
        type="text"
        class="form-control @error($name) is-invalid @enderror {{ @$class }}"
        name="{{ $name }}"
        placeholder="{{ $label }}"
        value="{{ old($name) ? old($name) : @$value }}"
        {{ @$readonly == true ? 'readonly' : '' }}
    >
    <span class="position-absolute top-50 translate-middle-y">
        <i class="bx bx-{{ $icon }}"></i>
    </span>
</div>
@error($name)
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
@enderror
