<label for="input16" class="form-label">{{ $label }}</label>
<div class="position-relative">
    <input
        type="time"
        class="form-control @error($name) is-invalid @enderror"
        name="{{ $name }}"
        placeholder="{{ $label }}"
        value="{{ old($name) ? old($name) : @$value }}"
        max="{{ @$max }}"
        {{ @$disabled }}
    >
</div>
@error($name)
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
@enderror
