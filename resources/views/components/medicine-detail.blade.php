<div class="row medicineRow medicine-detail mt-4">
    <div class="col-md-12 d-flex justify-content-between">
        <div>Medicine #{{ @$index }}</div>
        <div>
            @if (!@$disabled)
                <button type="button" class="btn button removeMedicine"><i class="fadeIn animated bx bx-trash"></i></button>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        @include('components.text-input', [
            'label' => __('children.medicineName'),
            'name' => "medicine_dosage[$index][name]",
            'icon' => 'network-chart',
            'value' => @$data['name'],
        ])
        @error('medicine_dosage.'.$index.'.name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="col-md-6">
        @include('components.select-input', [
            'label' => __('children.type'),
            'name' => "medicine_dosage[$index][type]",
            'icon' => 'buildings',
            'options' => [
                ['key' => 'sos', 'value' => 'SOS'],
                ['key' => 'regular', 'value' => 'Regular']
            ],
            'value' => @$data['type'],
        ])
        @error('medicine_dosage.'.$index.'.type')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="col-md-6">
        @include('components.text-input', [
            'label' => __('children.dosageAndTiming'),
            'name' => "medicine_dosage[$index][dosage_and_timing]",
            'icon' => 'network-chart',
            'value' => @$data['dosage_and_timing'],
        ])
        @error('medicine_dosage.'.$index.'.dosage_and_timing')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="col-md-6">
        @include('components.select-input', [
            'label' => __('children.where'),
            'name' => "medicine_dosage[$index][where]",
            'icon' => 'buildings',
            'options' => [
                ['key' => 'kindergarten', 'value' => __('children.kindergarten')],
                ['key' => 'home', 'value' => __('children.home')]
            ],
            'value' => @$data['where'],
        ])
        @error('medicine_dosage.'.$index.'.where')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>