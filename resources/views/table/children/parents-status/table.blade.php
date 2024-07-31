<table id="staffTable" class="table table-style table-bordered" style="width:100%">
    <thead>
        <tr>
            <th><input type="checkbox" class="mainCheckbox"></th>
            @include('components.table-heading', ['label' => 'Name', 'key' => 'name'])
            @include('components.table-heading', ['label' => 'Action'])
        </tr>
    </thead>
    <tbody>
        @forelse ($parentsStatus as $status)
            <tr class="tr-{{ $status->id }}">
                <td>
                    <input
                        type="checkbox"
                        name="id[]"
                        value="{{ $status->id }}"
                        class="checkbox"
                        data-name="{{ $status->is_assign ? $status->name.' has assigned to children' : '' }}"
                    >
                </td>
                <td>{{ $status->name }}</td>
                <td>
                    <a href="{{ route('children-table.edit', $status->id) }}?type=parents-status" class=""><i class="bx bx-edit icon"></i></a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">{{ __('comon.emptyTableMsg') }}</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="dataTables_paginate paging_simple_numbers mt-3" id="paginate">
    @include('components.pagination', ['paginate' => $parentsStatus])
</div>
