<?php

declare(strict_types=1);

namespace Transitions;

use Closure;
use Generator;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class TransitionMiddleware
{
    public function __construct(
        private Config $config,
        private TransitionFactory $factory,
    ) {}

    /** @param  Closure(Request): Response  $next */
    public function handle(Request $request, Closure $next): Response
    {
        foreach ($this->transitionsFor($request) as $transition) {
            $request = $transition->transformRequest($request);
        }

        $response = $next($request);

        foreach ($this->transitionsFor($request) as $transition) {
            $response = $transition->withRequest($request)->transformResponse($response);
        }

        return $response;
    }

    /** @return Generator<Transition> */
    private function transitionsFor(Request $request): Generator
    {
        $version = Version::fromHeader($request->headers->get($this->config->headerKey));

        foreach ($this->config->transitionsFor($version) as $transition) {
            yield $this->factory->create($transition);
        }
    }
}
