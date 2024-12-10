<div class="d-flex justify-content-between my-3">
    @switch(Route::currentRouteName())
        @case('therapy-schedule.index')
            <div class="filters d-flex flex-wrap  gap-3">
                <select class="kindergartenFilter form-select rounded-pill px-5 w-auto">
                    <option value="">Select Kindergarten</option>
                    @foreach ($kindergartens as $kindergarten)
                        <option value="{{ $kindergarten->staffKindergartens->pluck('user_id') }}">{{ $kindergarten->name }}</option>
                    @endforeach
                </select>
                {{-- <select data-key="event[status]" class="calendarFilter form-select rounded-pill px-5 w-auto">
                    <option value="">Children</option>
                    <option value="Child1">Child 1</option>
                    <option value="Child2">Child 2</option>
                </select>
                <select data-key="event[status]" class="calendarFilter form-select rounded-pill px-5 w-auto">
                    <option value="">Staff</option>
                    <option value="John">John</option>
                    <option value="Ortal Remano">Ortal Remano</option>
                </select> --}}
                <select data-key="event[status]" class="calendarFilter form-select rounded-pill px-5 w-auto">
                    <option value="published">published</option>
                    <option value="draft">Saved as draft</option>
                </select>
            </div>
            <div class="d-flex flex-wrap gap-3">
                <span class="badge button btn rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" data-bs-toggle="modal" data-bs-target="#draft">Draft</span>
                <a href="/schedule-history" class="badge button btn rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">History</a>
                <span class="badge button btn rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">Edit</span>
                <a href="{{ route('therapy-schedule.create') }}" class="badge button btn rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">Create New</a>
                <span class="badge button btn rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" data-bs-toggle="modal" data-bs-target="#scoreSummary">Hours</span>
            </div>
        @break
        @case('therapy-schedule.create')
            <div class="filters">
                <select id="kindergartenFilter" class="btn form-select btn-outline-secondary w-auto px-5 rounded-pill ">
                    <option value="">Select Kindergarten</option>
                    @foreach ($kindergartens as $kindergarten)
                        <option value="{{ $kindergarten->staffKindergartens->pluck('user_id') }}">{{ $kindergarten->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="d-flex gap-3">
                <button class="btn badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" data-bs-toggle="modal" data-bs-target="#">Export as PDf</button>
                <button class="btn badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer updateEventStatus" data-status="draft">Save as draft</button>
                <button class="btn badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">Cancel</button>
                <button class="btn badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer updateEventStatus" data-status="published">Publish</button>
                <button class="btn badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" data-bs-toggle="modal" data-bs-target="#eventTypeModal">New Appointment</button>
            </div>
        @break
        @default
    @endswitch
</div>