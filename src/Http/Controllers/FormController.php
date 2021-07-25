<?php

namespace mmerlijn\forms\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use mmerlijn\forms\Models\Test;
use mmerlijn\forms\Models\Testdetail;
use mmerlijn\msg\src\Hl7\HL7ZorgdomeinAanvraag;

class FormController extends Controller
{

    public function __construct()
    {
    }

    public function view($id, $step)
    {
        try {
            $model = Test::find($id);
            if (!$model) {
                throw new \Exception("Test met id = " . $id . " bestaat niet");
            }

            if (!auth()->user()
                ->hasAnyPermission([
                    $model->name . '_' . config('forms.steps.' . $step) . '_view',
                    $model->name . '_' . config('forms.steps.' . $step) . '_edit', $model->name . '_admin'
                ])) {
                abort(403, "Je hebt geen toegangsrechten tot " . $model->name . " " . config('forms.steps.' . $step) . " bekijken");
            }


        } catch (\Exception $e) {
            return view('forms::form.not-found')->with([
                'step' => $step,
                'step_name' => config('forms.steps.' . $step),
                'id' => $id,
                'exception' => $e->getMessage(),
            ]);
        }
        return view('forms::form.show')->with([
            'model' => $model,
            'questions' => $model->getQuestions($step),
            'step' => $step,
            'type' => 'view',
            'title' => 'Bekijk'
        ]);
    }

    public function create(Request $request, $test, $source = null, $id = null)
    {
        $first_step = config('forms.tests.' . $test . '.steps')[0] ?? false;
        if (!$first_step) {
            return view('forms::form.not-found')->with([
                'form' => $test,
                'step' => 'Not set in config',
                'class' => '',
                'exception' => 'Steps zijn niet gezet voor test: ' . $test,
            ]);
        }

        if (!auth()->user()->hasAnyPermission([
            $test . '_' . config('forms.steps.' . $first_step) . '_edit', $test . '_admin'
        ])) {
            abort(403, "Je hebt geen toegangsrechten tot " . $test . " " . config('forms.steps.' . $first_step));
        }
        //Er is geen source meegegeven dan even kijken of we dit wil willen creeeren voor deze test
        if (!config('forms.tests.' . $test . ".anonymous") and !$source) {
            abort(403, "Het is niet mogelijk om voor " . $test . " een onderzoek aan te maken zonder bron");
        }
        try {
            $model = new Test();
            $modelDetail = new Testdetail();
            $model->name = $test;
            $modelDetail->questions = config('forms.tests.' . $test . '.questions');
            $modelDetail->steps = config('forms.tests.' . $test . '.steps');
            $model->step = $first_step;


            /*
             * Indien er een source wordt meegegeven zal dit in prefill worden gezet. Elk question object kan van de
             * prefill gebruik maken en bepaalde velden daarmee alvast invullen. De prefill wordt niet een-op-een
             * overgenomen in 'answers'.
             *
             * */
            $prefill = [];
            if ($source) {
                $patient_id = null;
                switch ($source) {
                    case 'patient':
                        $patient_id = $id;
                        $prefill = ['patient' => config('forms.models.patient')::findOrFail($patient_id)];
                        break;
                    case 'msg':
                        $msg = config('forms.models.messages')::withTrashed()->findOrFail($id);
                        $hl7 = new HL7ZorgdomeinAanvraag();
                        $hl7->read($msg->input);
                        $modelDetail->request = $msg->input;
                        $patient_id = $msg->patient_id;
                        $prefill = ['msg' => $msg, 'orders' => $hl7->getOrders(), 'patient' => config('forms.models.patient')::findOrFail($patient_id)];
                        break;
                    case "appointment":
                        $appointment = config('forms.models.appointment')::withTrashed()->findOrFail($id);
                        if ($appointment->message_id) {
                            $msg = config('forms.models.messages')::find($appointment->message_id);
                            $modelDetail->request = $msg->input;
                            $hl7 = new HL7ZorgdomeinAanvraag();
                            $hl7->read($msg->input);
                            $patient_id = $msg->patient_id;
                            $prefill = ['msg' => $msg, 'orders' => $hl7->getOrders(), 'patient' => config('forms.models.patient')::findOrFail($patient_id)];
                        } elseif ($appointment->patient_id) {
                            $patient_id = $appointment->patient_id;
                            $prefill = ['patient' => config('forms.models.patient')::findOrFail($patient_id), 'appointment' => $appointment];
                        } else {
                            $prefill = ['appointment' => $appointment];
                        }
                        if ($appointment) {
                            $model->room_id = $appointment->room_id;
                            $model->location_id = $appointment->room->location_id;
                            $model->appointment_id = $appointment->id;
                        }
                        break;
                    default:
                        throw new \Exception("Bron ".$source." is niet bekend.");
                }
                if ($patient_id) {
                    //indien er al een onderzoek op deze dag van deze patient is geplaatst moet er doorverwezen worden naar dat betreffende onderzoek (bewerk status)
                    $form_model = Test::wherePatientId($patient_id)->whereName($test)->whereDate('created_at', now())->first();
                    if ($form_model) {
                        //TODO kijken of het wenselijk is om door te verwijzen naar (next) step. Bij rechten toevoegen moet hiernaar gekeken worden
                        if($form_model->step > $form_model->getNextStep($first_step)){
                            //misschien met admin rol wel wijzigingen nog toestaan???
                            return redirect(route('forms.view', ['step' => $model->step, 'id' => $form_model->id]));
                        }else{
                            return redirect(route('forms.edit', ['step' => $model->step, 'id' => $form_model->id]));
                        }

                    }
                    $model->patient_id = $patient_id;
                }
            }
            $questions = $model->getQuestions($first_step);

            $answer = []; //voorvullen van antwoorden
            foreach ($questions as $question) {
                $answer = array_merge_recursive($answer, $question->prefill($prefill));
            }
            $modelDetail->answers = $answer;
            $model->save();
            $model->detail()->save($modelDetail);
        } catch (\Exception $e) {
            return view('forms::form.not-found')->with([
                'form' => $test,
                'step' => $first_step,
                'step_name' => config('forms.steps.' . $first_step),
                'exception' => $e->getMessage(),
            ]);
        }
        return view('forms::form.show')->with([
            'model' => $model,
            'questions' => $questions,
            'type' => 'edit',
            'title' => "Invoer $test " . config('forms.steps.' . $model->step),
            'step' => $model->step
        ]);
    }

