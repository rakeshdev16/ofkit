<div class="d-flex justify-content-between my-3">
    @switch(Route::currentRouteName())
        @case('therapy-schedule.index')
            <div class="filters d-flex flex-wrap  gap-3">
                <select id="kindergartenFilter" class="form-select rounded-pill px-5 w-auto">
                    <option value="">Kindergarten Name</option>
                    <option value="Hatsav">Hatsav</option>
                    <option value="Nitzan">Nitzan</option>
                    <option value="Alwan">Alwan</option>
                </select>
                <select id="childrenFilter" class="form-select rounded-pill px-5 w-auto">
                    <option value="">Children</option>
                    <option value="Child1">Child 1</option>
                    <option value="Child2">Child 2</option>
                </select>
                <select id="staffFilter" class="form-select rounded-pill px-5 w-auto">
                    <option value="">Staff</option>
                    <option value="John">John</option>
                    <option value="Ortal Remano">Ortal Remano</option>
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
                <select id="staffFilter" class="btn form-select btn-outline-secondary w-auto px-5 rounded-pill ">
                    <option value="">Select Kindergarten</option>
                    <option value="John">John</option>
                    <option value="Ortal Remano">Ortal Remano</option>
                </select>
            </div>
            <div class="d-flex gap-3">
                <span class="badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" data-bs-toggle="modal" data-bs-target="#">Export as PDf</span>
                <span class="badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">Save as draft</span>
                <span class="badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer">Cancel</span>
                <span class="badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer test">Publish</span>
                <span class="badge button rounded-pill p-2 px-4 fs-6 fw-normal cursor-pointer" data-bs-toggle="modal" data-bs-target="#eventTypeModal">New Appointment</span>
            </div>
        @break
        @default
    @endswitch
</div>