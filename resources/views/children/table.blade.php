<table id="staffTable" class="table table-style table-bordered" style="width:100%">
    <thead>
        <tr>
            <th style="width: 2%"><input type="checkbox" class="mainCheckbox"></th>
            @include('components.table-heading', ['label' => __('children.name'), 'key' => 'name', 'width' => '11.75%'])
            @include('components.table-heading', ['label' => __('children.familyName'), 'key' => 'family_name', 'width' => '11.75%'])
            @include('components.table-heading', ['label' => __('children.identification'), 'key' => 'identification', 'width' => '11.75%'])
            @include('components.table-heading', ['label' => __('children.dob'), 'key' => 'dob', 'width' => '11.75%'])
            @include('components.table-heading', ['label' => __('children.address'), 'key' => 'address', 'width' => '11.75%'])
            @include('components.table-heading', ['label' => __('children.kindergarten'), 'key' => 'kindergarten_id', 'width' => '11.75%'])
            @include('components.table-heading', ['label' => __('comon.action'), 'width' => '4%'])
        </tr>
    </thead>
    <tbody>
        @forelse ($childrens as $children)
            @php
                $truncatedAddress = \Str::limit($children->address, 80, '...');
            @endphp
            <tr class="tr-{{ $children->id }} {{$children->status == 'inactive' ? $children->status : ''}}">
                <td><input type="checkbox" name="id[]" value="{{ $children->id }}" class="checkbox check-{{ $children->id }}" data-class="check-{{ $children->id }}" ></td>
                <td>{{ $children->name }}</td>
                <td>{{ $children->family_name }}</td>
                <td>{{ $children->identification }}</td>
                <td>{{ $children->date_of_birth }}</td>
                <td class="address-column">
                    <span data-toggle="tooltip" data-placement="bottom" title="{{ $children->address }}">{{ $truncatedAddress }}</span>
                </td>
                <td>{{ @getKindergartenNameById($children->kindergarten_id) ?? '-' }}</td>
                <td>
                    <a href="{{ route('children.show', $children->id) }}?kindergarten_id={{ request()->kindergarten_id }}" data-toggle="tooltip" data-placement="bottom" title="{{ __('comon.view') }}">
                        <img src="{{ asset('assets/icons/child-icon-new.png') }}" width="30px" alt="">
                    </a>
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
