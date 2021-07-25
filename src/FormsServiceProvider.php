<?php

namespace mmerlijn\forms;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use mmerlijn\forms\Console\MakePermissionCommand;
use mmerlijn\forms\Http\Livewire\Beoordeling;
use mmerlijn\forms\Http\Livewire\fundus\Tropicamide;
use mmerlijn\forms\Http\Livewire\Onderzoek;
use mmerlijn\forms\Http\Livewire\Overzicht;
use mmerlijn\forms\Http\Livewire\Patient;
use mmerlijn\forms\Http\Livewire\Question;
use mmerlijn\forms\Http\Livewire\Requester;
use mmerlijn\forms\Http\Livewire\RequesterCopy;
use mmerlijn\forms\Http\Livewire\Save;
use mmerlijn\forms\Models\Test;
use mmerlijn\forms\View\Components\AppLayout;
use mmerlijn\forms\View\Components\GuestLayout;

class FormsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/forms.php' => config_path('forms.php'),
            ], 'forms-config');
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'migrations');
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/forms'),
        ], 'views');


        $this->loadRoutesFrom(__DIR__.'/../routes/forms.php');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'forms');
        $this->loadViewComponentsAs('forms', [
            AppLayout::class,
            GuestLayout::class,
         //   Alert::class,
         //   Button::class,
        ]);
        $this->livewireComponents();
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakePermissionCommand::class, // registering the new command
            ]);
        }

    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/forms.php',
            'forms'
        );
    }
    protected function livewireComponents()
    {
        Livewire::component('forms-question', Question::class);
        Livewire::component('forms-overzicht', Overzicht::class);
        Livewire::component('forms-save', Save::class);
        Livewire::component('forms-patient', Patient::class);
        Livewire::component('forms-onderzoek', Onderzoek::class);
        Livewire::component('forms-beoordeling', Beoordeling::class);
        Livewire::component('forms-requester', Requester::class);
        Livewire::component('forms-requestercc', RequesterCopy::class);
        Livewire::component('forms-fundus-tropicamide', Tropicamide::class);
    }
}