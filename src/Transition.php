<?php

namespace Transitions;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Transition
 * @package Transitions
 */
abstract class Transition
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @param Request $request
     * @return Request
     */
    public function transformRequest(Request $request) : Request
    {

        return $request;
    }

    /**
     * @param Response $response
     * @return Response
     */
    public function transformResponse(Response $response) : Response
    {

        return $response;
    }

    public function withRequest($request)
    {
        $this->request = $request;
        return $this;
    }
}
