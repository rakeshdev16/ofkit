<table id="staffTable" class="table table-style table-bordered" style="width:100%">
    <thead>
        <tr>
            <th><input type="checkbox" class="mainCheckbox"></th>
            @include('components.table-heading', ['label' => 'Name', 'key' => 'name'])
            @include('components.table-heading', ['label' => 'Action'])
        </tr>
    </thead>
    <tbody>
        @forelse ($frameworkTypes as $frameworkType)
            <tr class="tr-{{ $frameworkType->id }}">
                <td>
                    <input
                        type="checkbox"
                        name="id[]"
                        value="{{ $frameworkType->id }}"
                        class="checkbox"
                        data-name="{{ $frameworkType->is_assign ? $frameworkType->name.' has assigned to kindergarten' : '' }}"
                    >
                </td>
                <td>{{ @$frameworkType->name ?? '-' }}</td>
                <td>
                    <a href="{{ route('framework-table.edit', $frameworkType->id) }}?type=framework-type" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="bx bx-edit icon"></i></a>
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
    @include('components.pagination', ['paginate' => $frameworkTypes])
</div>
