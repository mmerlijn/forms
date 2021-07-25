<x-bladeComponents::panel title="Werk Overzicht" class="p-2">
    <div class="flex inline-flex gap-2 bg-gray-100 p-2 rounded mb-4">
        <div>
            <label for="datum" class="font-bold">Datum</label>
            <input id="datum" type="date" wire:model="datum" class="border rounded shadow p-2">
        </div>
        <div>
            <label for="onderzoek" class="font-bold">Onderzoek</label>
            <select id="onderzoek" wire:model="test" class="border rounded shadow p-2">
                @foreach($onderzoeken as $o)
                    <option value="{{$o}}">{{$o}}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="locatie">Locatie</label>
            <select id="locatie"
                    placeholder="Locatie" wire:model="room_id"
                    class='inline-block border rounded p-2 h-10 shadow-lg rounded overflow-auto'>
                <option value=""></option>
                <?php $city = null;?>
                @foreach(config('forms.models.room')::with_appointments($datum) as $room)
                @if($city and $room->city!=$city)
                </optgroup>
                @endif
                @if($room->city!=$city)
                    <optgroup label="{{$room->city}}">
                        <?php $city = $room->city; ?>
                        @endif
                        <option value="{{$room->id}}">{{Str::of($room->location->name)->replace($room->city,"")}}
                            - {{$room->short}}</option>
                        @endforeach
                    </optgroup>
            </select>
        </div>
        <div>
            <label for="step" class="font-bold">Stap</label>
            <select id="step" wire:model="step" class="border rounded shadow p-2">
                @foreach($steps as $id=>$s)
                    @if($id<=100)
                        <option value="{{$id}}">{{$s}}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>
    <div>
        <table class="w-full table-auto">
            <tr class="">
                @if(!session()->get('forms.test'))
                    <th>Onderzoek</th>
                @endif
                <th>Te doen</th>
                <th>Datum</th>
                @if(!session()->get('forms.room_id'))
                    <th>Locatie</th>
                @endif
                <th>Naam</th>
                <th>Doe</th>
                <th>Bekijk</th>
            </tr>
            @if($step == 5) {{-- zoeken op afspraken ipv testen--}}
            @foreach($result as $row)
                <tr class="hover:bg-gray-100 hover:cursor-pointer">
                    @if(!session()->has('forms.test'))
                        <td>{{$row->activity->test_name}}</td>
                    @endif
                    <td>Afspraak</td>
                    <td>{{$row->start_time->format('G:i d-m-Y')}}</td>
                    @if(!session()->has('forms.room_id'))
                        <td>{{$row->room->location->name}} {{$row->room->name}}</td>
                    @endif
                    <td>{{$row->name}}</td>
                    <td>
                        @if($row->activity->test_name and is_array(config('forms.tests.'.$row->activity->test_name)))
                            @canany([
    $row->activity->test_name.'_'.config('forms.steps.'.config('forms.tests.'.$row->activity->test_name.'.steps')[0]).'_edit'
    ,$row->activity->test_name.'_admin'
    ])
                                <button class="bg-blue-600 text-white rounded p-1 hover:bg-blue-500 hover:text-gray-200"
                                        wire:click="onderzoekInvoeren('{{$row->activity->test_name}}','appointment','{{$row->id}}')">
                                    @if($row->done)
                                        Wijzigen
                                    @else
                                        Invoeren
                                    @endif
                                </button>
                            @else
                                <span class="text-red-600">Geen toegang</span>
                            @endcan
                        @else
                            <span class="text-red-600">Geen invoer mogelijk</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            @else
                @foreach($result as $row)
                    @canany([$row->name.'_'.config('forms.steps.'.$row->step)."_view", $row->name.'_'.$row->step."_edit",$row->name."_admin"])
                        <tr class="p-1 hover:bg-yellow-100">
                            @if(!session()->get('forms.test'))
                                <td>{{$row->name}}</td>
                            @endif
                            <td>{{config('forms.steps.'.$row->step)}}</td>
                            <td>{{$row->created_at->format('d-m-Y')}}</td>
                            @if(!session()->has('forms.room_id'))
                                <td>
                                    @if($row->locatie_id)
                                        {{$row->location->name}}
                                    @endif
                                </td>
                            @endif
                            <td>
                                @if($row->patient_id)
                                    {{$row->patient->aanhef}} {{$row->patient->gbdatum->format('d-m-Y')}}
                                @else
                                    {{$row->answers['patient']['eigennaam']??''}}
                                @endif
                            </td>
                            <td>
                                {{-- Indien het een legale stap is en er nog geen vervolg stappen zijn genomen --}}
                                @if(in_array($step,$row->detail->steps) and $row->step <= $row->getNextStep($step))
                                    @canany([
            $row->name.'_'.config('forms.steps.'.$row->step)."_edit".(($row->auth[$row->step]??false)?"_".$row->auth[$row->step]:""),
             $row->name."_admin"])

                                        <button
                                                class="mb-1 bg-blue-600 text-white rounded p-1 hover:bg-blue-500 hover:text-gray-200 inline-flex items-center"
                                                wire:click="onderzoekVervolgen('edit','{{$step?:$row->step}}','{{$row->id}}')">
                                            <x-heroicon-o-pencil class="h-4 w-4 mr-2"/>
                                            <span> {{config('forms.steps.'.$step?:$row->step)}}</span>
                                        </button>
                                    @else
                                        <span class="text-red-600">Geen toegang</span>
                                    @endcanany
                                @endif
                            </td>
                            <td>
                                @if($row->step > $step and in_array($step,$row->detail->steps))
                                    @canany([
            $row->name.'_'.config('forms.steps.'.$row->step)."_view",
             $row->name."_admin"])

                                        <button
                                                class="mb-1 bg-blue-600 text-white rounded p-1 hover:bg-blue-500 hover:text-gray-200 inline-flex items-center"
                                                wire:click="onderzoekVervolgen('view','{{$step?:$row->step}}','{{$row->id}}')">
                                            <x-heroicon-o-view-list class="h-4 w-4 mr-2"/>
                                            <span> {{config('forms.steps.'.$step?:$row->step)}}</span>
                                        </button>
                                    @endcanany
                                @else
                                    <span class="text-red-600">-</span>
                                @endif
                            </td>
                        </tr>
                    @endcan
                @endforeach
            @endif
        </table>
        @if($error)
            <x-bladeComponents::alert-warning>{{$error}}</x-bladeComponents::alert-warning>
        @endif
    </div>
</x-bladeComponents::panel>
