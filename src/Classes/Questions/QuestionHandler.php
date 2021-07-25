<?php


namespace mmerlijn\forms\Classes\Questions;




interface QuestionHandler
{

    public function prefill(array $data):array;

    public function validate(array $answers):array;

    public function after():array;

}