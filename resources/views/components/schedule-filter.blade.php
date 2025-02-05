
@include('components.select-input', [
    'name' => '',
    'id' => 'kindergartenFilter',
    'icon' => 'buildings',
    'value' => request('kindergarten_id'),
    'onchange' => "filterCalendar({ 'kindergarten_id': this.value })",
    'options' => $kindergartens,
    'disabled' => request('edit') == true ? 'disabled' : '',
])
@if (Route::currentRouteName() == 'schedule.index')
    @include('components.select-input', [
        'name' => '',
        'icon' => 'buildings',
        'value' => request('status'),
        'onchange' => "filterCalendar({ 'status': this.value })",
        'options' => [['key' => 'published', 'value' => 'Published'], ['key' => 'draft', 'value' => 'Saved as Draft']],
    ])
@endif
<span style="border: 2px solid #80808094"></span>
<div id="childrenFilter"></div>
<div id="staffFilter"></div>