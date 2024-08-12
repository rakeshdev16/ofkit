<table id="staffTable" class="table table-style table-bordered" style="width:100%">
    <thead>
        <tr>
            <th><input type="checkbox" class="mainCheckbox"></th>
            @include('components.table-heading', ['label' => 'Name', 'key' => 'name'])
            @include('components.table-heading', ['label' => 'Action'])
        </tr>
    </thead>
    <tbody>
        @forelse ($interventionTypes as $interventionType)
            <tr class="tr-{{ $interventionType->id }}">
                <td>
                    <input
                        type="checkbox"
                        name="id[]"
                        value="{{ $interventionType->id }}"
                        class="checkbox"
                        data-name="{{ $interventionType->is_assign ? $interventionType->name.' has assigned to children document' : '' }}"
                    >
                </td>
                <td>{{ @$interventionType->name ?? '-' }}</td>
                <td>
                    <a href="{{ route('intervention.edit', $interventionType->id) }}?type=intervention-type" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="bx bx-edit icon"></i></a>
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
    @include('components.pagination', ['paginate' => $interventionTypes])
</div>
