<div class="accordion fileSec{{@$id}}" id="accordionExample">
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button collapsed" style="background: #80808017;" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$index}}" aria-expanded="false" aria-controls="collapse{{$index}}">
                {{ @$name }}
            </button>
        </h2>
        <div id="collapse{{$index}}" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
            <div class="accordion-body" style="background: #80808017;">
                <div class="row">
                    <div class="col-md-12">
                        @include('components.radio-input', [
                            'label' => "Participated",
                            'name' => "participated[$index][participated]",
                            'class' => "participated$index",
                            'icon' => 'user',
                            'onchange' => 'onChange=childParticipated(this);',
                        ])
                    </div>
                    <div class="col-md-12 participatedReason" style="display: none;">
                        @include('components.select-input', [
                            'label' => 'Reason',
                            'name' => "participated[$index][reason]",
                            'icon' => 'buildings',
                            'options' => [
                                ['key' => 'Child absent', 'value' => 'Child absent'],
                                ['key' => 'Therapist absent', 'value' => 'Therapist absent'],
                                ['key' => 'Kindergarten closed', 'value' => 'Kindergarten closed'],
                                ['key' => 'Other', 'value' => 'Other'],
                            ]
                        ])
                    </div>
                    <div class="col-md-12">
                        @include('components.textarea-input', [
                            'label' => 'Description',
                            'name' => "participated[$index][description]",
                            'icon' => 'network-chart',
                        ])
                    </div>
                    <div class="col-md-12 mt-2">
                        @include('components.file-input', [
                            'label' => 'File',
                            'name' => "participated[$index][child_file]",
                            'class' => 'file',
                            'id' => 'file',
                            'icon' => 'file',
                            'value' => old('file'),
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>