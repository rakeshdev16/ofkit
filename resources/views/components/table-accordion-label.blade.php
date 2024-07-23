<div class="row w-100 align-items-center" style="margin-left: 4px;">
    <div class="col-8">{{ \Str::limit($name, 10, '...') ?? '-' }}</div>
    <div class="col-4 d-flex justify-content-center">
        @isset($edit)
            <a href="{{ $edit }}" class="me-2"><i class="bx bx-edit icon"></i></a>
        @endisset
        @isset($show)
            <a href="{{ $show }}" class="me-2"><i class="bx bx-show icon"></i></a>
        @endisset
    </div>
</div>