<?php


namespace mmerlijn\forms\Classes\Questions;




class Onderzoek extends Question
{
    public $view = "forms-onderzoek";

    public $field = 'onderzoek';

    public const answer_format="{datum: date\ndoor: text\nuser_id: int}";

    public function prefill(array $data): array
    {
        $value=[];
        if(auth()->check()) {
            $value['door'] = auth()->user()->name;
            $value['user_id'] = auth()->user()->id;
        }
        if($data['appointment']??false){
            $value['datum'] = $data['appointment']->start_time->format('Y-m-d');
        }else{
            $value['datum'] = date('Y-m-d');
        }
        return $this->prefillAnswer($value);
    }
    public function validate(array $answers): array
    {
        $errors=[];
        if(!($answers[$this->field]['datum']??'')){
            $errors[] = 'Datum is een verplicht veld';
        }

        return $errors;
    }
}
