<table id="staffTable" class="table table-style table-bordered" style="width:100%">
    <thead>
        <tr>
            <th><input type="checkbox" class="mainCheckbox"></th>
            @include('components.table-heading', ['label' => 'Name', 'key' => 'name'])
            @include('components.table-heading', ['label' => 'Action'])
        </tr>
    </thead>
    <tbody>
        @forelse ($documents as $document)
            <tr class="tr-{{ $document->id }}">
                <td>
                    <input
                        type="checkbox"
                        name="id[]"
                        value="{{ $document->id }}"
                        class="checkbox"
                        data-name="{{ $document->is_assign ? $document->name.' has assigned to children document' : '' }}"
                    >
                </td>
                <td>{{ @$document->name ?? '-' }}</td>
                <td>
                    <a href="{{ route('intervention.edit', $document->id) }}?type=documents-and-approval" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="bx bx-edit icon"></i></a>
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
    @include('components.pagination', ['paginate' => $documents])
</div>
