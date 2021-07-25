<x-forms::question title="Tropicamide 0,5%">
    @if($type=='view')
        {{($value['od']??false)?'OD':''}}
        {{($value['os']??false)?'OS':''}}
    @else
        <input type="checkbox" wire:model="value.od" id="trop_od"><label class="ml-2" for="trop_od">OD</label>
        <input type="checkbox" wire:model="value.os" id="trop_os"><label class="ml-2" for="trop_os">OS</label>
    @endif
</x-forms::question>
