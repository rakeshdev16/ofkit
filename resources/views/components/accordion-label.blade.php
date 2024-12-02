<div class="row w-100 align-items-center" style="">
    <div class="col-2 d-flex justify-content-center">
        <input type="checkbox" name="id[]" value="{{ @$id }}" class="accordionCheckbox {{@$checkClass}}" data-name="{{ @$dataName }}">&nbsp;&nbsp;
        {{-- <input type="checkbox" value="{{ @$id }}" class="accordionCheckbox check-{{ $id }}" data-class="check-{{ $id }}">&nbsp;&nbsp; --}}
    </div>
    <div class="col-{{ @$edit && @$show ? '7' : '8' }} name-col">{{ \Str::limit($name, 25, '...') ?? '-' }}</div>
    <div class="col-{{ @$edit && @$show ? '3' : '2' }} d-flex">
        @isset($edit)
            <a href="{{ $edit }}" class="me-3"><i class="bx bx-edit icon"></i></a>
        @endisset
        @isset($show)
            <a href="{{ $show }}" target="{{ @$targetBlank }}" class="me-4"><i class="bx bx-show icon"></i></a>
        @endisset
        @isset($download)
            <a href="{{ $document['url'] }}" download="{{ $download['fileName'] }}" data-toggle="tooltip" data-placement="bottom" title="Download">
                <i class="bx bx-download icon"></i>
            </a>
        @endisset
    </div>
</div>