    public function edit(Request $request, $step, $id)
    {
        try {
            $model = Test::find($id);
            if (!$model) {
                throw new \Exception('Er kan geen onderzoek gevonden met id=' . $id);
            }
            if (!$model->possibleStep($step)) {
                throw new \Exception('Deze fase van onderzoek ' . $model->name  . ' is (nog) niet mogelijk.');
            }
        } catch (\Exception $e) {
            return view('forms::form.not-found')->with([
                'form' => $model->name??'null' ,
                'step' => $step,
                'id' => $id,
                'exception' => $e->getMessage(),
            ]);
        }
        if (!auth()->user()->hasAnyPermission([$model->name . '_' . config('forms.steps.' . $step) . '_edit', $model->name  . '_admin'])) {
            abort(403, "Je hebt geen toegangsrechten tot " . $model->name  . " " . config('forms.steps.' . $step) . " bewerken");
        }

        return view('forms::form.show')->with([
            'model' => $model,
            'questions' => $model->getQuestions($step, $model, $model->detail),
            'type' => 'edit',
            'title' => "Invoer/bewerken {$model->name} " . config('forms.steps.' . $step),
            'step' => $step
        ]);
    }

    public function overzicht($test = null, $step = null)
    {
        if ($test) {
            if (!in_array($test, array_keys(config('forms.tests')))) {
                return view('forms::form.not-found')->with([
                    'form' => $test,
                    'step' => $step,
                    'exception' => "Test '$test' bestaat niet",
                ]);
            }
        }
        //TODO controle op step
        if ($test or $step) {
            session()->put('forms', ['test' => $test, 'step' => $step]);
        }
        return view('forms::form.overzicht');
    }

    public function docs($test = null)
    {
        if ($test) {
            $only = [];
            foreach (config('forms.tests.' . $test . '.questions') as $item) {
                $only = array_merge($only, $item);
            }
        } else {
            $only = array_keys(config('forms.questions'));
        }
        return view('forms::form.docs', ['only' => $only]);
    }
}
