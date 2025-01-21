<div class="accordion my-2 fileSec{{ @$child_id }}" id="accordionExample{{ $index }}">
    @php
        $error = false;
        if ($errors->has('participated.' . $index . '.participated') || $errors->has('participated.' . $index . '.reason') || $errors->has('participated.' . $index . '.description') || $errors->has('participated.' . $index . '.child_file')) {
            $error = true;
        }

        if (isset($data['file']) && !empty($data['file'])) {
            $file = asset('storage/' . $data['file']);
        }
    @endphp
    <div class="accordion-item" style="border: {{ $error == true ? '1px solid red' : '' }}">
        <h2 class="accordion-header" id="heading{{ $index }}">
            <button class="accordion-button collapsed" style="background: #80808017;" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="false" aria-controls="collapse{{ $index }}">
                &nbsp;&nbsp;&nbsp;{{ @$name }}
            </button>
        </h2>
        <div id="collapse{{ $index }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $index }}" data-bs-parent="#accordionExample{{ $index }}" style="">
            <div class="accordion-body" style="background: #80808017;">
                <div class="row">
                    <div class="col-md-12">
                        @include('components.radio-input', [
                            'label' => __('children.participated'),
                            'name' => "participated[$index][participated]",
                            'class' => "participated$index",
                            'icon' => 'user',
                            'onchange' => 'onChange=childParticipated(this);',
                            'value' => old('participated.' . $index . '.participated') ?? @$data['participated'],
                        ])
                        @error('participated.' . $index . '.participated')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-12 participatedReason" style="display: {{ (old('participated.' . $index . '.participated') ?? @$data['participated']) == '0' ? 'block' : 'none' }};">
                        @include('components.select-input', [
                            'label' => __('children.reason'),
                            'name' => "participated[$index][reason]",
                            'class' => 'reason',
                            'icon' => 'buildings',
                            'value' => old('participated.' . $index . '.reason') ?? @$data['reason'],
                            'options' => [['key' => 'Child absent', 'value' => 'Child absent'], ['key' => 'Therapist absent', 'value' => 'Therapist absent'], ['key' => 'Kindergarten closed', 'value' => 'Kindergarten closed'], ['key' => 'Parent absent', 'value' => __('children.parentAbsent')], ['key' => 'Other', 'value' => 'Other']],
                        ])
                        @error('participated.' . $index . '.reason')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-md-12 participatedDescription" style="display: {{ (old('participated.' . $index . '.participated') ?? @$data['participated']) == '1' ? 'block' : 'none' }};">
                        @include('components.textarea-input', [
                            'label' => __('children.description'),
                            'name' => "participated[$index][description]",
                            'icon' => 'network-chart',
                            'value' => old('participated.' . $index . '.description') ?? @$data['description'],
                        ])
                        @error('participated.' . $index . '.description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-12 mt-2">
                        @include('components.file-input', [
                            'label' => __('children.file'),
                            'name' => "participated[$index][child_file]",
                            'id' => 'childFile',
                            'class' => 'file',
                            'icon' => 'file',
                            'onchange' => 'appendChildFile(this)',
                            'value' => old('participated.' . $index . '.child_file') ?? @$file,
                        ])
                        <div class="d-flex mt-2 childChoosenFile" style="flex-wrap: wrap;">
                            @if (old('participated.' . $index . '.file'))
                                @php
                                    $fileName = explode('child-document/', old('participated.' . $index . '.file'))[1];
                                @endphp
                                <div class="document mt-1">
                                    <a href="{{ asset('storage/' . old('participated.' . $index . '.file')) }}" target="_blank" rel="noopener noreferrer">{{ $fileName }}</a>
                                    <i class="bx bx-x childDocFile" data-file-name="{{ $fileName }}"></i>
                                </div>
                                <input type="hidden" name="participated[{{ $index }}][file]" value="{{ old('participated.' . $index . '.file') }}">
                            @else
                                @if (isset($data['file']) && $data['file'] != null)
                                    <div class="document mt-1">
                                        <a href="{{ $file }}" target="_blank" rel="noopener noreferrer">{{ explode('child-document/', $data['file'])[1] }}</a>
                                        <i class="bx bx-x childDocFile" data-file-name="{{ $file }}"></i>
                                    </div>
                                    <input type="hidden" name="participated[{{ $index }}][file]" value="{{ @$data['file'] }}">
                                @endif
                            @endif
                        </div>
                        <input type="hidden" name="participated[{{ $index }}][children_id]" value="{{ @$child_id }}">
                        @error('participated.' . $index . '.child_file')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <input type="hidden" name="participated[{{ $index }}][id]" id="{{ @$id }}">
            </div>
        </div>
    </div>
</div>
