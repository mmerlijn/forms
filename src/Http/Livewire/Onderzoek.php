<?php

namespace mmerlijn\forms\Http\Livewire;


class Onderzoek extends Question
{

    public function render()
    {
        return view('forms::livewire.onderzoek');
    }
    public function valideer()
    {
        $this->validate(
            ['value.datum'=>'required|date'],
            [],
            ['value.datum'=>'Datum']
        );
    }
}
