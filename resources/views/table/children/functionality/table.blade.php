<table id="functionalityTable" class="table table-style table-bordered" style="width:100%">
    <thead>
        <tr>
            <th><input type="checkbox" class="mainCheckbox"></th>
            @include('components.table-heading', ['label' => 'Name', 'key' => 'name'])
            @include('components.table-heading', ['label' => 'Action'])
        </tr>
    </thead>
    <tbody>
        @forelse ($functionalities as $functionality)
            <tr class="tr-{{ $functionality->id }}">
                <td>
                    <input
                        type="checkbox"
                        name="id[]"
                        value="{{ $functionality->id }}"
                        class="checkbox"
                        data-name="{{ $functionality->is_assign ? $functionality->name.' has assigned to children' : '' }}"
                    >
                </td>
                <td>{{ $functionality->name }}</td>
                <td>
                    <a href="{{ route('children-table.edit', $functionality->id) }}?type=functionality" class=""><i class="bx bx-edit icon"></i></a>
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
    @include('components.pagination', ['paginate' => $functionalities])
</div>
