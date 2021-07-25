<?php


namespace mmerlijn\forms\Console;


use Spatie\Permission\Models\Permission;

class MakePermissionCommand extends \Illuminate\Console\Command
{
    protected $signature = "forms:permissions";

    protected $description = "Create form permissions in database from the config file, if user_id provided admin will be added to user";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $User = config('forms.models.user')::find(2);
        $tests = config('forms.tests');
        foreach ($tests as $test => $data) {
            $steps = config('forms.tests.' . $test . '.steps');
            foreach ($steps as $step) {
                if ($step <= 100) {
                    $step_name = config('forms.steps')[$step];
                    foreach (['edit', 'view', 'delete'] as $action) {
                        $p = Permission::whereName($test . '_' . $step_name . "_" . $action)->first();
                        if (!$p) {
                            Permission::create(['name' => $test . '_' . $step_name . "_" . $action, 'guard_name' => 'web']);
                            $User->givePermissionTo($test . '_' . $step_name . "_" . $action);
                        }
                    }
                }
            }
            $p = Permission::whereName($test . '_admin')->first();
            if (!$p) {
                Permission::create(['name' => $test . '_admin', 'guard_name' => 'web']);
            }
            echo "Permissions aan de user 1 en 2 toekennen";
            $U = config('forms.models.user')::find(1);
            $U->givePermissionTo($test . '_admin');


        }
    }
}