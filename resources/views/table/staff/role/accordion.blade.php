<div class="mx-3 p-1">
    <input type="checkbox" class="mainCheckbox">&nbsp;&nbsp;&nbsp;
</div>
@foreach ($roles as $role)
    <div class="accordion accordion-flush tr-{{ $role->id }}" id="accordion{{ $loop->iteration }}">
        <div class="accordion-item">
            <h2 class="accordion-header" id="staff-listing-{{ $loop->iteration }}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapse{{ $loop->iteration }}" aria-expanded="false"
                    aria-controls="flush-collapse{{ $loop->iteration }}">
                    <div class="row">
                        <div class="col-2">
                            <input type="checkbox" value="{{ $role->id }}" class="checkbox">&nbsp;&nbsp;
                        </div>
                        <div class="col-8">{{ \Str::limit($role->name, 10, '...') ?? '-' }}</div>
                        <div class="col-2 d-flex justify-content-center">
                            <a href="{{ route('staff-table.edit', $role->id) }}?type=role" class=""><i class="bx bx-edit"></i></a>&nbsp;
                            <a href="{{ route('staff-table.show', $role->id) }}?type=role" class=""><i class="bx bx-show"></i></a>
                        </div>
                    </div>
                </button>
            </h2>
            <div id="flush-collapse{{ $loop->iteration }}" class="accordion-collapse collapse"
                aria-labelledby="staff-listing-{{ $loop->iteration }}"
                data-bs-parent="#accordion{{ $loop->iteration }}" style="">
                <div class="accordion-body">
                    <div class="d-flex">
                        <div class="w-50">Name</div>
                        <div class="w-50">{{ $role->name }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
<div class="dataTables_paginate paging_simple_numbers mt-2" id="paginate">
    @include('components.pagination', ['paginate' => $roles])
</div>
