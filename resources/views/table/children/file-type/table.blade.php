<table id="fileTypeTable" class="table table-style table-bordered" style="width:100%">
    <thead>
        <tr>
            <th><input type="checkbox" class="mainCheckbox"></th>
            @include('components.table-heading', ['label' => __('tables.name'), 'key' => 'name'])
            @include('components.table-heading', ['label' => __('comon.action')])
        </tr>
    </thead>
    <tbody>
        @forelse ($fileTypes as $fileType)
            <tr class="tr-{{ $fileType->id }} {{$fileType->status == 'inactive' ? $fileType->status : ''}}">
                <td>
                    <input type="checkbox" name="id[]" value="{{ $fileType->id }}" class="checkbox check-{{ $fileType->id }}" data-name="{{ $fileType->is_assign ? $fileType->name . ' has assigned to children' : '' }}">
                </td>
                <td>{{ $fileType->name }}</td>
                <td>
                    <a href="{{ route('children-table.edit', $fileType->id) }}?type=file-type" data-toggle="tooltip" data-placement="bottom" title="{{ __('comon.edit') }}"><i class="bx bx-edit icon"></i></a>
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
    @include('components.pagination', ['paginate' => $fileTypes])
</div>
