<?php

namespace mmerlijn\forms\Http\Livewire;

use mmerlijn\forms\Classes\FormComponentTrait;
use mmerlijn\forms\Rules\Bsn;
use Carbon\Carbon;
use Livewire\Component;

class Patient extends Question
{
    public function updatedValue()
    {
        if ($this->value['gbdatum'] ?? '') {
            $this->value['leeftijd'] = round(Carbon::make($this->value['gbdatum'])->floatDiffInYears(),4);
        }
        if($this->value['voorletters']??''){
            $this->value['voorletters'] = strtoupper($this->value['voorletters']);
        }
        parent::updatedValue();

    }

    public function valideer()
    {
        $this->validate(
            [
                'value.geslacht' => 'required',
                'value.voorletters' => 'required',
                'value.eigennaam' => 'required',
                'value.bsn' => [new Bsn],
                'value.gbdatum' => 'required|date',
                'value.email' => 'email',
                'value.postcode'=>'required',
                'value.huisnr'=>'required',
                'value.plaats'=>'required',
                'value.straat'=> 'required',
            ],
            [],
            [
                'value.eigennaam' => 'Eigennaam',
                'value.geslacht' => 'Geslacht',
                'value.voorletters' => 'Voorletters',
                'value.bsn' => 'BSN',
                'value.gbdatum' => 'Geboortedatum',
                'value.email' => 'Emailadres',
                'value.plaats'=>'Plaats',
                'value.straat'=>'Straat',
                'value.huisnr'=>'Huisnr',
                'value.postcode'=>'Postcode',
            ]
        );
    }
    public function render()
    {
        return view('forms::livewire.patient');
    }
}
