<table id="staffTable" class="table table-style table-bordered" style="width:100%">
    <thead>
        <tr>
            <th style="width: 2%"><input type="checkbox" class="mainCheckbox"></th>
            @include('components.table-heading', ['label' => __('children.date'), 'key' => 'created_at', 'width' => '11.75%'])
            {{-- @include('components.table-heading', ['label' => __('children.document'), 'key' => 'document', 'width' => '11.75%']) --}}
            @include('components.table-heading', ['label' => __('children.fileType'), 'key' => 'file_type_id', 'width' => '11.75%'])
            @include('components.table-heading', ['label' => __('children.documentDescription'), 'key' => 'desription', 'width' => '11.75%'])
            @include('components.table-heading', ['label' => __('comon.action'), 'width' => '5%'])
        </tr>
    </thead>
    <tbody>
        @forelse ($documents as $document)
            @php
                $fileName = explode('child-document/', $document->document)[1];
            @endphp
            <tr class="tr-{{ $document->id }}">
                <td><input type="checkbox" name="id[]" value="{{ $document->id }}" class="checkbox check-{{ $document->id }}" data-class="check-{{ $document->id }}"></td>
                <td>{{ date('d/m/Y', strtotime($document->created_at)) }}</td>
                {{-- <td>
                    @if ($document->document)
                        <span>{{ \Str::limit($fileName, 15, '...') }}</span>
                    @else
                        -
                    @endif
                </td> --}}
                <td>{{ $document->file_type }}</td>
                <td class="address-column">{!! description($document->description, 80) !!}</td>
                <td>
                    {{-- <a href="#" class="editDocument" data-id="{{ $document->id }}" data-document="{{ $document->document }}" data-file-type-id="{{ $document->file_type_id }}" data-description="{{ $document->description }}" data-toggle="tooltip" data-placement="bottom" title="Edit"> --}}
                    @if (Auth::user()->hasRole(['admin', 'manager']))
                        <a href="{{ route('documents-approvals.edit', $document->id) }}" data-toggle="tooltip" data-placement="bottom" title="Edit">
                            <i class="bx bx-edit icon"></i>
                        </a>
                    @endif
                    @php
                        $docExt = pathinfo($document->document, PATHINFO_EXTENSION);
                    @endphp
                    @if ($docExt == 'xlsx' || $docExt == 'docx' || $docExt == 'odt')
                        <a href="#" onclick="window.open('https://docs.google.com/gview?url={{ $document->document }}&embedded=true', '_blank')">
                            <i class="bx bx-file icon"></i>
                        </a>
                    @else
                        <a href="{{ $document->document }}" target="__blank" data-toggle="tooltip" data-placement="bottom" title="{{ __('comon.view') }}">
                            <i class="bx bx-file icon"></i>
                        </a>
                    @endif
                    <a href="{{ $document->document }}" download="{{ $fileName }}" data-toggle="tooltip" data-placement="bottom" title="Download">
                        <i class="bx bx-download icon"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center">{{ __('comon.emptyTableMsg') }}</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="dataTables_paginate paging_simple_numbers mt-3" id="paginate">
    @include('components.pagination', ['paginate' => $documents])
</div>
