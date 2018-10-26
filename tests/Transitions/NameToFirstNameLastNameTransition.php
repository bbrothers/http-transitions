<?php

namespace Transitions\Transitions;

use Symfony\Component\HttpFoundation\Response;
use Transitions\Transition;

class NameToFirstNameLastNameTransition extends Transition
{

    public function transformResponse(Response $response) : Response
    {
        $content = json_decode($response->getContent(), true);
        $content = array_diff_key(array_merge($content, $content['name']), ['name' => true]);

        return $response->setContent(json_encode($content));
    }
}
