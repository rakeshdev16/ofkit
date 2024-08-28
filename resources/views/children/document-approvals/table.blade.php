<table id="staffTable" class="table table-style table-bordered" style="width:100%">
    <thead>
        <tr>
            <th style="width: 2%"><input type="checkbox" class="mainCheckbox"></th>
            @include('components.table-heading', ['label' => 'Document', 'key' => 'document', 'width' => '11.75%'])
            @include('components.table-heading', ['label' => 'Action', 'width' => '5%'])
        </tr>
    </thead>
    <tbody>
        @forelse ($documents as $document)
        @php
            $fileName = explode('child-document/', $document->document)[1];
        @endphp
            <tr class="tr-{{ $document->id }}">
                <td><input type="checkbox" name="id[]" value="{{ $document->id }}" class="checkbox check-{{ $document->id }}" data-class="check-{{ $document->id }}"></td>
                <td>
                    @if ($document->document)
                        <span>{{ $fileName }}</span>
                    @else
                        -
                    @endif
                </td>
                <td>
                    <a href="{{ $document->document }}" target="__blank" data-toggle="tooltip" data-placement="bottom" title="View"><i class="bx bx-show icon"></i></a>
                    <a href="{{ $document->document }}" download="{{ $fileName }}" data-toggle="tooltip" data-placement="bottom" title="Download">
                        <i class="bx bx-download icon"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="2" class="text-center">{{ __('comon.emptyTableMsg') }}</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="dataTables_paginate paging_simple_numbers mt-3" id="paginate">
    @include('components.pagination', ['paginate' => $documents])
</div>
