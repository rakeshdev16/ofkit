<table id="staffTable" class="table table-style table-bordered" style="width:100%">
    <thead>
        <tr>
            @if (Auth::user()->hasRole('admin'))
                <th><input type="checkbox" class="mainCheckbox"></th>
            @endif
            @include('components.table-heading', ['label' => __('staff.nameTh'), 'key' => 'name'])
            @include('components.table-heading', ['label' => __('staff.birthDateTh'), 'key' => 'dob'])
            @include('components.table-heading', ['label' => __('staff.addressTh'), 'key' => 'address'])
            @include('components.table-heading', ['label' => __('staff.telephoneTh'), 'key' => 'telephone'])
            @include('components.table-heading', ['label' => __('staff.emailTh'), 'key' => 'email'])
            @include('components.table-heading', ['label' => __('staff.professionTh'), 'key' => 'profession_id'])
            @include('components.table-heading', ['label' => __('staff.licenceNumberTh'), 'key' => 'licence_number'])
            @include('components.table-heading', ['label' => __('staff.roleTh')])
            @include('components.table-heading', ['label' => __('staff.kindergartenTh')])
            @include('components.table-heading', ['label' => 'Action'])
        </tr>
    </thead>
    <tbody>
        @forelse ($members as $member)
            <tr class="tr-{{ $member->id }}">
                @if (Auth::user()->hasRole('admin'))
                    <td><input type="checkbox" name="id[]" value="{{ $member->id }}" class="checkbox"></td>
                @endif
                <td>{{ $member->name }}</td>
                <td>{{ $member->dob }}</td>
                <td>{{ $member->address }}</td>
                <td>{{ $member->telephone }}</td>
                <td>{{ $member->email }}</td>
                <td>{{ @$member->profession->name }}</td>
                <td>{{ $member->licence_number }}</td>
                <td>{{ $member->getRoleNames()->first() }}</td>
                <td>
                    @foreach ($member->staffKindergartens as $staffKindergarten)
                        {{ @$staffKindergarten->kindergartens->name }} {{ !$loop->last ? ',' : '' }}
                    @endforeach
                </td>
                <td>
                    <a href="{{ route('staff.edit', $member->id) }}" class=""><i class="bx bx-edit"></i></a>
                    <a href="{{ route('staff.show', $member->id) }}" class=""><i class="bx bx-show"></i></a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="11" class="text-center">{{ __('comon.emptyTableMsg') }}</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="dataTables_paginate paging_simple_numbers mt-3" id="paginate">
    @include('components.pagination', ['paginate' => $members])
</div>
