<?php


namespace mmerlijn\forms\Classes\Questions;


use mmerlijn\forms\Models\Test;
use mmerlijn\forms\Models\Testdetail;

class Requester extends Question
{
    public $view = "forms-requester";

    public $field = 'aanvrager';

    public const answer_format="{agbcode: text\nachternaam: text\nvoorletters: text}";

    public function prefill($data): array
    {
        $value=[];
        if($data['orders']??''){
            if($data['orders']->requester['agbcode']){
                $value['agbcode'] = $data['orders']->requester['agbcode'];
                $name = explode(",", $data['orders']->requester['name']);
                $value['achternaam'] = trim($name[0]);
                $value['voorletters'] = trim($name[1]);
            }
       }elseif($data['patient']??''){
            if($data['patient']->last_requester){
                $value['agbcode'] =$data['patient']->last_requester;
                $aanvrager = config('forms.models.requester')::whereAgbcode($data['patient']->last_requester)->first();
                $value['achternaam'] = $aanvrager->achternaam.", ".$aanvrager->tussenvoegsel;
                $value['voorletters'] = $aanvrager->voorletters;
            }
        }
        return $this->prefillAnswer($value);
    }
    public function validate(array $answers): array
    {
        $errors=[];
        if(!($answers[$this->field]['agbcode']??'')){
            $errors[] = 'Aanvrager is een verplicht veld';
        }

        return $errors;
    }
}
