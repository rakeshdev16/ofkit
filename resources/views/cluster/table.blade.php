<table id="staffTable" class="table table-style table-bordered" style="width:100%">
    <thead>
        <tr>
            <th style="width: 5%;"><input type="checkbox" class="mainCheckbox"></th>
            @include('components.table-heading', ['label' => __('cluster.clusterTh'), 'key' => 'cluster', 'width' => '30%'])
            @include('components.table-heading', ['label' => __('cluster.kindergartens'), 'width' => '30%'])
            @include('components.table-heading', ['label' => __('cluster.managerTh'), 'key' => 'manager', 'width' => '30%'])
            @include('components.table-heading', ['label' => __('comon.action'), 'width' => '5%'])
        </tr>
    </thead>
    <tbody>
        @forelse ($clusters as $cluster)
            <tr class="tr-{{ $cluster->id }}">
                <td>
                    <input
                        type="checkbox"
                        name="id[]"
                        value="{{ $cluster->id }}"
                        class="checkbox check-{{ $cluster->id }}"
                        data-class="check-{{ $cluster->id }}"
                        data-name="{{ $cluster->is_assign ? $cluster->cluster.' has assigned to kindergarten' : '' }}"
                    >
                </td>
                <td>{{ $cluster->cluster }}</td>
                <td>
                    @php
                        $kindergartens = $cluster->kindergartens->pluck('name')->toArray();
                    @endphp
                    <span data-toggle="tooltip" data-placement="bottom" title="{{ implode(', ', $kindergartens) }}" style="cursor: default">
                        {{ \Str::limit(implode(', ', $kindergartens), 90, '...') ?? '-' }}
                    </span>
                </td>
                <td>{{ @$cluster->manager->name ?? '-' }}</td>
                <td>
                    <a href="{{ route('cluster.edit', $cluster->id) }}" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="bx bx-edit icon"></i></a>
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
