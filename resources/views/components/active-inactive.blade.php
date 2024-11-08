<div class="ms-2 align-self-end">
    @if (Auth::user()->hasRole('admin'))
        <select name="status" class="form-select select-padding status py-1">
            <option value="active" {{request()->status == 'active' ? 'selected' : ''}}>{{ __('comon.active') }}</option>
            <option value="inactive" {{request()->status == 'inactive' ? 'selected' : ''}}>{{ __('comon.inactive') }}</option>
        </select>
    @endif
</div>
