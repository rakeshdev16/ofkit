<label for="input16" class="form-label">{{ $label }}</label>
@php
    $dateValue = @$value ?? '';
@endphp
<div class="position-relative input-icon">
    <input type="text" class="form-control datepicker {{ @$class }} @error($name) is-invalid @enderror" name="{{ $name }}" placeholder="dd/mm/yyyy" value="{{ old($name) ? old($name) : $dateValue }}" {{ isset($max) ? "max=$max" : '' }}  {{ @$disabled }} {{ @$readonly == true ? 'readonly' : '' }} dir="ltr" style="padding-right: 10px !important;">
</div>
@error($name)
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
@enderror
