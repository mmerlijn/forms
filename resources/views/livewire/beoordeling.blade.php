<x-forms::question :title="'Beoordeling'">
    @if($type=='view')
        @if($value['datum']??'')
            <label class="font-bold">Datum</label>
            {{\Carbon\Carbon::make($value['datum'])->format('d-m-Y')}}
            <label class="font-bold">Door</label>
            {{$value['door']}}
        @endif
    @else
        <div>
            <label for="ond_datum" class="font-bold">Datum</label>
            <input type="date" id="ond_datum" wire:model.lazy="value.datum" class="p-1 border rounded">
            <label class="font-bold">Door</label>
            {{$value['door']}}
        </div>
    @endif
</x-forms::question>
