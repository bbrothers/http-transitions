<?php

namespace Transitions;

use Illuminate\Contracts\Http\Kernel;
use Orchestra\Testbench\TestCase as Orchestra;

/**
 * Class TestCase
 * @package Transitions
 */
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
    protected function getPackageProviders($app)
    {

        return [TransitionProvider::class];
    }

    protected function setupConfig()
    {

        $this->app['config']->set('transitions', [
            'headerKey'   => 'Api-Version',
            'transitions' => [
                '20160101' => [
                    FullNameToNameTransition::class,
                    NameToFirstNameLastNameTransition::class,
                    BirthDateTransition::class,
                ],
                '20150101' => [
                    FirstNameLastNameToFullNameTransition::class,
                ],
            ],
        ]);
    }

    protected function setUpRoutes()
    {

        $this->app['router']->get('users/{id}', function ($id) {

            return [
                'id'   => $id,
                'name' => [
                    'first_name' => 'John',
                    'last_name'  => 'Doe',
                ],
                'age'  => 40,
            ];
        });

        $this->app['router']->post('users', function () {

            return $this->app['request']->only(['id', 'name', 'age']);
        });

        $this->app['router']->getRoutes()->refreshNameLookups();
    }

    protected function setUpMiddleware()
    {

        $this->app[Kernel::class]->pushMiddleware(TransitionMiddleware::class);
    }
}
