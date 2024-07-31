<div class="row medicineRow mt-4">
    <div class="col-md-12 d-flex justify-content-between">
        <div>Medicine #{{ @$index }}</div>
        <div>
            @if (@$index == 1)
                <button type="button" class="btn button addMoreMedicine">+</button>
            @else
                <button type="button" class="btn button removeMedicine"><i class="fadeIn animated bx bx-trash"></i></button>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        @include('components.text-input', [
            'label' => __('children.medicineName'),
            'name' => 'medicine_name',
            'icon' => 'network-chart',
        ])
    </div>
    <div class="col-md-6">
        @include('components.select-input', [
            'label' => __('children.type'),
            'name' => 'type',
            'icon' => 'buildings',
            'options' => [
                ['key' => 'sos', 'value' => 'SOS'],
                ['key' => 'regular', 'value' => 'Regular']
            ],
        ])
    </div>
    <div class="col-md-6">
        @include('components.text-input', [
            'label' => __('children.dosageAndTiming'),
            'name' => 'dosage_and_timing',
            'icon' => 'network-chart',
        ])
    </div>
    <div class="col-md-6">
        @include('components.select-input', [
            'label' => __('children.where'),
            'name' => 'where',
            'icon' => 'buildings',
            'options' => [
                ['key' => 'kindergarten', 'value' => __('children.kindergarten')],
                ['key' => 'home', 'value' => __('children.home')]
            ],
        ])
    </div>
</div>