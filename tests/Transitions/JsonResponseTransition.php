<?php

declare(strict_types=1);

namespace Transitions\Transitions;

use Symfony\Component\HttpFoundation\Response;
use Transitions\Transition;
use UnexpectedValueException;

abstract class JsonResponseTransition extends Transition
{
    public function transformResponse(Response $response): Response
    {
        $content = json_decode((string) $response->getContent(), true, flags: JSON_THROW_ON_ERROR);

        if (! is_array($content)) {
            throw new UnexpectedValueException('Expected a JSON object response.');
        }

        return $response->setContent(json_encode($this->transformJson($content), JSON_THROW_ON_ERROR));
    }

    /**
     * @param  array<mixed>  $content
     * @return array<mixed>
     */
    abstract protected function transformJson(array $content): array;
}
