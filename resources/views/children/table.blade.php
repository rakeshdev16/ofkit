<table id="staffTable" class="table table-style table-bordered" style="width:100%">
    <thead>
        <tr>
            <th><input type="checkbox" class="mainCheckbox"></th>
            @include('components.table-heading', ['label' => __('children.name'), 'key' => 'name'])
            @include('components.table-heading', ['label' => __('children.familyName'), 'key' => 'family_name'])
            @include('components.table-heading', ['label' => __('children.identification'), 'key' => 'identification'])
            @include('components.table-heading', ['label' => __('children.dob'), 'key' => 'dob'])
            @include('components.table-heading', ['label' => __('children.address'), 'key' => 'address'])
            @include('components.table-heading', ['label' => __('children.kindergarten'), 'key' => 'address'])
            @include('components.table-heading', ['label' => 'Action'])
        </tr>
    </thead>
    <tbody>
        @forelse ($childrens as $children)
            <tr class="tr-{{ $children->id }}">
                <td><input type="checkbox" name="id[]" value="{{ $children->id }}" class="checkbox check-{{ $children->id }}" data-class="check-{{ $children->id }}"></td>
                <td>{{ $children->name }}</td>
                <td>{{ $children->family_name }}</td>
                <td>{{ $children->identification }}</td>
                <td>{{ $children->date_of_birth }}</td>
                <td>{{ \Str::limit($children->address, 20, '...') ?? '-' }}</td>
                <td>{{ @getKindergartenNameById($children->kindergarten_id) ?? '-' }}</td>
                <td>
                    <a href="{{ route('children.edit', $children->id) }}" class=""><i class="bx bx-edit icon"></i></a>
                    <a href="{{ route('children.show', $children->id) }}" class=""><i class="bx bx-show icon"></i></a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">{{ __('comon.emptyTableMsg') }}</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="dataTables_paginate paging_simple_numbers mt-3" id="paginate">
    @include('components.pagination', ['paginate' => $childrens])
</div>
