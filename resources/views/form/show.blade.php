<x-forms-app-layout>
    <x-bladeComponents::panel :title="$title" class="container">
        @foreach($questions as $question)
            @if($question->type =='Livewire')
                @livewire($question->view,[
    'model'=>$model,
    'field'=>$question->field,
    'type'=>$type
    ])
            @else
                <x-dynamic-component :component="$question->view" :model="$model" :field="$question->field"
                                     :type="$type"/>
            @endif
        @endforeach
        <livewire:forms-save :step="$step" :model="$model" :type="$type"/>

    </x-bladeComponents::panel>

</x-forms-app-layout>
