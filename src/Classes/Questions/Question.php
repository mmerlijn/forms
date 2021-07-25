<?php


namespace mmerlijn\forms\Classes\Questions;


class Question implements QuestionHandler
{
    public $view="";            //TODO lege view toevoegen, met melding
    public $field="question";
    public $type="Livewire";    //Livewire / Component



    //documentation
    public const codes=[];
    public const codes_old=[]; //oude niet meer gebruikte codes
    public const answer_format="";
    public const comments="";



    public function prefill(array $data): array
    {
        return [];// TODO: Implement before() method.
    }

    public function validate(array $ansers): array
    {
        return [];// TODO: Implement validate() method.
    }

    public function after(): array
    {
        return [];// TODO: Implement after() method.
    }
    protected function prefillAnswer($data):array
    {
        return [$this->field=>$data];
    }
}