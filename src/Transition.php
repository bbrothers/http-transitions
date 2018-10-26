<?php

namespace Transitions;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class Transition
{

    /**
     * @var Request
     */
    protected $request;

    public function transformRequest(Request $request) : Request
    {

        return $request;
    }

    public function transformResponse(Response $response) : Response
    {

        return $response;
    }

    public function withRequest($request) : Transition
    {

        $this->request = $request;
        return $this;
    }
}
