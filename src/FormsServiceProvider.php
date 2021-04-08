<?php

namespace mmerlijn\forms;

use Illuminate\Support\ServiceProvider;

class FormsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/forms.php' => config_path('forms.php'),
        ], 'forms-config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/forms.php',
            'forms'
        );
    }
}