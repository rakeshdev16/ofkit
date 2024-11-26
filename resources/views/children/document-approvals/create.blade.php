@extends('layout.master')
@push('customLink')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('section')
    <div class="wrapper">
        <div class="header-wrapper">
            <div class="page-wrapper">
                <div class="page-content">
                    <div class="page-breadcrumb d-flex align-items-center mb-3">
                        <div class="breadcrumb-title pe-3">{{ __('cluster.addBtnText') }}</div>
                        <div class="ps-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 p-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('cluster.index') }}">
                                            <img class="p-1" src="{{ asset('assets/icons/fi_2887367.png') }}" />
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ __('cluster.cluster') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="ms-auto">
                            <div class="">
                                {{old('document')}}
                                {{old('description')}}
                                <button data-url="{{ route('documents-approvals.get', $childId) }}" class="btn button exit">{{ __('comon.back') }}</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 mx-auto">
                            <div class="card">
                                <div class="card-body p-4">
                                    <h5 class="mb-4">{{ __('children.document') }}</h5>
                                    <form action="{{ route('documents-approvals.post') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <input type="hidden" name="children_id" value="{{ $childId }}">
                                            <div class="col-md-12">
                                                {{-- <label for="file" class="form-label">{{ __('children.document') }}</label>
                                                <div class="position-relative input-icon">
                                                    <input type="file" class="form-control documents @error('document') is-invalid @enderror file" id="file" name="document" placeholder="Document">
                                                </div>
                                                @error('document')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                                <div class="d-flex mt-2 choosenDocument" style="flex-wrap: wrap;"></div>
                                                <input type="hidden" class="document" name="old_document" value=""> --}}
                                                @include('components.file-input', [
                                                    'label' => __('children.document'),
                                                    'name' => 'document',
                                                    'class' => 'file',
                                                    'id' => 'file',
                                                    'icon' => 'file',
                                                    'value' => old('file'),
                                                ])
                                                <div class="d-flex mt-2 choosenFile" style="flex-wrap: wrap;">
                                                    @if (old('file'))
                                                        @php
                                                            $fileName = explode('child-document/', old('file'))[1];
                                                        @endphp
                                                        <div class="document mt-1">
                                                            <a href="{{ asset('storage/' . old('file')) }}" target="_blank" rel="noopener noreferrer">{{ $fileName }}</a>
                                                            <i class="bx bx-x childDocument" data-file-name="{{ $fileName }}"></i>
                                                        </div>
                                                        <input type="hidden" name="old_document" value="{{ old('file') }}">
                                                    @else
                                                        @if (isset($document->file) && $document->file != null)
                                                            <div class="document mt-1">
                                                                <a href="{{ $document->file }}" target="_blank" rel="noopener noreferrer">{{ $document->file_name }}</a>
                                                                <i class="bx bx-x childDocument" data-file-name="{{ $document->file }}"></i>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-12 pt-3">
                                                <label for="file_type_id" class="form-label">{{ __('children.fileType') }}</label>
                                                <div class="position-relative input-icon">
                                                    <select name="file_type_id" class="form-control @error('file_type_id') is-invalid @enderror  file-type">
                                                        <option value="" selected="">{{ __('comon.select') }}</option>
                                                        @foreach ($fileTypes as $fileType)
                                                            <option value="{{ $fileType['key'] }}" {{old('file_type_id') == $fileType['key'] ? 'selected' : ''}}>{{ $fileType['value'] }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="position-absolute top-50 translate-middle-y">
                                                        <i class="bx bx-buildings"></i>
                                                    </span>
                                                    @error('file_type_id')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror

                                                </div>
                                            </div>
                                            <div class="col-md-12 pt-3">
                                                <label for="description" class="form-label">{{ __('children.documentDescription') }}</label>
                                                <div class="position-relative input-icon">
                                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror  description" id="description" cols="30" rows="2" style="resize: none;">{{old('description')}}</textarea>
                                                    <span class="position-absolute top-50 translate-middle-y">
                                                        <i class="bx bx-network-chart"></i>
                                                    </span>
                                                    @error('description')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="my-3">
                                            <input type="hidden" name="id" class="id">
                                            <button type="submit" class="btn button">{{ __('comon.submit') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @push('customScript')
     @include('children.document.script')
        <script>
            $('.documents').change(function(event) {
                const file = event.target.files[0];
                console.log(file);
                $('.choosenDocument').append('<div class="document mt-1">' + file.name + '<i class="bx bx-x staffDocument" data-file-name="' + file.name + '"></i></div>');
            });

            $(document).on('click', '.staffDocument', function() {
                let parentDiv = $(this).parent();
                let fileName = $(this).data('file-name');
                parentDiv.remove();
                $('.documents').val('');
            });

        </script>
    @endpush
