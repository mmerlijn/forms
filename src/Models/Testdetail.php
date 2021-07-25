<?php


namespace mmerlijn\forms\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testdetail extends Model
{
    use HasFactory;
    protected $primaryKey="test_id";
    protected $formErrors = [];
    public $vragen = [];
    public $stappen = [];


    protected $casts = [
        'questions' => 'array',
        'answers' => 'array',
        'steps' => 'array',
        'log' =>'array',
        'auth' => 'array',
    ];

    public function getTable()
    {
        return config('forms.tables.testdetails');
    }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function patient()
    {
        return $this->belongsTo(config('forms.model_patient'));
    }

    public function room()
    {
        return $this->belongsTo(config('forms.model_room'));
    }

    public function location()
    {
        return $this->belongsTo(config('forms.model_location'));
    }
}