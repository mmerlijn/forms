<?php

namespace mmerlijn\forms\Http\Livewire;

use Livewire\Component;

class Save extends Component
{

    public $model;
    public $step;
    public $type;
    public $formErrors = [];
    public $next=false;

    public function render()
    {
        return view('forms::livewire.save');
    }

    public function valideer()
    {
        $this->formErrors=[];
        $this->model->valideer($this->step);
        if(!$this->model->hasErrors()){

            $this->next=true;
            session()->flash('success','Gegegevens zijn succesvol verwerkt en opgeslagen');

            $this->model->runAfterValidation($this->step);
            //TODO hier een redirect naar volgende ...

            //nu maar even terug naar overzicht
            $this->terugOverzicht();
        }else{
            $this->formErrors = $this->model->getErrors();
            session()->flash('warning','Niet alle velden zijn nog juist');
        }
    }
    public function volgende(){
        //TODO
        //Hier moet iets slims komen om direct naar de volgende te gaan

        //$this->redirect(route('form.edit',['form'=>$onderzoek,'step'=>$step,'id'=>$id]));
    }
    public function terugOverzicht()
    {
        $this->redirect(route('forms.overzicht'));
    }
}
