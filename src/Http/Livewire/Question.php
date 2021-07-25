<?php

namespace mmerlijn\forms\Http\Livewire;

use Livewire\Component;

class Question extends Component
{
    public $model; //used model
    public $field; //affected database/json field
    public $value; //value of the field
    public $type = "view";
    public $codes = []; //used codes for storage
    public $storage="json"; // json or field


    public function mount()
    {
        //if($this->storage=="json"){
            if ($this->model->detail->answers) {
                if (isset($this->model->detail->answers[$this->field])) {
                    $this->value = $this->model->detail->answers[$this->field];
                }
            }
        //}else{
        //    if(isset($this->model->{$this->field})){
        //        $this->value = $this->model->{$this->field};
        //    }
        //}
        $this->valideer();
    }

    public function updatedValue()
    {
        $this->value = $this->cleanValues($this->value);
        $this->valideer();
        $detail = $this->model->detail;
        if($this->storage=="json"){
            $answers = $detail->answers;
            if ($this->value) {
                $answers[$this->field] = $this->value;
            } else {
                unset($answers[$this->field]);
            }
            $detail->answers = $answers;
        }
        //else{
        //    $this->model->{$this->field} = $this->value;
        //}
        $detail->save();
    }

    public function code2string($code)
    {
        $out = [];
        if (is_array($code)) {
            foreach ($code as $c => $v) {
                $out[] = $this->codes[$c] ?? "niet ingevuld";
            }
        } else {
            $out[] = $this->codes[$code] ?? "niet ingevuld";
        }
        return implode(", ", $out);
    }

    public function valideer()
    {
    }

    public function render()
    {
        return view('forms::livewire.question');
    }

    private function cleanValues($haystack)
    {
        if (is_array($haystack)) {
            foreach ($haystack as $key => $value) {
                if (is_array($value)) {
                    $haystack[$key] = $this->cleanValues($haystack[$key]);
                }
                if (empty($haystack[$key])) {
                    unset($haystack[$key]);
                }
            }
            return $haystack;
        } else {
            return $haystack;
        }
    }
}
