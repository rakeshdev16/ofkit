@isset($label)
    <label for="input16" class="form-label">{{ $label }}</label>
@endisset
<div class="position-relative input-icon @isset($multiple) multiple-selection @endisset">
    <select
        name="{{ $name }}"
        class="form-control @error($name) is-invalid @enderror {{ @$class }}"
        {{ @$multiple }}
        {{ @$disabled }}
        {{ @$required }}
    >
        @if (!@$multiple)
            <option value="">Select</option>
        @endif
        @foreach ($options as $option)
            <option
                @if (@$multiple && @$value)
                    {{ in_array($option['key'], @$value) ? 'selected' : '' }}
                @else
                    {{ (old($name) ?? @$value) == $option['key'] ? 'selected' : '' }}
                @endif
                value="{{ $option['key'] }}"
            >
                {{ ucfirst($option['value']) }}
            </option>
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
