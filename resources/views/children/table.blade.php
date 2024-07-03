<div class="table-search">
    <label>Search: <input type="search" class="search" value="{{ request()->search }}" placeholder=""></label>
</div>
<table id="staffTable" class="table table-style table-bordered" style="width:100%">
    <thead>
        <tr>
            <th><input type="checkbox" class="mainCheckbox"></th>
            @include('components.table-heading', ['label' => __('children.nameTh'), 'key' => 'name'])
            @include('components.table-heading', ['label' => __('children.familyNameTh'), 'key' => 'family_name'])
            @include('components.table-heading', ['label' => __('children.identificationTh'), 'key' => 'identification'])
            @include('components.table-heading', ['label' => __('children.dobTh'), 'key' => 'dob'])
            @include('components.table-heading', ['label' => __('children.addressTh'), 'key' => 'address'])
            @include('components.table-heading', ['label' => __('children.accessRecordsTh'), 'key' => 'access_records'])
            @include('components.table-heading', ['label' => 'Action'])
        </tr>
    </thead>
    <tbody>
        @forelse ($childrens as $children)
            <tr class="tr-{{ $children->id }}">
                <td><input type="checkbox" name="id[]" value="{{ $children->id }}" class="checkbox"></td>
                <td>{{ $children->name }}</td>
                <td>{{ $children->family_name }}</td>
                <td>{{ $children->identification }}</td>
                <td>{{ $children->dob }}</td>
                <td>{{ $children->address }}</td>
                <td>{{ $children->access_records }}</td>
                <td>
                    <a href="{{ route('children.edit', $children->id) }}" class=""><i class="bx bx-edit"></i></a>
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
