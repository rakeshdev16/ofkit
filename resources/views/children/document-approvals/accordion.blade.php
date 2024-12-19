<div class="mx-2" style="display: {{ count($documents) > 0 ? 'block' : 'none' }}">
    <input type="checkbox" class="mainAccordionCheckbox">&nbsp;&nbsp;&nbsp;
</div>
@forelse ($documents as $document)
    @php
        $fileName = explode('child-document/', $document->document)[1] ?? $document->document;
    @endphp
    <div class="accordion accordion-flush tr-{{ $document->id }}" id="accordion{{ $loop->iteration }}">
        <div class="accordion-item">
            <h2 class="accordion-header" id="staff-listing-{{ $loop->iteration }}">
                <button class="accordion-button accordion-screen collapsed {{$document->status == 'inactive' ? $document->status : ''}}" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse{{ $loop->iteration }}" aria-expanded="false" aria-controls="flush-collapse{{ $loop->iteration }}">
                    {{-- @include('components.accordion-label', [
                        'id' => $document->id,
                        'name' => $fileName,
                        'download' => ['fileName' => $fileName, 'url' => $document->document],
                        'show' => $document->document,
                        'targetBlank' => true,
                    ]) --}}
                    <div class="row w-100 align-items-center" style="">
                        <div class="col-2 d-flex justify-content-center">
                            <input type="checkbox" name="id[]" value="{{ $document->id }}" class="accordionCheckbox checkbox check-{{ $document->id }}" data-name="{{ @$dataName }}">&nbsp;&nbsp;
                            {{-- <input type="checkbox" value="{{ @$id }}" class="accordionCheckbox check-{{ $id }}" data-class="check-{{ $id }}">&nbsp;&nbsp; --}}
                        </div>
                        {{-- <div class="col-6">{{ \Str::limit($fileName, 10, '...') ?? '-' }}</div> --}}
                        <div class="col-6">{{ date('d/m/Y', strtotime($document->created_at)) }}</div>
                        <div class="col-4 d-flex">
                            @if (Auth::user()->hasRole(['admin', 'manager']))
                                <a href="{{ route('documents-approvals.edit', $document->id) }}" class="me-1" data-toggle="tooltip" data-placement="bottom" title="Edit">
                                    <i class="bx bx-edit icon"></i>
                                </a>
                            @endif
                            @php
                                $docExt = pathinfo($document->document, PATHINFO_EXTENSION);
                            @endphp
                            @if ($docExt == 'xlsx' || $docExt == 'docx' || $docExt == 'odt')
                                {{-- <a href="#" onclick="window.open('https://docs.google.com/gview?url={{ $document->document }}&embedded=true', '_blank')">
                                    <i class="bx bx-file icon"></i>
                                </a> --}}
                            @else
                                <a href="{{ $document->document }}" target="__blank" data-toggle="tooltip" data-placement="bottom" title="{{ __('comon.view') }}">
                                    <i class="bx bx-file icon"></i>
                                </a>
                            @endif
                            <a href="{{ $document->document }}" download="{{ $fileName }}" class="me-1" data-toggle="tooltip" data-placement="bottom" title="Download">
                                <i class="bx bx-download icon"></i>
                            </a>
                        </div>
                    </div>
                </button>
            </h2>

            <div id="flush-collapse{{ $loop->iteration }}" class="accordion-collapse collapse" aria-labelledby="staff-listing-{{ $loop->iteration }}" data-bs-parent="#accordion{{ $loop->iteration }}" style="">
                <div class="accordion-body">
                    {{-- <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('children.document') }}</div>
                        <div class="w-50">{{ $fileName }}</div>
                    </div> --}}
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('comon.therapist') }}</div>
                        <div class="w-50">{{$document->user->name ?? ' - '}}</div>
                    </div>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('children.fileType') }}</div>
                        <div class="w-50">{{ $document->file_type }}</div>
                    </div>
                    <div class="d-flex accordion-row">
                        <div class="w-50 label">{{ __('children.documentDescription') }}</div>
                        <div class="w-50">{!! description($document->description, 80) !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="text-center"> {{ __('comon.emptyTableMsg') }} </div>
@endforelse
<div class="dataTables_paginate paging_simple_numbers mt-2" id="paginate">
    @include('components.pagination', ['paginate' => $documents])
</div>
