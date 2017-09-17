<?php

namespace Transitions;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TransitionMiddleware
 * @package Transitions
 */
class TransitionMiddleware
{

    protected static $transitions = [];

    /**
     * @var Config
     */
    private $config;
    /**
     * @var TransitionFactory
     */
    private $factory;

    /**
     * TransitionMiddleware constructor.
     * @param Config            $config
     * @param TransitionFactory $factory
     */
    public function __construct(Config $config, TransitionFactory $factory)
    {

        $this->config = $config;
        $this->factory = $factory;
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
            yield $this->factory->create($transition);
        }
    }
}
