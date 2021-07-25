<x-forms::question title="Patiëntgegevens">
    @if($type=='view')
        <div>
            <label class="font-bold">Naam: </label>
            @isset($value['geslacht'])
                @if($value['geslacht']=="M")
                    Dhr.
                @elseif($value['geslacht']=="F")
                    Mevr.
                @endif
            @endisset
            {{$value['tv_achternaam']??''}} {{$value['voorletters']??''}}
            {{$value['achternaam']??''}}
            {{$value['tv_eigennaam']??''}} {{$value['eigennnaam']??''}}
        </div>
        <div>
            <label class="font-bold">BSN: </label>{{$value['bsn']??''}}
        </div>
        <div>
            <label class="font-bold">Geboortedatum: </label>{{$value['gbdatum']??''}}
            @if($value['leeftijd']??'')
                <span class="italic text-sm"> {{floor($value['leeftijd'])}} jaar</span>
            @endif
        </div>
        @if($value['tweeling']??false)
            <div>Is tweeling</div>
        @endif
        <div>
            <label class="font-bold">Adres: </label>
            {{$value['straat']??''}} {{$value['huisnr']??''}}<br>
            {{$value['postcode']??''}} {{$value['plaats']??''}}
        </div>
        <div>
            <label class="font-bold">Telefoonnr: </label>
            {{$value['telefoon']??''}} {{$value['telefoon2']??''}}
        </div>
        <div>
            <label class="font-bold">Email: </label>
            {{$value['email']??''}}
        </div>
    @else
        <div class="p-1 @error('value.*') rounded border border-red-600 @enderror">
            @error('value.*') <x-bladeComponents::alert-danger>{{ $message }}</x-bladeComponents::alert-danger> @enderror
            <div>
                <label for="geslacht" class="font-bold">Geslacht</label>
                <div class="inline-block @error('value.geslacht') border border-red-600 @enderror ">
                <input type="radio" id="geslacht_m" value="M" wire:model.lazy="value.geslacht"
                       class="p-1 border rounded"><label class="ml-2" for="geslacht_m">Man</label>
                <input type="radio" id="geslacht_v" value="F" wire:model.lazy="value.geslacht"
                       class="p-1 border rounded"><label class="ml-2" for="geslacht_v">Vrouw</label>
                <input type="radio" id="geslacht_o" value="O" wire:model.lazy="value.geslacht"
                       class="p-1 border rounded"><label class="ml-2" for="geslacht_o">Overig</label>
                </div>
            </div>
            <div>
                <label for="voorletters" class="font-bold">Voorletters</label>
                <input type="text" id="voorletters" wire:model.lazy="value.voorletters" class="p-1 border rounded @error('value.voorletters') border-red-600 @enderror ">
            </div>
            <div>
                <label for="eigennaam" class="font-bold">Eigennaam</label>
                <input type="text" id="eigennaam" wire:model.lazy="value.eigennaam" class="p-1 border rounded @error('value.eigennaam') border-red-600 @enderror ">
                <label for="tv_eigennaam" class="font-bold">tussenv.</label>
                <input type="text" id="tv_eigennaam" wire:model.lazy="value.tv_eigennaam" class="p-1 border rounded @error('value.tv_eigennaam') border-red-600 @enderror ">
            </div>
            <div>
                <label for="achternaam" class="font-bold">Achternaam</label>
                <input type="text" id="achternaam" wire:model.lazy="value.achternaam" class="p-1 border rounded @error('value.achternaam') border-red-600 @enderror ">
                <label for="tv_achternaam" class="font-bold">tussenv.</label>
                <input type="text" id="tv_achternaam" wire:model.lazy="value.tv_achternaam" class="p-1 border rounded @error('value.tv_achternaam') border-red-600 @enderror ">
            </div>
            <div>
                <label for="bsn" class="font-bold">BSN</label>
                <input type="text" id="bsn" wire:model.lazy="value.bsn" class="p-1 border rounded @error('value.bsn') border-red-600 @enderror ">
            </div>
            <div>
                <label for="gbdatum" class="font-bold">Geboortedatum</label>
                <input type="date" id="gbdatum" wire:model.lazy="value.gbdatum" class="p-1 border rounded @error('value.gbdatum') border-red-600 @enderror ">
            </div>
            <div>
                <label for="tweeling" class="font-bold">Is tweeling</label>
                <input type="checkbox" id="tweeling" wire:model.lazy="value.tweeling" class="p-1 border rounded">
            </div>
            <div>
                <label for="postcode" class="font-bold">Postcode</label>
                <input type="text" id="postcode" wire:model.lazy="value.postcode" class="p-1 border rounded @error('value.postcode') border-red-600 @enderror ">
                <label for="huisnr" class="font-bold">Huisnr</label>
                <input type="text" id="huisnr" wire:model.lazy="value.huisnr" class="p-1 border rounded @error('value.huisnr') border-red-600 @enderror ">
            </div>
            <div>
                <label for="plaats" class="font-bold">Plaats</label>
                <input type="text" id="plaats" wire:model.lazy="value.plaats" class="p-1 border rounded @error('value.plaats') border-red-600 @enderror ">
                <label for="straat" class="font-bold">Straat</label>
                <input type="text" id="straat" wire:model.lazy="value.straat" class="p-1 border rounded @error('value.straat') border-red-600 @enderror ">
            </div>
            <div>
                <label for="tel1" class="font-bold">Telefoonnr</label>
                <input type="text" id="tel1" wire:model.lazy="value.telefoon" class="p-1 border rounded @error('value.telefoon') border-red-600 @enderror ">
                <label for="tel2" class="font-bold">Telefoonnr 2</label>
                <input type="text" id="tel2" wire:model.lazy="value.telefoon2" class="p-1 border rounded @error('value.telefoon2') border-red-600 @enderror ">
            </div>
            <div>
                <label for="email" class="font-bold">Email</label>
                <input type="email" id="email" wire:model.lazy="value.email" class="p-1 border rounded @error('value.email') border-red-600 @enderror ">
            </div>
        </div>
    @endif
    {{--
        <div>
            Model: {{$model}}<br>
            Value: {{implode(", ",$value??[])}}<br>
            Type: {{$type}}
        </div>
        --}}
</x-forms::question>
