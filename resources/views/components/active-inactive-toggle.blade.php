<div class="d-flex align-items-center gap-3">
    <div class="form-check form-switch">
        <input
            name="status"
            type="checkbox"
            role="switch"
            id="activeInactiveToggleBtn"
            class="form-check-input activeInactive checkbox check-{{ $statusCheck->id }}" data-class="check-{{ $statusCheck->id }}"
            data-name="{{$dataName}}"
            value="{{$statusCheck->status == 'active' || '' ? 'active' : 'inactive'}}" {{$statusCheck->status == 'active' || '' ? 'checked' : ''}}
        >
        <label class="form-check-label" for="activeInactiveToggleBtn">{{__('comon.active')}}/{{__('comon.inactive')}}</label>
    </div>
</div>
