<?php


namespace mmerlijn\forms\Classes\Questions;


class RequesterCc extends Question
{
    public $view = "forms-requestercc";

    public $field = 'aanvrager_cc';

    public const answer_format="{agbcode: text\nachternaam: text\nvoorletters: text}";

    public function prefill($data): array
    {
        $value=[];
        if($data['orders']??''){
            if($data['orders']->copy_to['agbcode']){
                $value['agbcode'] = $data['orders']->copy_to['agbcode'];
                $name = explode(",", $data['orders']->copy_to['name']);
                $value['achternaam'] = trim($name[0]);
                $value['voorletters'] = trim($name[1]);
            }
       }
        return $this->prefillAnswer($value);
    }
}
