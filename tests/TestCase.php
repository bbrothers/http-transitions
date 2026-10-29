<?php

declare(strict_types=1);

namespace Transitions;

use Illuminate\Contracts\Config\Repository;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [TransitionProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        $app->make(Repository::class)->set('transitions', [
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

    protected function defineRoutes($router): void
    {
        $router->get('users/{id}', fn (string $id) => [
            'id' => (int) $id,
            'name' => [
                'first_name' => 'John',
                'last_name' => 'Doe',
            ],
            'age' => 40,
        ])->middleware(TransitionMiddleware::class);
    }
}
