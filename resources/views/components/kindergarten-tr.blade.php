<table class="table table-{{ $id }} table-borderd" style="width:100%;"  data-index="{{ $index }}">
    @php
        $route = Route::currentRouteName();
        $sunday = isset($schedule) ? (clone $schedule)->where('day', 'sunday')->where('kindergarten_id', $id) : [];
        $monday = isset($schedule) ? (clone $schedule)->where('day', 'monday')->where('kindergarten_id', $id) : [];
        $tuesday = isset($schedule) ? (clone $schedule)->where('day', 'tuesday')->where('kindergarten_id', $id) : [];
        $wednesday = isset($schedule) ? (clone $schedule)->where('day', 'wednesday')->where('kindergarten_id', $id) : [];
        $thursday = isset($schedule) ? (clone $schedule)->where('day', 'thursday')->where('kindergarten_id', $id) : [];
        $friday = isset($schedule) ? (clone $schedule)->where('day', 'friday')->where('kindergarten_id', $id) : [];
        $saturday = isset($schedule) ? (clone $schedule)->where('day', 'saturday')->where('kindergarten_id', $id) : [];
    @endphp
    <thead>
        <tr>
            <th colspan="{{ $route == 'staff.show' ? '' : '2' }}">{{ __('staff.name') }}</th>
            <th>{{ __('staff.professionalRole') }}</th>
            <th>{{ __('staff.association') }}</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="{{ $route == 'staff.show' ? '' : '2' }}">
                <h6 class="pt-2">{{ getKindergartenNameById($id) }}</h6>
                <input type="hidden" name="kindergarten[{{ $index }}][kindergarten_id]" value="{{ $id }}">
            </td>
            <td>
                @if (Auth::user()->hasRole('admin'))
                    @include('components.select-input', [
                        'name' => "kindergarten[$index][role_id]",
                        'icon' => 'buildings',
                        'options' => $memberRoles,
                        'disabled' => Route::currentRouteName() == 'staff.show' ? 'disabled' : '',
                        'value' => old('kindergarten.' . $index . '.role_id') ?? @$data['role_id'],
                    ])
                @else
                    @php
                        $keys = array_column($memberRoles, 'key');
                        $index = array_search(@$data['role_id'], $keys);
                    @endphp
                    <div class="position-relative input-icon">
                        <input type="text" class="form-control" value="{{ $index !== false ? $memberRoles[$index]['value'] : '' }}" readonly="">
                        <span class="position-absolute top-50 translate-middle-y">
                            <i class="bx bx-buildings"></i>
                        </span>
                        <input type="hidden" name="kindergarten[{{ $index }}][role_id]" value="{{ @$data['role_id'] }}">
                    </div>
                @endif
                @error('kindergarten.' . $index . '.role_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </td>
            <td>
                @if (Auth::user()->hasRole('admin'))
                    @include('components.select-input', [
                        'name' => "kindergarten[$index][association_id]",
                        'icon' => 'buildings',
                        'options' => $associations,
                        'value' => @$data['association_id'],
                        'disabled' => Route::currentRouteName() == 'staff.show' ? 'disabled' : '',
                        'value' => old('kindergarten.' . $index . '.association_id') ?? @$data['association_id'],
                    ])
                @else
                    @php
                        $keys = array_column($associations, 'key');
                        $index = array_search(@$data['association_id'], $keys);
                    @endphp
                    <div class="position-relative input-icon">
                        <input type="text" class="form-control" value="{{ $index !== false ? $associations[$index]['value'] : '' }}" readonly="">
                        <span class="position-absolute top-50 translate-middle-y">
                            <i class="bx bx-buildings"></i>
                        </span>
                        <input type="hidden" name="kindergarten[{{ $index }}][association_id]" value="{{ @$data['association_id'] }}">
                    </div>
                @endif

                @error('kindergarten.' . $index . '.association_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </td>
        </tr>
        @forelse ($sunday as $sunday)
            @include('components.time-slot-row', ['id' => $id, 'key' => 'sunday', 'data' => $data, 'day' => $sunday, 'loopIndex' => $loop->index])
        @empty
            @include('components.time-slot-row', ['id' => $id, 'key' => 'sunday', 'data' => @$data])
        @endforelse
        @forelse ($monday as $monday)
            @include('components.time-slot-row', ['id' => $id, 'key' => 'monday', 'data' => $data, 'day' => $monday, 'loopIndex' => $loop->index])
        @empty
            @include('components.time-slot-row', ['id' => $id, 'key' => 'monday', 'data' => @$data])
        @endforelse
        @forelse ($tuesday as $tuesday)
            @include('components.time-slot-row', ['id' => $id, 'key' => 'tuesday', 'data' => $data, 'day' => $tuesday, 'loopIndex' => $loop->index])
        @empty
            @include('components.time-slot-row', ['id' => $id, 'key' => 'tuesday', 'data' => @$data])
        @endforelse
        @forelse ($wednesday as $wednesday)
            @include('components.time-slot-row', ['id' => $id, 'key' => 'wednesday', 'data' => $data, 'day' => $wednesday, 'loopIndex' => $loop->index])
        @empty
            @include('components.time-slot-row', ['id' => $id, 'key' => 'wednesday', 'data' => @$data])
        @endforelse
        @forelse ($thursday as $thursday)
            @include('components.time-slot-row', ['id' => $id, 'key' => 'thursday', 'data' => $data, 'day' => $thursday, 'loopIndex' => $loop->index])
        @empty
            @include('components.time-slot-row', ['id' => $id, 'key' => 'thursday', 'data' => @$data])
        @endforelse
        @forelse ($friday as $friday)
            @include('components.time-slot-row', ['id' => $id, 'key' => 'friday', 'data' => $data, 'day' => $friday, 'loopIndex' => $loop->index])
        @empty
            @include('components.time-slot-row', ['id' => $id, 'key' => 'friday', 'data' => @$data])
        @endforelse
        @forelse ($saturday as $saturday)
            @include('components.time-slot-row', ['id' => $id, 'key' => 'saturday', 'data' => $data, 'day' => $saturday, 'loopIndex' => $loop->index])
        @empty
            @include('components.time-slot-row', ['id' => $id, 'key' => 'saturday', 'data' => @$data])
        @endforelse
    </tbody>
</table>
<script>

</script>