<tr>
    <td>
        <h6 class="pt-2">{{ getKindergartenNameById($id) }}</h6>
        <input type="hidden" name="kindergarten[{{$index}}][kindergarten_id]" value="{{ $id }}">
    </td>
    <td>
        @include('components.select-input', [
            'name' => "kindergarten[$index][role_id]", 
            'icon' => 'buildings', 
            'options' => $roles,
            'value' => @$data->role_id,
            'disabled' => Route::currentRouteName() == 'staff.show' ? 'disabled' : '',
            'required' => 'required'
        ])
    </td>
    <td>
        @include('components.select-input', [
            'name' => "kindergarten[$index][profession_id]", 
            'icon' => 'buildings', 
            'options' => $professions,
            'value' => @$data->profession_id,
            'disabled' => Route::currentRouteName() == 'staff.show' ? 'disabled' : '',
            'required' => 'required'
        ])
    </td>
</tr>