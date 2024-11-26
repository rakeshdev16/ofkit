@isset($label)
    <label for="input16" class="form-label">{{ $label }}</label>
@endisset
<div class="position-relative input-icon">
    <div class="form-check form-check-inline">
        <input
            class="form-check-input @error($name) is-invalid @enderror {{ $class }}"
            type="radio"
            name="{{ $name }}"
            id="{{ $class }}1"
            value="1"
            {{ @$disabled }}
            {{ @$required }}
            {{ @$onchange }}
            {{ (old($name) ?? @$value) == '1' ? 'checked' : '' }}
        >
        <label class="form-check-label" for="{{ $class }}1">{{__('comon.yes')}}</label>
    </div>
    <div class="form-check form-check-inline">
        <input
            class="form-check-input @error($name) is-invalid @enderror {{ $class }}"
            type="radio"
            name="{{ $name }}"
            id="{{ $class }}2"
            value="0"
            {{ @$disabled }}
            {{ @$required }}
            {{ @$onchange }}
            {{ (old($name) ?? @$value) == '0' ? 'checked' : '' }}
        >
        <label class="form-check-label" for="{{ $class }}2">{{__('comon.no')}}</label>
    </div>
</div>
@error($name)
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
@enderror
