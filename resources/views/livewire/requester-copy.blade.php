<x-forms::question :title="'Aanvrager Kopie'">
    @if($type=='view')
        @if($value['achternaam']??'')
            <label class="font-bold">Naam</label>
            {{$value['achternaam']??''}}, {{$value['voorletters']>>''}}
        @endif
    @else
        <div class="p-1 @error('value.*') rounded border border-red-600 @enderror">
        @isset($aanvrager->id)
            <label class="font-bold">Naam</label>
            {{$aanvrager->achternaam}}, {{$aanvrager->voorletters}} ({{$aanvrager->plaats}})
            <label class="font-bold">AGBcode</label>
            {{$aanvrager->agbcode}}
            <button class="bg-blue-600 py-1 rounded shadow text-white px-2 inline-flex items-center lg:ml-8"
                    wire:click="wijzig">
                <x-heroicon-o-pencil class="w-5 h-5 mr-2"/>
                Wijzig
            </button>
        @else
            <label class="font-bold">Naam</label>
            <input type="text" wire:model="achternaam" class="border p-1 rounded"/>
            <label class="font-bold">AGBcode</label>
            <input type="text" wire:model="agbcode" class="border p-1 rounded"/>
            <ul>
                @foreach($aanvragers as $aanvrager)
                    <li class="cursor-pointer hover:bg-yellow-100"
                        wire:click="select({{$aanvrager->id}})">{{$aanvrager->achternaam}}
                        , {{$aanvrager->voorletters}} ({{$aanvrager->plaats}})
                    </li>
                @endforeach
            </ul>
        @endisset
        </div>
    @endif
</x-forms::question>
