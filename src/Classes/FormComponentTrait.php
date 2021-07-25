<?php


namespace mmerlijn\forms\Classes;


trait FormComponentTrait
{
    public $model;
    public $field;
    public $value;
    public $type = "view";

    public function mount()
    {
        if ($this->model->detail->answers) {
            if (isset($this->model->detail->answers[$this->field])) {
                $this->value = $this->model->answers[$this->field];
            }
        }
    }

    public function updatedValue()
    {
        $answers = $this->model->detail->answers;
        $answers[$this->field] = $this->value;
        $this->model->detail->answers = $answers;
        $this->model->detail->save();
    }

}
