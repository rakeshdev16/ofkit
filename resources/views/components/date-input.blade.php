<label for="input16" class="form-label">{{ $label }}</label>
<div class="position-relative input-icon">
    <input type="date" class="form-control {{ @$class }} @error($name) is-invalid @enderror" name="{{ $name }}" placeholder="{{ $label }}" value="{{ old($name) ? old($name) : @$value }}" max="{{ @$max }}" {{ @$disabled }} {{ @$readonly == true ? 'readonly' : '' }} dir="ltr" style="padding-right: 10px !important;">
</div>
@error($name)
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
@enderror
