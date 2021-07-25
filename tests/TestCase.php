<?php
namespace mmerlijn\forms\tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use mmerlijn\forms\FormsServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;
    //protected $loadEnvironmentVariables = true;


    public function setUp(): void
    {
        // Code before application created.

        parent::setUp();

        // Code after application created.
    }

    protected function getPackageProviders($app)
    {
        return [
            FormsServiceProvider::class
        ];

    }
    protected function getEnvironmentSetUp($app)
    {

    }
    protected function getApplicationTimezone($app)
    {
        return "Europe/Amsterdam";
    }


    protected function defineEnvironment($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadLaravelMigrations();
        $this->artisan('migrate', ['--database' => 'testbench'])->run();

        $this->beforeApplicationDestroyed(function () {
            $this->artisan('migrate:rollback', ['--database' => 'testbench'])->run();
        });
    }
}