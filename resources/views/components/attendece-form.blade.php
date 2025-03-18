<div class="form{{ $id }}">
    <div class="mt-3">
        <strong>({{ $name }}) {{ @$data['type'] == 'group' ? 'Participated' : 'Occurred' }}?</strong>
        <div class="d-flex align-items-center gap-3 mt-2">
            <div class="form-check">
                <input class="form-check-input" name="occurred[{{ $type }}][{{ $id }}][occurred]" type="radio" id="occurredYes">
                <label class="form-check-label" for="occurredYes">Yes</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" name="occurred[{{ $type }}][{{ $id }}][occurred]" type="radio" id="occurredNo">
                <label class="form-check-label" for="occurredNo">No</label>
            </div>
        </div>
    </div>
    <div class="mt-3">
        <div class="d-flex align-items-center gap-3 mt-2">
            <select class="form-select w-100" name="occurred[{{ $type }}][{{ $id }}][reason]">
                <option selected>Reason</option>
                <option value="Child Absent">Child Absent</option>
                <option value="Therapist Absent">Therapist Absent</option>
                <option value="Kindergarten Closed">Kindergarten Closed</option>
                <option value="Other">Other</option>
            </select>
        </div>
    </div>
    <div class="mt-3">
        <textarea class="form-control" rows="3" name="occurred[{{ $type }}][{{ $id }}][description]" placeholder="Description Add"></textarea>
    </div>
</div>