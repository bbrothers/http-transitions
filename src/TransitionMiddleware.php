<?php

namespace Transitions;

use Closure;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TransitionMiddleware
 * @package Transitions
 */
class TransitionMiddleware
{

    /**
     * @var Config
     */
    private $config;
    /**
     * @var Container
     */
    private $container;

    /**
     * TransitionMiddleware constructor.
     * @param Config    $config
     * @param Container $container
     */
    public function __construct(Config $config, Container $container)
    {

        $this->config = $config;
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next) : Response
    {

        $request = $this->transformRequest($request);
        return $this->transformResponse($request, $next($request));
    }

    /**
     * @param Request $request
     * @return Request
     */
    private function transformRequest(Request $request)
    {

        foreach ($this->transitions($request->header($this->config->headerKey())) as $version) {
            $request = $version->transformRequest($request);
        }
        return $request;
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @return Response
     */
    private function transformResponse(Request $request, Response $response)
    {

        foreach ($this->transitions($request->header($this->config->headerKey())) as $transition) {
            $response = $transition->withRequest($request)->transformResponse($response);
        }
        return $response;
    }

    /**
     * @param string|int $version
     * @return iterable|Transition[]
     */
    private function transitions($version) : iterable
    {

        /** @var Transition $transition */
        foreach ($this->config->transitionsForRequest($version) as $transition) {
            yield $this->container->make($transition);
        }
    }
}
