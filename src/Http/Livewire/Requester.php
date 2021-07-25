<?php

namespace mmerlijn\forms\Http\Livewire;

use mmerlijn\forms\Classes\FormComponentTrait;

use Livewire\Component;

class Requester extends Question
{
    public $achternaam;
    public $agbcode;
    public $aanvragers = [];
    public $aanvrager;

    public function mount()
    {
        parent::mount();
        if ($this->value['agbcode'] ?? '') {
            $this->aanvrager = config('forms.models.requester')::getAanvragerByAgbcode($this->value['agbcode']);
        }
    }

    public function render()
    {
        return view('forms::livewire.requester');
    }

    public function updatedAchternaam()
    {
        $this->agbcode = "";
        $this->aanvragers = config('forms.models.requester')::where('achternaam', 'like', '%' . $this->achternaam . "%")->limit(10)->get();
    }

    public function updatedAgbcode()
    {
        $this->naam = "";
        $this->aanvragers = config('forms.models.requester')::whereAgbcode($this->agbcode)->get();
    }

    public function wijzig()
    {
        $this->aanvrager = null;
        $this->achternaam = null;
        $this->agbcode = null;
    }

    public function select($aanvrager_id)
    {
        $this->aanvrager = config('forms.models.requester')::find($aanvrager_id);
        $this->aanvragers = [];
        $this->setValue();
    }

    private function setValue()
    {
        $this->value['agbcode'] = $this->aanvrager->agbcode;
        $this->value['achternaam'] = $this->aanvrager->achternaam;
        $this->value['voorletters'] = $this->aanvrager->voorletters;
        $this->updatedValue();
    }

    public function valideer()
    {
        $this->validate(
            [
                'value.agbcode' => 'required',
                'value.achternaam' => 'required',
            ],
            [],
            [
                'value.agbcode' => 'AGBcode',
                'value.achternaam' => 'Achternaam'
            ]
        );
    }
}
