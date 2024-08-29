<div class="row w-100 align-items-center" style="margin-left: 30px;">
    <div class="col-2 d-flex justify-content-center">
        <input
            type="checkbox"
            name="id[]"
            value="{{ @$id }}"
            class="accordionCheckbox checkbox"
            data-name="{{ @$dataName }}"
        >&nbsp;&nbsp;
        {{-- <input type="checkbox" value="{{ @$id }}" class="accordionCheckbox check-{{ $id }}" data-class="check-{{ $id }}">&nbsp;&nbsp; --}}
    </div>
    <div class="col-8">{{ \Str::limit($name, 10, '...') ?? '-' }}</div>
    <div class="col-2 d-flex justify-content-center">
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