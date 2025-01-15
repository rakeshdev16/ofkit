@if(gettype($kindergarten_id) == 'array')
    <button type="button" class="btn" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="{!! html_entity_decode(@getKindergartenInfoByIds($kindergarten_id)) ?? '-' !!}"><i class="fadeIn animated bx bx-info-circle"></i></button>
@else
    <button type="button" class="btn" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="{{ @getKindergartenInfoById($kindergarten_id) ?? '-' }}"><i class="fadeIn animated bx bx-info-circle"></i></button>
@endif

