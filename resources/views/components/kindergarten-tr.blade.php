<tr class="tr-{{$id}}" data-index="{{$index}}">
    <td>
        <h6 class="pt-2">{{ getKindergartenNameById($id) }}</h6>
        <input type="hidden" name="kindergarten[{{$index}}][kindergarten_id]" value="{{ $id }}">
    </td>
    <td>
        @include('components.select-input', [
            'name' => "kindergarten[$index][role_id]", 
            'icon' => 'buildings', 
            'options' => $memberRoles,
            'disabled' => Route::currentRouteName() == 'staff.show' ? 'disabled' : '',
            'value' => old('kindergarten.'.$index.'.role_id') ?? @$data->role_id
        ])
        @error('kindergarten.'.$index.'.role_id')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </td>
    <td>
        @include('components.select-input', [
            'name' => "kindergarten[$index][association_id]", 
            'icon' => 'buildings', 
            'options' => $associations,
            'value' => @$data->association_id,
            'disabled' => Route::currentRouteName() == 'staff.show' ? 'disabled' : '',
            'value' => old('kindergarten.'.$index.'.association_id') ?? @$data->association_id
        ])
        @error('kindergarten.'.$index.'.association_id')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </td>
</tr>