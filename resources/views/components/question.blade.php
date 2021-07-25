<div class="my-2" @if($toggle??false) x-data="{open:{{$open??'false'}}}" @endif>
    <div class="font-bold text-xl mb-2 inline-flex items-center"><span>{{$title}}</span>
        @if($toggle??false)

            <button x-show="!open" @click="open=true" class="ml-2">
                <x-heroicon-o-chevron-down class="ml-2 h-5 v-5"/>
            </button>

            <button x-show="open" @click="open=false" class="ml-2">
                <x-heroicon-o-chevron-up class="ml-2 h-5 v-5"/>
            </button>
        @endif
    </div>
    <div class="ml-2" @if($toggle??false)  x-show="open" @endif>
        {{$slot}}
    </div>
</div>
