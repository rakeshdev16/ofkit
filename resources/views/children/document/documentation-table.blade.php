<table id="staffTable" class="table table-style table-bordered" style="width:100%">
    <thead>
        <tr>
            {{-- <th><input type="checkbox" class="mainCheckbox"></th> --}}
            @include('components.table-heading', ['label' =>__('children.date'), 'key' => 'created_at'])
            @include('components.table-heading', ['label' => 'Therapist', 'key' => 'kindergarten_id'])
            @include('components.table-heading', ['label' => 'Profession'])
            @include('components.table-heading', ['label' => 'Intervention', 'key' => 'type'])
            @include('components.table-heading', ['label' => 'Occurred', 'key' => 'occured'])
            @include('components.table-heading', ['label' => __('children.description'), 'key' => 'occured_description'])
            @include('components.table-heading', ['label' => 'Attacted File'])
            @include('components.table-heading', ['label' => 'Action'])
        </tr>
    </thead>
    <tbody>
        @forelse ($documentations as $documentation)
            @php
                $truncatedDesc = \Str::limit($documentation->occured_description, 80, '...');
                $groupChildDetail = getDocGroupChildDetail($documentation->id, $children->id);
            @endphp
            <tr class="tr-{{ $documentation->id }}">
                {{-- <td><input type="checkbox" name="id[]" value="{{ $documentation->id }}" class="checkbox check-{{ $documentation->id }}" data-class="check-{{ $documentation->id }}"></td> --}}
                <td>{{ date('d/m/Y', strtotime($documentation->date)) }}</td>
                <td>{{ $documentation->therapist->name ?? '-' }}</td>
                <td>{{ $documentation->therapist->profession->name ?? '-' }}</td>
                <td>{{ ucfirst(str_replace('-', ' ', $documentation->type)) }}</td>
                <td>
                    @if ($documentation->type == 'group' && $groupChildDetail)
                        {{ $groupChildDetail->participated == 1 ? 'Yes' : 'No' }}
                    @else
                        {{ $documentation->occured == 1 ? 'Yes' : 'No' }}
                    @endif
                </td>
                <td class="{{ $documentation->occured == 1 ? 'address-column' : '' }}">
                    @if ($documentation->occured == 1)
                        @if ($documentation->type == 'group')
                            @if ($groupChildDetail)
                                @php
                                    $truncatedGroupDesc = $groupChildDetail->participated == 1 ? \Str::limit($groupChildDetail->description, 80, '...') : $groupChildDetail->reason;
                                @endphp
                                <span data-toggle="tooltip" data-placement="bottom" title="{{ $groupChildDetail->description }}">{{ $truncatedGroupDesc }}</span>
                            @else
                                <span data-toggle="tooltip" data-placement="bottom" title="{{ $documentation->occured_description }}">{{ $documentation->group_name }}: <br> {{ $truncatedDesc }}</span>
                            @endif
                        @else
                            <span data-toggle="tooltip" data-placement="bottom" title="{{ $documentation->occured_description }}">{{ $truncatedDesc }}</span>
                        @endif
                    @else
                        {{ $documentation->occured_reason }}
                    @endif
                </td>
                {{-- <td>{{ $documentation->occured == 1 ? \Str::limit($documentation->occured_description, 20, '...') : $documentation->occured_reason }}</td> --}}
                <td>
                    @if ($documentation->file)
                        <a href="{{ $documentation->file }}" target="_blank"><h4><i class="bx bx-file"></i></h4></a>
                    @else
                        -
                    @endif
                </td>
                <td>
                    <a
                        href="{{ route('children-documentation.show', [
                            $documentation->children_id,
                            $documentation->id,
                            Request::segment(2)
                        ]) }}"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="View"
                    >
                        <i class="bx bx-show icon"></i>
                    </a>
                    <a
                        href="{{ route('children-documentation.get', [
                            $documentation->type,
                            Request::segment(2),
                            $documentation->id
                        ]) }}"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Edit"
                    >
                        <i class="bx bx-edit icon"></i>
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
    @include('components.pagination', ['paginate' => $documentations])
</div>
