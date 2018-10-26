<?php

namespace Transitions;

use Illuminate\Contracts\Http\Kernel;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{

    public function setUp()
    {

        parent::setUp();
        $this->setupConfig();
        $this->setUpRoutes();
        $this->setUpMiddleware();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app) : array
    {

        return [TransitionProvider::class];
    }

    protected function setupConfig() : void
    {

        $this->app['config']->set('transitions', [
            'headerKey' => 'Api-Version',
            'transitions' => [
                '20160101' => [
                    Transitions\FullNameToNameTransition::class,
                    Transitions\NameToFirstNameLastNameTransition::class,
                    Transitions\BirthDateTransition::class,
                ],
                '20150101' => [
                    Transitions\FirstNameLastNameToFullNameTransition::class,
                ],
            ],
        ]);
    }

    protected function setUpRoutes() : void
    {

        $this->app['router']->get('users/{id}', function ($id) {

            return [
                'id' => $id,
                'name' => [
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                ],
                'age' => 40,
            ];
        });

        $this->app['router']->getRoutes()->refreshNameLookups();
    }

    protected function setUpMiddleware() : void
    {

        $this->app[Kernel::class]->pushMiddleware(TransitionMiddleware::class);
    }
}
