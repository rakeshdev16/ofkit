
<select id="kindergartenFilter" onchange="filterCalendar({ 'kindergarten_id': this.value })" class="form-select rounded-pill px-5 w-auto">
    @foreach ($kindergartens as $kindergarten)
        @php
            $value = $kindergarten->id;
        @endphp
        <option value="{{ $value }}" {{ (request('kindergarten_id') ?? '') == $value ? 'selected' : '' }}>{{ $kindergarten->name }}</option>
    @endforeach
</select>
@if (Route::currentRouteName() == 'therapy-schedule.index')
    <select id="childrenFilter" onchange="filterCalendar({ 'children_id': this.value })" class="form-select rounded-pill px-5 w-auto"></select>
    <select id="staffFilter" onchange="filterCalendar({ 'user_id': this.value })" class="form-select rounded-pill px-5 w-auto"></select>
    <select onchange="filterCalendar({ 'status': JSON.stringify([this.value]) })" class="form-select rounded-pill px-5 w-auto">
        <option value="published" {{ ($status ?? '') == 'published' ? 'selected' : '' }}>Published</option>
        <option value="draft" {{ ($status ?? '') == 'draft' ? 'selected' : '' }}>Saved as Draft</option>
    </select>
@endif