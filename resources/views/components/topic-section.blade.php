<div class="row p-4 my-2 child-{{ $children_id }}" style="background: #aaaaaa2b">
    <div class="col-md-12 text-center">
        <h4>{{ $name }}</h4>
    </div>
    <div class="col-md-12">
        @include('components.textarea-input', [
            'label' => __('children.addTopic'),
            'name' => "children[$children_id][topic]",
            'icon' => 'notepad',
            'value' => @$data->topic,
        ])
    </div>
    <div class="col-md-12">
        @include('components.textarea-input', [
            'label' => __('children.addDiscussion'),
            'name' => "children[$children_id][discussion]",
            'icon' => 'group',
            'value' => @$data->discussion,
        ])
    </div>
    <div class="col-md-12">
        @include('components.textarea-input', [
            'label' => __('children.addDecisions'),
            'name' => "children[$children_id][decisions]",
            'icon' => 'user-check',
            'value' => @$data->decisions,
        ])
    </div>
</div>
