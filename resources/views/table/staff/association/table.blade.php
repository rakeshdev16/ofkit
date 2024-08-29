<table id="staffTable" class="table table-style table-bordered" style="width:100%">
    <thead>
        <tr>
            <th><input type="checkbox" class="mainCheckbox"></th>
            @include('components.table-heading', ['label' => 'Name', 'key' => 'name'])
            @include('components.table-heading', ['label' => __('comon.action')])
        </tr>
    </thead>
    <tbody>
        @forelse ($associations as $association)
            <tr class="tr-{{ $association->id }}">
                <td>
                    <input
                        type="checkbox"
                        name="id[]"
                        value="{{ $association->id }}"
                        class="checkbox"
                        data-name="{{ $association->is_assign ? $association->name.' has assigned to staff members' : '' }}"
                    >
                </td>
                <td>{{ @$association->name ?? '-' }}</td>
                <td>
                    <a href="{{ route('staff-table.edit', $association->id) }}?type=association" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="bx bx-edit icon"></i></a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="2" class="text-center">{{ __('comon.emptyTableMsg') }}</td>
            </tr>
        @endforelse
    </tbody>
</table>
{{-- <div class="dataTables_paginate paging_simple_numbers mt-3" id="paginate">
    @include('components.pagination', ['paginate' => $associations])
</div> --}}
