
@include('components.select-input', [
    'name' => '',
    'id' => 'kindergartenFilter',
    'icon' => 'buildings',
    'value' => request('kindergarten_id'),
    'onchange' => "filterCalendar({ 'kindergarten_id': this.value })",
    'options' => $kindergartens,
])
@if (Route::currentRouteName() == 'schedule.index')
    <div id="childrenFilter"></div>
    <div id="staffFilter"></div>
    @include('components.select-input', [
        'name' => '',
        'id' => 'childrenFilter',
        'icon' => 'buildings',
        'value' => request('status'),
        'onchange' => "filterCalendar({ 'status': this.value })",
        'options' => [['key' => 'published', 'value' => 'Published'], ['key' => 'draft', 'value' => 'Saved as Draft']],
    ])
@endif