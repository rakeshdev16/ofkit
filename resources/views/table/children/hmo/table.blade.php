<table id="staffTable" class="table table-style table-bordered" style="width:100%">
    <thead>
        <tr>
            <th><input type="checkbox" class="mainCheckbox"></th>
            @include('components.table-heading', ['label' => 'Name', 'key' => 'name'])
            @include('components.table-heading', ['label' => 'Action'])
        </tr>
    </thead>
    <tbody>
        @forelse ($hmos as $hmo)
            <tr class="tr-{{ $hmo->id }}">
                <td>
                    <input
                        type="checkbox"
                        name="id[]"
                        value="{{ $hmo->id }}"
                        class="checkbox"
                        data-name="{{ $hmo->is_assign ? $hmo->name.' has assigned to children' : '' }}"
                    >
                </td>
                <td>{{ $hmo->name }}</td>
                <td>
                    <a href="{{ route('children-table.edit', $hmo->id) }}?type=hmo" class=""><i class="bx bx-edit icon"></i></a>
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
    @include('components.pagination', ['paginate' => $hmos])
</div>
