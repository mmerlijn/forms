<?php


namespace mmerlijn\forms\Classes\Questions;
use Carbon\Carbon;

class Patient extends Question implements QuestionHandler
{

    public $view = "forms-patient"; // \mmerlijn\forms\Http\Livewire\Patient::class;

    public $field = 'patient';

    //docs
    public const answer_format="{\ngeslacht: M/F/O\nbsn: number\nvoorletters: text\nachternaam: text\ntv_achternaam: text\neigennaam: text\ntv_eigennaam: text\ngbdatum: date\nleeftijd: decimal
plaats: text\nstraat: text\npostcode: text\nhuisnr:text
phone: text\ntelefoon: text\ntelefoon2: text\nemail: text\n}";

    public function prefill($data): array
    {
        $value=[];
        if($data['patient']??''){
            if($data['patient']->geslacht){
                $value['geslacht'] = $data['patient']->geslacht;
            }
            if($data['patient']->bsn){
                $value['bsn'] = $data['patient']->bsn;
            }
            if($data['patient']->voorletters){
                $value['voorletters'] = $data['patient']->voorletters;
            }
            if($data['patient']->achternaam){
                $value['achternaam'] = $data['patient']->achternaam;
            }
            if($data['patient']->tv_achternaam){
                $value['tv_achternaam'] = $data['patient']->tv_achternaam;
            }
            if($data['patient']->tv_eigennaam){
                $value['tv_eigennaam'] = $data['patient']->tv_eigennaam;
            }
            if($data['patient']->eigennaam){
                $value['eigennaam'] = $data['patient']->eigennaam;
            }
            if($data['patient']->plaats){
                $value['plaats'] = $data['patient']->plaats;
            }
            if($data['patient']->straat){
                $value['straat'] = $data['patient']->straat;
            }
            if($data['patient']->postcode){
                $value['postcode'] = $data['patient']->postcode;
            }
            if($data['patient']->huisnr){
                $value['huisnr'] = $data['patient']->huisnr;
            }
            if($data['patient']->phone){
                $value['phone'] = $data['patient']->phone;
            }
            if($data['patient']->telefoon){
                $value['telefoon'] = $data['patient']->telefoon;
            }
            if($data['patient']->telefoon2){
                $value['telefoon2'] = $data['patient']->telefoon2;
            }
            if($data['patient']->email){
                $value['email'] = $data['patient']->email;
            }
            if($data['patient']->gbdatum){
                $value['gbdatum'] = $data['patient']->gbdatum->format('Y-m-d');
                $value['leeftijd'] = round(Carbon::make($data['patient']->gbdatum)->floatDiffInYears(),4);
            }
        }elseif($data['appointment']??''){
            if($data['appointment']->sex){
                $value['geslacht'] = $data['appointment']->sex; //TODO check of geslacht goed staat
            }
            if($data['appointment']->bsn){
                $value['bsn'] = $data['appointment']->bsn;
            }
            if($data['appointment']->dob){
                $value['gbdatum'] = $data['appointment']->dob;
            }
            if($data['appointment']->phone){
                $value['telefoon'] = $data['appointment']->phone;
            }
            if($data['appointment']->email){
                $value['email'] = $data['appointment']->email;
            }
        }
        return $this->prefillAnswer($value);
    }
    public function validate(array $answers): array
    {
        $errors=[];
        if(!($answers[$this->field]['eigennaam']??'')){
            $errors[] = 'Eigennaam is een verplicht veld';
        }
        if(!($answers[$this->field]['geslacht']??'')){
            $errors[] = 'Geslacht is een verplicht veld';
        }
        if(!($answers[$this->field]['gbdatum']??'')){
            $errors[] = 'Geboortedatum is een verplicht veld';
        }
        if(!($answers[$this->field]['postcode']??'')){
            $errors[] = 'Postcode is een verplicht veld';
        }
        if(!($answers[$this->field]['huisnr']??'')){
            $errors[] = 'Huisnr is een verplicht veld';
        }
        if(!($answers[$this->field]['plaats']??'')){
            $errors[] = 'Plaats is een verplicht veld';
        }
        if(!($answers[$this->field]['straat']??'')){
            $errors[] = 'Straat is een verplicht veld';
        }
        return $errors;
    }
}
