<label for="input16" class="form-label">{{ $label }}</label>
<div class="position-relative input-icon">
    <select name="{{ $name }}" class="form-control @error($name) is-invalid @enderror">
        <option value="">Select</option>
        @foreach ($options as $option)
            <option {{ (old($name) ?? @$value) == $option['key'] ? 'selected' : '' }} value="{{ $option['key'] }}">{{ ucfirst($option['value']) }}</option>
        @endforeach
    </select>
    <span class="position-absolute top-50 translate-middle-y">
        <i class="bx bx-{{ $icon }}"></i>
    </span>
</div>
@error($name)
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
@enderror
