<?php

namespace Transitions;

use DateTime;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

class TransitionMiddlewareTest extends TestCase
{

    /** @test */
    public function itWillNotRunTransitionsWhenApiVersionIsGreaterThanTheTransitionsVersion() : void
    {

        $middleware = new TransitionMiddleware($this->stubbedConfig(), $this->factory());
        $request = new Request;
        $request->headers->set('Api-Version', '20170101');

        $response = $middleware->handle($request, function () {

            return new JsonResponse($this->stubbedUser());
        });

        TestResponse::fromBaseResponse($response)
                    ->assertJson($this->stubbedUser());
    }

    /** @test */
    public function itWillRunAllTransitionsGreaterThanTheSuppliedVersion() : void
    {

        $middleware = new TransitionMiddleware($this->stubbedConfig(), $this->factory());
        $request = new Request;
        $request->headers->set('Api-Version', '20150101');

        $response = $middleware->handle($request, function () {

            return new JsonResponse($this->stubbedUser());
        });

        TestResponse::fromBaseResponse($response)->assertJson([
            'id' => 123,
            'name' => 'John Doe',
            'birth_date' => (new DateTime('-40 years'))->format('Y-m-d'),
        ]);
    }

    /** @test */
    public function itWillTransitionARequestSubmission() : void
    {

        $middleware = new TransitionMiddleware($this->stubbedConfig(), $this->factory());
        $request = Request::create('/', 'POST', $this->stubbedUser());
        $request->headers->set('Api-Version', '20150101');

        $response = $middleware->handle($request, function () {

            return new JsonResponse($this->stubbedUser());
        });

        TestResponse::fromBaseResponse($response)->assertJson([
            'id' => 123,
            'name' => 'John Doe',
            'birth_date' => (new DateTime('-40 years'))->format('Y-m-d'),
        ]);
    }

    private function stubbedUser() : array
    {

        return [
            'id' => 123,
            'name' => [
                'first_name' => 'John',
                'last_name' => 'Doe',
            ],
            'age' => 40,
        ];
    }

    private function stubbedConfig() : Config
    {

        return Config::fromArray([
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

    private function factory() : TransitionFactory
    {

        return new class implements TransitionFactory
        {

            public function create(string $transition) : Transition
            {

                return new $transition;
            }
        };
    }
}
