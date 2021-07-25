<x-forms::question :title="'Onderzoek'">
    @if($type=='view')
        @if($value['datum']??'')
            <label class="font-bold">Datum</label>
            {{\Carbon\Carbon::make($value['datum'])->format('d-m-Y')}}
            <label class="font-bold">Door</label>
            {{$value['door']??''}}
        @endif
    @else
        <div class="p-1 @error('value.*') rounded border border-red-600 @enderror">
            <label for="ond_datum" class="font-bold">Datum</label>
            <input type="date" id="ond_datum" wire:model.lazy="value.datum" class="p-1 border rounded @error('value.voorletters') border-red-600 @enderror ">
            <label class="font-bold">Door</label>
            {{$value['door']??''}}
        </div>
    @endif
</x-forms::question>
