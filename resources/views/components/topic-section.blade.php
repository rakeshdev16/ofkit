<div class="row p-4 my-2 child-{{ $children_id }}" style="background: #aaaaaa2b">
    <div class="col-md-12 text-center">
        <h4>{{ $name }}</h4>
    </div>
    <div class="col-md-12">
        @include('components.textarea-input', [
            'label' => __('children.addTopic'),
            'name' => "children[$index][topic]",
            'icon' => 'notepad',
            'value' => old('children.' . $index . '.topic') ?? @$data->topic,
        ])
        @error('children.' . $index . '.topic')
            <span class="invalid-feedback" role="alert">
                <strong>{{$message}}</strong>
            </span>
        @enderror
    </div>
    <div class="col-md-12">
        @include('components.textarea-input', [
            'label' => __('children.addDiscussion'),
            'name' => "children[$index][discussion]",
            'icon' => 'group',
            'value' => old('children.' . $index . '.discussion') ?? @$data->discussion,
        ])
        @error('children.' . $index . '.discussion')
            <span class="invalid-feedback" role="alert">
                <strong>{{$message}}</strong>
            </span>
        @enderror
    </div>
    <div class="col-md-12">
        @include('components.textarea-input', [
            'label' => __('children.addDecisions'),
            'name' => "children[$index][decisions]",
            'icon' => 'user-check',
            'value' => old('children.' . $index . '.decisions') ?? @$data->decisions,
        ])
        @error('children.' . $index . '.decisions')
            <span class="invalid-feedback" role="alert">
                <strong>{{$message}}</strong>
            </span>
        @enderror
    </div>
    <input type="hidden" name="children[{{ $index }}][children_id]" value="{{ $children_id }}">
</div>
