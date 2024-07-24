<table id="staffTable" class="table table-style table-bordered" style="width:100%">
    <thead>
        <tr>
            @if (Auth::user()->hasRole('admin'))
                <th><input type="checkbox" class="mainCheckbox"></th>
            @endif
            @include('components.table-heading', ['label' => __('staff.nameTh'), 'key' => 'name'])
            @include('components.table-heading', ['label' => __('staff.telephoneTh'), 'key' => 'telephone'])
            @include('components.table-heading', ['label' => __('staff.emailTh'), 'key' => 'email'])
            @include('components.table-heading', ['label' => __('staff.professionTh'), 'key' => 'profession_id'])
            @include('components.table-heading', ['label' => __('staff.kindergartenTh')])
            @include('components.table-heading', ['label' => __('staff.createdAt'), 'key' => 'profession_id'])
            @include('components.table-heading', ['label' => __('staff.updatedAt'), 'key' => 'profession_id'])
            @include('components.table-heading', ['label' => 'Action'])
        </tr>
    </thead>
    <tbody>
        @forelse ($members as $member)
            <tr class="tr-{{ $member->id }}">
                @if (Auth::user()->hasRole('admin'))
                    <td><input type="checkbox" name="id[]" value="{{ $member->id }}" class="checkbox check-{{ $member->id }}" data-class="check-{{ $member->id }}"></td>
                @endif
                <td>{{ $member->name ?? '-' }}</td>
                <td>{{ $member->telephone ?? '-' }}</td>
                <td>{{ $member->email ?? '-' }}</td>
                <td>{{ @$member->profession->name ?? '-' }}</td>
                <td>
                    @if ($member->staffKindergartens->count() > 0)
                        @foreach ($member->staffKindergartens->take(1) as $staffKindergarten)
                            {{ $staffKindergarten->kindergartens->name }}@if (!$loop->last), @endif
                        @endforeach
                        @if ($member->staffKindergartens->count() > 2)
                            ...
                        @endif
                    @else
                        <span class="light-gray">No kindergartens available</span>
                    @endif
                </td>
                <td>{{ date('d/m/Y', strtotime($member->created_at)) }}</td>
                <td>{{ date('d/m/Y', strtotime($member->updated_at)) }}</td>
                <td>
                    <a href="{{ route('staff.edit', $member->id) }}" class=""><i class="bx bx-edit icon"></i></a>
                    <a href="{{ route('staff.show', $member->id) }}" class=""><i class="bx bx-show icon"></i></a>
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
