<?php


namespace mmerlijn\forms\Classes\Questions\Fundus;





use mmerlijn\forms\Classes\Questions\Question;

class Tropicamide extends Question
{
    public $view = 'forms-fundus-tropicamide';

    public $field = 'tropicamide';

    public const codes=[
        "true"=>"Ja"
    ];
    public const answer_format="\nod: true\nos: true";
    public const comments = "Indien veld aanwezig dan aangevinkt";
}
