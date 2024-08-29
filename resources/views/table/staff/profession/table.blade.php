<table id="staffTable" class="table table-style table-bordered" style="width:100%">
    <thead>
        <tr>
            <th><input type="checkbox" class="mainCheckbox"></th>
            @include('components.table-heading', ['label' => 'Name', 'key' => 'name'])
            @include('components.table-heading', ['label' => __('comon.action')])
        </tr>
    </thead>
    <tbody>
        @forelse ($professions as $profession)
            <tr class="tr-{{ $profession->id }}">
                <td>
                    <input
                        type="checkbox"
                        name="id[]"
                        value="{{ $profession->id }}"
                        class="checkbox"
                        data-name="{{ $profession->is_assign ? $profession->name.' has assigned to staff members' : '' }}"
                    >
                </td>
                <td>{{ @$profession->name ?? '-' }}</td>
                <td>
                    <a href="{{ route('staff-table.edit', $profession->id) }}?type=profession" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="bx bx-edit icon"></i></a>
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
    @include('components.pagination', ['paginate' => $professions])
</div> --}}
