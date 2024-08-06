<table id="staffTable" class="table table-style table-bordered" style="width:100%">
    <thead>
        <tr>
            <th style="width: 2%;"><input type="checkbox" class="mainCheckbox"></th>
            @include('components.table-heading', ['label' => __('cluster.clusterTh'), 'key' => 'cluster', 'width' => '30%'])
            @include('components.table-heading', ['label' => 'Kindergartens', 'width' => '30%'])
            @include('components.table-heading', ['label' => __('cluster.managerTh'), 'key' => 'manager', 'width' => '30%'])
            @include('components.table-heading', ['label' => 'Action', 'width' => '5%'])
        </tr>
    </thead>
    <tbody>
        @forelse ($clusters as $cluster)
            <tr class="tr-{{ $cluster->id }}">
                <td><input type="checkbox" name="id[]" value="{{ $cluster->id }}" class="checkbox check-{{ $cluster->id }}" data-class="check-{{ $cluster->id }}"></td>
                <td>{{ $cluster->cluster }}</td>
                <td>
                    @forelse ($cluster->kindergartens->take(2) as $kindergarten)
                        {{ getKindergartenNameById($kindergarten->kindergarten_id) }}{{ !$loop->last ? ', ' : '' }}
                    @empty
                        -
                    @endforelse
                    {{ count($cluster->kindergartens) > 2 ? '...' : '' }}
                </td>
                <td>{{ @$cluster->manager->name ?? '-' }}</td>
                <td>
                    <a href="{{ route('cluster.edit', $cluster->id) }}" class=""><i class="bx bx-edit icon"></i></a>
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
    @include('components.pagination', ['paginate' => $clusters])
</div>
