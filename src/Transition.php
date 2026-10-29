<?php

declare(strict_types=1);

namespace Transitions;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class Transition
{
    protected ?Request $request = null;

    public function transformRequest(Request $request): Request
    {
        return $request;
    }

    public function transformResponse(Response $response): Response
    {
        return $response;
    }

    public function withRequest(Request $request): static
    {
        $this->request = $request;

        return $this;
    }
}
