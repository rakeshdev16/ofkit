<table id="diagnosisTable" class="table table-style table-bordered" style="width:100%">
    <thead>
        <tr>
            <th><input type="checkbox" class="mainCheckbox"></th>
            @include('components.table-heading', ['label' => 'Name', 'key' => 'name'])
            @include('components.table-heading', ['label' => 'Action'])
        </tr>
    </thead>
    <tbody>
        @forelse ($diagnosises as $diagnosis)
            <tr class="tr-{{ $diagnosis->id }}">
                <td>
                    <input
                        type="checkbox"
                        name="id[]"
                        value="{{ $diagnosis->id }}"
                        class="checkbox"
                        data-name="{{ $diagnosis->is_assign ? $diagnosis->name.' has assigned to children' : '' }}"
                    >
                </td>
                <td>{{ $diagnosis->name }}</td>
                <td>
                    <a href="{{ route('children-table.edit', $diagnosis->id) }}?type=diagnosis" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="bx bx-edit icon"></i></a>
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
    @include('components.pagination', ['paginate' => $diagnosises])
</div>
