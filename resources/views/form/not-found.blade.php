<x-forms-app-layout>
    <x-bladeComponents::panel title="NOT FOUND" class="container">
        <x-bladeComponents::alert-danger>
                id : {{$id??''}}<br>
                Step : {{$step_name??''}} ({{$step??''}})<br>

                {{-- TODO hier admin role toevoegen om exception te kunnen zien --}}
                Exception : <pre>{{$exception??''}}</pre>
        </x-bladeComponents::alert-danger>
    </x-bladeComponents::panel>
</x-forms-app-layout>
