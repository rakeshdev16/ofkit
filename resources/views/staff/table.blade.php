<table id="staffTable" class="table table-style table-bordered" style="width:100%">
    <thead>
        <tr>
            @if (Auth::user()->hasRole('admin'))
                <th style="width: 2%"><input type="checkbox" class="mainCheckbox"></th>
            @endif
            @include('components.table-heading', ['label' => __('staff.nameTh'), 'key' => 'first_name', 'width' => '13.44%'])
            @include('components.table-heading', ['label' => __('staff.familyName'), 'key' => 'family_name', 'width' => '13.44%'])
            @include('components.table-heading', ['label' => __('staff.telephoneTh'), 'key' => 'telephone', 'width' => '13.44%'])
            @include('components.table-heading', ['label' => __('staff.emailTh'), 'key' => 'email', 'width' => '13.44%'])
            @include('components.table-heading', ['label' => __('staff.professionTh'), 'key' => 'profession_id', 'width' => '13.44%'])
            @include('components.table-heading', ['label' => __('staff.kindergartenTh'), 'width' => '13.44%'])
            {{-- @include('components.table-heading', ['label' => __('staff.createdAt'), 'key' => 'created_at', 'width' => '13.44%'])
            @include('components.table-heading', ['label' => __('staff.updatedAt'), 'key' => 'updated_at', 'width' => '13.44%']) --}}
            @include('components.table-heading', ['label' => __('comon.action'), 'width' => '4%'])
        </tr>
    </thead>
    <tbody>
        @forelse ($members as $member)
            <tr class="tr-{{ $member->id }}">
                @if (Auth::user()->hasRole('admin'))
                    <td>
                        <input type="checkbox" name="id[]" value="{{ $member->id }}" class="checkbox check-{{ $member->id }}" data-class="check-{{ $member->id }}" data-name="{{ $member->is_assign ? $member->first_name . ' has assigned to kindergarten or cluster' : '' }}">
                        {{-- <input type="checkbox" name="id[]" value="{{ $member->id }}" class="checkbox check-{{ $member->id }}" data-class="check-{{ $member->id }}"> --}}
                    </td>
                @endif
                <td>{{ $member->first_name ?? '-' }}</td>
                <td>{{ $member->family_name ?? '-' }}</td>
                <td>{{ $member->telephone ?? '-' }}</td>
                <td>{{ $member->email ?? '-' }}</td>
                <td>{{ @$member->profession->name ?? '-' }}</td>
                <td>
                    @if ($member->staffKindergartens->count() > 0)
                        @php
                            $kindergartens = getKindergartenNamesById($member->staffKindergartens->pluck('kindergarten_id')->toArray());
                        @endphp
                        {{ \Str::limit(implode(', ', $kindergartens), 35, '...') ?? '-' }}
                    @else
                        -
                    @endif
                </td>
                {{-- <td>{{ date('d/m/Y', strtotime($member->created_at)) }}</td>
                <td>{{ date('d/m/Y', strtotime($member->updated_at)) }}</td> --}}
                <td>
                    {{-- <a
                        href="{{ route('staff.edit', $member->id) }}?kindergarten_id={{ request()->kindergarten_id }}"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Edit"
                    >
                        <i class="bx bx-edit icon"></i>
                    </a> --}}
                    <a href="{{ route('staff.show', $member->id) }}?kindergarten_id={{ request()->kindergarten_id }}" data-toggle="tooltip" data-placement="bottom" title="{{ __('comon.view') }}">
                        <i class="bx bx-show icon"></i>
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
    @include('components.pagination', ['paginate' => $members])
</div>
