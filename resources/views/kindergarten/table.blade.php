<table id="staffTable" class="table table-style table-bordered" style="width:100%">
    <thead>
        <tr>
            <th style="width: 2%"><input type="checkbox" class="mainCheckbox"></th>
            @include('components.table-heading', ['label' => __('kindergarten.nameTh'), 'key' => 'name', 'width' => '9.4%'])
            @include('components.table-heading', ['label' => __('kindergarten.symbolTh'), 'key' => 'symbol', 'width' => '9.4%'])
            {{-- @include('components.table-heading', ['label' => __('kindergarten.frameworkTh'), 'key' => 'framework', 'width' => '9.4%']) --}}
            @include('components.table-heading', ['label' => __('kindergarten.clusterTh'), 'width' => '9.4%'])
            @include('components.table-heading', ['label' => __('kindergarten.clusterManagerTh'), 'width' => '9.4%'])
            @include('components.table-heading', ['label' => __('kindergarten.kindergartenManagerTh'),'width' => '9.4%'])
            @include('components.table-heading', ['label' => __('kindergarten.addressTh'), 'key' => 'address', 'width' => '9.4%'])
            @include('components.table-heading', ['label' => __('kindergarten.telephoneTh'), 'key' => 'telephone', 'width' => '9.4%'])
            {{-- @include('components.table-heading', ['label' => __('kindergarten.createdAt'), 'key' => 'created_at', 'width' => '9.4%'])
            @include('components.table-heading', ['label' => __('kindergarten.updatedAt'), 'key' => 'updated_at', 'width' => '9.4%']) --}}
            @include('components.table-heading', ['label' => __('comon.action'), 'width' => '4%'])
        </tr>
    </thead>
    <tbody>
        @forelse ($kindergartens as $kindergarten)
            <tr class="tr-{{ $kindergarten->id }}">
                <td>
                    <input type="checkbox" name="id[]" value="{{ $kindergarten->id }}" class="checkbox check-{{ $kindergarten->id }}" data-class="check-{{ $kindergarten->id }}" data-name="{{ $kindergarten->is_assign ? $kindergarten->name . ' has assigned to children or staff' : '' }}">
                </td>
                <td>{{ $kindergarten->name }}</td>
                <td>{{ $kindergarten->symbol }}</td>
                {{-- <td>{{ $kindergarten->framework_type }}</td> --}}
                <td>{{ @$kindergarten->cluster->cluster ?? '-' }}</td>
                <td>{{ @getUserNameById($kindergarten->cluster->manager_id) ?? '-' }}</td>
                <td>{{ @getUserNameById($kindergarten->kindergartenUser->user_id) ?? '-' }}</td>
                <td>{{ \Str::limit($kindergarten->address, 20, '...') ?? '-' }}</td>
                <td>{{ $kindergarten->telephone }}</td>
                {{-- <td>{{ date('d/m/Y', strtotime($kindergarten->created_at)) }}</td>
                <td>{{ date('d/m/Y', strtotime($kindergarten->updated_at)) }}</td> --}}
                <td>
                    {{-- <a href="{{ route('kindergarten.edit', $kindergarten->id) }}" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="bx bx-edit icon"></i></a> --}}
                    <a href="{{ route('kindergarten.show', $kindergarten->id) }}" data-toggle="tooltip" data-placement="bottom" title="{{ __('comon.view') }}"><i class="bx bx-show icon"></i></a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="10" class="text-center">{{ __('comon.emptyTableMsg') }}</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="dataTables_paginate paging_simple_numbers mt-3" id="paginate">
    @include('components.pagination', ['paginate' => $kindergartens])
</div>
