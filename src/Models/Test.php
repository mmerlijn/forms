<?php
/*
 * Rechten worden zijn bepaald aan de hand van de stap van de test
 *
 * <testnaam>_<step>_view
 * <testnaam>_<step>_edit
 * <testnaam>_admin         alle rechten
 * <testnaam>_<step>_edit_<authorization>       specifieke autorization voor bepaalde steppen bv beoordeling alleen door een bepaalde locatie
 *
 * stappen (stap die gedaan moet worden)
 * 10 - onderzoek
 * 20 - upload
 * 30 - beoordeling
 * 40 - controle
 * 50 - uitslag
 * 100 -done
 *
 * */

namespace mmerlijn\forms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Test extends Model
{
    use HasFactory;

    public function getTable()
    {
        return config('forms.tables.test');
    }

//    public $timestamps = [
//        'appointment_at',
//        'onderzoek_at',
//        'analyse_at',
//        'upload_at',
//        'beoordeling_at',
//        'beoordeling2_at',
//        'behandeladvies_at',
//        'controle_at',
//        'gesprint_at',
//        'verstuurd_at',
//    ];

    private $formErrors = [];

    public function detail()
    {
        return $this->hasOne(Testdetail::class);
    }

    public function patient()
    {
        return $this->belongsTo(config('forms.models.patient'));
    }

    public function room()
    {
        return $this->belongsTo(config('forms.models.room'));
    }

    public function location()
    {
        return $this->belongsTo(config('forms.models.location'));
    }
    public function appointment()
    {
        return $this->belongsTo(config('forms.models.appointment'));
    }

    public function getQuestions(int $step): array
    {
        $questions = [];
        $list = $this->getQuestionList($step);
        foreach ($list as $item) {
            $class = config('forms.questions.' . $item);
            $questions[] = new $class;
        }
        return $questions;
    }

    protected function getQuestionList(int $step): array
    {
        if ($this->id) {
            try {
                return $this->detail->questions[$step];
            } catch (\Exception $e) {
                return [];
            }
        }
        return config('forms.tests.' . $this->name . '.questions.' . $step);
    }

    public function valideer($step): void
    {
        $this->formErrors = [];
        foreach ($this->detail->questions[$step] as $q) {
            $class = config('forms.questions.' . $q);
            $m = new $class;
            $this->formErrors = array_merge($this->formErrors, $m->validate($this->detail->answers));
        }
    }

    public function runAfterValidation($step)
    {
        $this->setStep($step);
        if($this->isFristStep($step) and $this->appointment_id)
        {
            $a =$this->appointment;
            $a->done=now();
            $a->save();
        }
    }

    public function getErrors(): array
    {
        return $this->formErrors;
    }

    public function hasErrors(): bool
    {
        if (empty($this->formErrors)) {
            return false;
        }
        return true;
    }

    public function setStep(int $step)
    {
        $log = $this->detail->log;
        $log[] =[
            'step'=>$step,
            'dd'=>now(),
            'by'=>auth()->user()->name??'Guest',
            'user_id'=>auth()->user()->id,
        ];
        $this->detail->log = $log;

        $this->step = $this->getNextStep($step);
        $this->save();
        $this->detail->save();
    }

    public function getNextStep($step): int
    {
        return $this->detail->steps[array_search($step, $this->detail->steps) + 1] ?? 200;
    }

    public function possibleStep($step): bool
    {
        if (!in_array($step, $this->detail->steps)) {
            return false;
        }
        if ($this->step > 100) { //test kan niet meer worden aangepast
            return false;
        }
        if ($step <= $this->step) { //step kan nog bewerkt worden (even kijken of dit handig is)
            return true;
        }
        if (($this->detail->steps[array_search($this->step, $this->detail->steps) + 1] ?? false) == $step) {//volgende stap mag ook
            return true;
        }
        return false;
    }
    private function isFristStep($step)
    {
        return $step==$this->detail->steps[0];
    }

}
