<div class="modal-header">
    <h5 class="modal-title form-heading" style="text-transform: capitalize;">{{ str_replace('-', ' ', $data['type']) }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <form action="" enctype="multipart/form-data">
        <div class="d-flex align-items-center">
            <span><i class="fa fa-calendar"></i></span>&nbsp;&nbsp;
            <span>({{ $data->day }}) {{ date('H:i', strtotime($data->end_time)) }} - {{ date('H:i', strtotime($data->start_time)) }}</span>
        </div>
        <div class="mt-3">
            <span style="font-size: 20px"><i class="fa fa-home"></i></span>
            <strong>Kindergarten Name:</strong> {{ getKindergartenNameById($data->schedule->kindergarten_id) }} 
        </div>
        @if ($data['type'] == 'group' || $data['type'] == 'staff-meeting')
            <div class="mt-3">
                <strong>General Occurred?</strong>
                <div class="d-flex align-items-center gap-3 mt-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="general_occurred" id="generalOccurredYes">
                        <label class="form-check-label" for="generalOccurredYes">Yes</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="general_occurred" id="generalOccurredNo">
                        <label class="form-check-label" for="generalOccurredNo">No</label>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <div class="d-flex align-items-center gap-3 mt-2">
                    <select class="form-select w-100">
                        <option selected>Reason</option>
                        <option value="Child Absent">Child Absent</option>
                        <option value="Therapist Absent">Therapist Absent</option>
                        <option value="Kindergarten Closed">Kindergarten Closed</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>
            <div class="mt-3">
                <textarea class="form-control" rows="3" placeholder="Description Add"></textarea>
            </div>
        @endif
        @if (!in_array($data['type'], ['documentation-break', 'preparation', 'tutorial', 'other']))
            <div class="mt-2">
                <span>
                    <i class="fa fa-user" style="font-size: 20px"></i>
                    {{ getChildrenNamesById($data->childrens->pluck('children_id')) }}
                    <strong>Child’s Name:</strong>
                </span>
            </div>
        @endif
        <div class="mt-3">
            <strong>{{ $data['type'] == 'group' ? 'Participated' : 'Occurred' }}?</strong>
            <div class="d-flex align-items-center gap-3 mt-2">
                <div class="form-check">
                    <input class="form-check-input" name="occurred" type="radio" id="occurredYes">
                    <label class="form-check-label" for="occurredYes">Yes</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" name="occurred" type="radio" id="occurredNo">
                    <label class="form-check-label" for="occurredNo">No</label>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <div class="d-flex align-items-center gap-3 mt-2">
                <select class="form-select w-100">
                    <option selected>Reason</option>
                    <option value="Child Absent">Child Absent</option>
                    <option value="Therapist Absent">Therapist Absent</option>
                    <option value="Kindergarten Closed">Kindergarten Closed</option>
                    <option value="Other">Other</option>
                </select>
            </div>
        </div>
        <div class="mt-3">
            <textarea class="form-control" rows="3" placeholder="Description Add"></textarea>
        </div>
        <div class="mt-3 d-flex align-items-center">
            <input type="file" class="form-control" name="" id="">
        </div>
        <div class=" mt-4">
            <button type="submit" class="btn button">Save</button>
            <button type="button" class="btn button me-2" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
        </div>
    </form>
</div>