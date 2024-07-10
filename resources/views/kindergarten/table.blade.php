<div class="table-search">
    <label>Search: <input type="search" class="search" value="{{ request()->search }}" placeholder=""></label>
</div>
<table id="staffTable" class="table table-style table-bordered" style="width:100%">
    <thead>
        <tr>
            <th><input type="checkbox" class="mainCheckbox"></th>
            @include('components.table-heading', ['label' => __('kindergarten.telephoneTh'), 'key' => 'telephone'])
            @include('components.table-heading', ['label' => __('kindergarten.addressTh'), 'key' => 'address'])
            @include('components.table-heading', ['label' => __('kindergarten.kindergartenManagerTh'), 'key' => 'cluster_manager'])
            @include('components.table-heading', ['label' => __('kindergarten.clusterManagerTh'), 'key' => 'cluster'])
            @include('components.table-heading', ['label' => __('kindergarten.clusterTh'), 'key' => 'cluster'])
            @include('components.table-heading', ['label' => __('kindergarten.typeTh'), 'key' => 'type'])
            @include('components.table-heading', ['label' => __('kindergarten.frameworkTh'), 'key' => 'framework'])
            @include('components.table-heading', ['label' => __('kindergarten.symbolTh'), 'key' => 'symbol'])
            @include('components.table-heading', ['label' => __('kindergarten.nameTh'), 'key' => 'name'])
            @include('components.table-heading', ['label' => 'Action'])
        </tr>
    </thead>
    <tbody>
        @forelse ($kindergartens as $kindergarten)
            <tr class="tr-{{ $kindergarten->id }}">
                <td><input type="checkbox" name="id[]" value="{{ $kindergarten->id }}" class="checkbox"></td>
                <td>{{ $kindergarten->telephone }}</td>
                <td>{{ $kindergarten->address }}</td>
                <td>{{ @getUserNameById($kindergarten->kindergartenUser->user_id) ?? '-' }}</td>
                <td>{{ @getUserNameById($kindergarten->cluster_manager_id) ?? '-' }}</td>
                <td>{{ @$kindergarten->cluster->cluster ?? '-' }}</td>
                <td>{{ $kindergarten->kindergarten_type }}</td>
                <td>{{ $kindergarten->framework_type }}</td>
                <td>{{ $kindergarten->symbol }}</td>
                <td>{{ $kindergarten->name }}</td>
                <td>
                    <a href="{{ route('kindergarten.edit', $kindergarten->id) }}" class=""><i class="bx bx-edit"></i></a>
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
    @include('components.pagination', ['paginate' => $kindergartens])
</div>
