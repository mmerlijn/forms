<?php
namespace mmerlijn\forms\tests\Feature;

use mmerlijn\forms\tests\TestCase;

class BasicRouteTest extends TestCase
{

    public function test_forms_index_route_works()
    {
        $this->get(route('forms.index'))
            ->assertSee("Hallo");
    }
}