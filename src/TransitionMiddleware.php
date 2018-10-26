<?php

namespace Transitions;

use Closure;
use Generator;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TransitionMiddleware
{

    /**
     * @var Config
     */
    private $config;
    /**
     * @var TransitionFactory
     */
    private $factory;

    public function __construct(Config $config, TransitionFactory $factory)
    {

        $this->config = $config;
        $this->factory = $factory;
    }

    public function handle(Request $request, Closure $next) : Response
    {

        $request = $this->transformRequest($request);
        return $this->transformResponse($request, $next($request));
    }

    private function transformRequest(Request $request) : Request
    {

        foreach ($this->transitions($request->header($this->config->headerKey())) as $version) {
            $request = $version->transformRequest($request);
        }
        return $request;
    }

    private function transformResponse(Request $request, Response $response) : Response
    {

        foreach ($this->transitions($request->header($this->config->headerKey())) as $transition) {
            $response = $transition->withRequest($request)->transformResponse($response);
        }
        return $response;
    }

    /**
     * @param string|int $version
     * @return Generator|Transition[]
     */
    private function transitions($version) : Generator
    {

        foreach ($this->config->transitionsForVersion($version) as $transition) {
            yield $this->factory->create($transition);
        }
    }
}
