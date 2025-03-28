<div class="therapist-{{$id}} mx-1">
    <label for="therapist-{{$id}}">{{ $name }} {{ isTherapistAttended($id, @$eventId) }}</label>
    <input type="checkbox" name="therapist_occurred[]" id="therapist-{{$id}}" value="{{ @$id }}" {{ isTherapistAttended($id, @$eventId) ? 'checked' : '' }}>
</div>