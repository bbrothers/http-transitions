<?php

declare(strict_types=1);

namespace Transitions;

use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

final class TransitionMiddlewareTest extends TestCase
{
    #[Test]
    public function it_does_not_run_transitions_when_the_api_version_is_newer_than_every_transition(): void
    {
        $request = new Request;
        $request->headers->set('Api-Version', '20170101');

        $this->handle($request)->assertExactJson($this->stubbedUser());
    }

    #[Test]
    public function it_runs_all_transitions_newer_than_the_supplied_version(): void
    {
        $request = new Request;
        $request->headers->set('Api-Version', '20150101');

        $this->handle($request)->assertJson($this->transitionedUser());
    }

    #[Test]
    public function it_transitions_a_request_submission(): void
    {
        $request = Request::create('/', 'POST', ['full_name' => 'John Doe']);
        $request->headers->set('Api-Version', '20150101');

        $middleware = new TransitionMiddleware($this->stubbedConfig(), $this->factory());
        $middleware->handle($request, function (Request $request) {
            $this->assertSame('John Doe', $request->request->get('name'));
            $this->assertFalse($request->request->has('full_name'));

            return new JsonResponse($this->stubbedUser());
        });
    }

    #[Test]
    public function it_creates_each_transition_after_the_previous_one_has_transformed_the_request(): void
    {
        $request = Request::create('/', 'POST', ['full_name' => 'John Doe']);
        $request->headers->set('Api-Version', '20160101');

        $factory = new class($request) implements TransitionFactory
        {
            /** @var list<mixed> */
            public array $namesSeenAtCreation = [];

            public function __construct(private readonly Request $request) {}

            public function create(string $transition): Transition
            {
                $this->namesSeenAtCreation[] = $this->request->request->get('name');

                return new $transition;
            }
        };

        $config = Config::fromArray([
            'headerKey' => 'Api-Version',
            'transitions' => ['20160101' => [Transitions\FullNameToNameTransition::class, Transitions\BirthDateTransition::class]],
        ]);

        (new TransitionMiddleware($config, $factory))->handle($request, fn () => new JsonResponse($this->stubbedUser()));

        $this->assertSame([null, 'John Doe', 'John Doe', 'John Doe'], $factory->namesSeenAtCreation);
    }

    #[Test]
    public function it_selects_response_transitions_from_the_version_header_after_the_request_is_handled(): void
    {
        $request = new Request;
        $request->headers->set('Api-Version', '20150101');

        $middleware = new TransitionMiddleware($this->stubbedConfig(), $this->factory());
        $response = $middleware->handle($request, function (Request $request) {
            $request->headers->set('Api-Version', '20170101');

            return new JsonResponse($this->stubbedUser());
        });

        TestResponse::fromBaseResponse($response)->assertExactJson($this->stubbedUser());
    }

    /** @return TestResponse<Response> */
    private function handle(Request $request): TestResponse
    {
        $middleware = new TransitionMiddleware($this->stubbedConfig(), $this->factory());

        return TestResponse::fromBaseResponse(
            $middleware->handle($request, fn () => new JsonResponse($this->stubbedUser())),
        );
    }

    /** @return array<string, mixed> */
    private function stubbedUser(): array
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

    /** @return array<string, mixed> */
    private function transitionedUser(): array
    {
        return [
            'id' => 123,
            'name' => 'John Doe',
            'birth_date' => (new DateTime('-40 years'))->format('Y-m-d'),
        ];
    }

    private function stubbedConfig(): Config
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

    private function factory(): TransitionFactory
    {
        return new class implements TransitionFactory
        {
            public function create(string $transition): Transition
            {
                return new $transition;
            }
        };
    }
}
