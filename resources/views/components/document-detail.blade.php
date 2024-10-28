<div class="row doc{{ @$id }}">
    <div class="col-md-12 mt-2">
        <div class="document my-1">{{ @$name }}<i class="bx bx-x {{ @$class }}" data-file-name="{{ @$name }}" data-id="{{ @$id }}"></i></div>
    </div>
    <div class="col-md-12">
        <textarea name="document_description[{{ $index }}]" class="form-control" cols="30" rows="5">{{ @$description }}</textarea>
    </div>
    <input type="hidden" name="document_id[{{ $index }}]" value="{{ @$id }}">
</div>
