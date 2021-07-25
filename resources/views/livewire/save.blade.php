<x-forms::question :title="''">
    <div class="m-4">
        @if($type=='edit')
        <button class="bg-blue-600 text-white rounded shadow hover:bg-blue-500 hover:text-gray-200 px-2 py-1"
                wire:click="valideer">Valideer en sla op
        </button>
        @endif
        {{--TODO De next button moet nog naar gekeken worden, is nog niet operationeel
        @if($next)
            <button class="bg-blue-600 text-white rounded shadow ml-4 hover:bg-blue-500 hover:text-gray-200 px-2 py-1"
                    wire:click="volgende">Volgende
            </button>
        @endif
        --}}
        <button class="bg-blue-600 text-white rounded shadow hover:bg-blue-500 hover:text-gray-200 px-2 py-1 ml-4"
                wire:click="terugOverzicht">Terug naar overzicht
        </button>
    </div>
    @if(!empty($formErrors))
        <x-bladeComponents::alert-warning>
            <ul>
                @foreach($formErrors as $formError)
                    <li>{{$formError}}</li>
                @endforeach
            </ul>
        </x-bladeComponents::alert-warning>
    @endif
</x-forms::question>
