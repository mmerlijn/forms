<?php

namespace mmerlijn\forms\Http\Livewire;

use Livewire\Component;
use mmerlijn\forms\Models\Test;

class Overzicht extends Component
{
    public $datum;
    public $test;
    public $step = "";
    public array $steps;
    public array $onderzoeken;
    public $room_id;
    public $result = [];
    public $error;

    public function updated()
    {

        session()->put('forms', [
            'test' => $this->test,
            'step' => $this->step,
            'datum' => $this->datum,
            'room_id' => $this->room_id,
        ]);

        $this->zoek();
    }

    public function mount()
    {
        $this->onderzoeken = [''];
        foreach (config('forms.tests') as $onderzoek=>$items){
            $this->onderzoeken[] = $onderzoek;
        }
        $this->steps=[''=>'','5'=>'Afspraak'];
        foreach (config('forms.steps') as $k=>$v){
            $this->steps[$k] = $v;
        }
        if (session()->has('forms')) {
            $this->test = session('forms')['test'] ?? '';
            $this->step = session('forms')['step'] ?? 5; //default 'afspraak'
            $this->room_id = session('forms')['room_id'] ?? '';
            $this->datum = session('forms')['datum'] ?? now()->format('Y-m-d');
            $this->zoek();
        }
        if ($this->step == 10) {
            $this->datum = date('Y-m-d');
        }
        //indien er parameters gezet zijn moet hier direct gezocht worden
    }

    public function render()
    {
        return view('forms::livewire.overzicht');
    }

    public function onderzoekInvoeren($test, $source, $id)
    {
        $this->redirect(route('forms.create', ['test' => $test, 'source' => $source, 'id' => $id]));
    }

    public function onderzoekVervolgen($actie, $step, $id)
    {
        if($actie=='view'){
            $this->redirect(route('forms.view', [ 'step' => $step, 'id' => $id]));
        }elseif($actie=='edit'){
            $this->redirect(route('forms.edit', [ 'step' => $step, 'id' => $id]));
        }
    }

    protected function zoek(): void
    {
        $this->error = "";
        if ($this->step == 5) {
            //zoeken in afspraken
            $a = config('forms.models.appointment')::withTrashed();
            if ($this->datum) {
                $a = $a->whereDate('start_time', $this->datum );
            }else{
                $a = $a->whereDate('start_time', date('Y-m-d'));
            }
            if ($this->room_id) {
                $a = $a->whereRoomId($this->room_id);
            }
            if($this->test){
                $a = $a->whereHas('activity',fn($q)=>$q->where('test_name',$this->test));
            }
            $this->result = $a->with(['activity','room'])->orderBy('start_time')->get();
        } else {
            $model = new Test();
            //$Test = $model::whereTest($this->test);
            if ($this->step) {
                $model= $model->where('step','>=',$this->step);
            }
            if ($this->room_id) {
                $model = $model->whereRoomId($this->room_id);
            }
            if ($this->datum) {
                $model = $model->whereDate('created_at', $this->datum);
            }
            $q = $model->toSql();
            $this->result = $model->with(['detail'])->get();
            if (!count($this->result)) {
                $this->error = "Geen resultaten gevonden: ".$q;
            }

        }
    }
}
