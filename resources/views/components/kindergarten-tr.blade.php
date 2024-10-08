<tr class="tr-{{ $id }}" data-index="{{ $index }}">
    <td>
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
                $index = array_search($data->role_id, $keys);
            @endphp
            <div class="position-relative input-icon">
                <input type="text" class="form-control" value="{{ $index !== false ? $memberRoles[$index]['value'] : '' }}" readonly="">
                <span class="position-absolute top-50 translate-middle-y">
                    <i class="bx bx-buildings"></i>
                </span>
                <input type="hidden" name="kindergarten[{{ $index }}][role_id]" value="{{ $data->role_id }}">
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
                $index = array_search($data->association_id, $keys);
            @endphp
            <div class="position-relative input-icon">
                <input type="text" class="form-control" value="{{ $index !== false ? $associations[$index]['value'] : '' }}" readonly="">
                <span class="position-absolute top-50 translate-middle-y">
                    <i class="bx bx-buildings"></i>
                </span>
                <input type="hidden" name="kindergarten[{{ $index }}][association_id]" value="{{ $data->association_id }}">
            </div>
        @endif

        @error('kindergarten.' . $index . '.association_id')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </td>
</tr>
