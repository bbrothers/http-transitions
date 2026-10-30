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

        if (! $this->variesByVersion($response)) {
            $response->setVary($this->config->headerKey, replace: false);
        }

        return $response;
    }

    private function variesByVersion(Response $response): bool
    {
        $vary = array_map(strtolower(...), array_filter($response->getVary(), is_string(...)));

        return in_array('*', $vary, true) || in_array(strtolower($this->config->headerKey), $vary, true);
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
