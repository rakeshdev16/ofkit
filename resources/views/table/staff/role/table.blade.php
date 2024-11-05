<table id="staffTable" class="table table-style table-bordered" style="width:100%">
    <thead>
        <tr>
            <th><input type="checkbox" class="mainCheckbox"></th>
            @include('components.table-heading', ['label' => __('tables.name'), 'key' => 'name'])
            @include('components.table-heading', ['label' => __('comon.action')])
        </tr>
    </thead>
    <tbody>
        @forelse ($roles as $role)
            <tr class="tr-{{ $role->id }}">
                <td>
                    <input type="checkbox" name="id[]" value="{{ $role->id }}" class="checkbox" data-name="{{ $role->is_assign ? $role->name . ' has assigned to staff members' : '' }}">
                </td>
                <td>{{ $role->name }}</td>
                <td>
                    <a href="{{ route('staff-table.edit', $role->id) }}?type=role" data-toggle="tooltip" data-placement="bottom" title="{{ __('comon.edit') }}"><i class="bx bx-edit icon"></i></a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">{{ __('comon.emptyTableMsg') }}</td>
            </tr>
        @endforelse
    </tbody>
</table>
{{-- <div class="dataTables_paginate paging_simple_numbers mt-3" id="paginate">
    @include('components.pagination', ['paginate' => $roles])
</div> --}}
