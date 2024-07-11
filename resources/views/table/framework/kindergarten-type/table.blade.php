<table id="staffTable" class="table table-style table-bordered" style="width:100%">
    <thead>
        <tr>
            {{-- <th><input type="checkbox" class="mainCheckbox"></th> --}}
            @include('components.table-heading', ['label' => 'Name', 'key' => 'name'])
            @include('components.table-heading', ['label' => 'Action'])
        </tr>
    </thead>
    <tbody>
        @forelse ($kindergartenTypes as $kindergartenType)
            <tr class="tr-{{ $kindergartenType->id }}">
                {{-- <td><input type="checkbox" name="id[]" value="{{ $kindergartenType->id }}" class="checkbox"></td> --}}
                <td>{{ @$kindergartenType->name ?? '-' }}</td>
                <td>
                    <a href="{{ route('framework-table.edit', $kindergartenType->id) }}?type=kindergarten-type" class=""><i class="bx bx-edit icon"></i></a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="2" class="text-center">{{ __('comon.emptyTableMsg') }}</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="dataTables_paginate paging_simple_numbers mt-3" id="paginate">
    @include('components.pagination', ['paginate' => $kindergartenTypes])
</div>
