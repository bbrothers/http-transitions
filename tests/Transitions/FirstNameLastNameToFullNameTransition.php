<?php

namespace Transitions\Transitions;

use Symfony\Component\HttpFoundation\Response;
use Transitions\Transition;

class FirstNameLastNameToFullNameTransition extends Transition
{

    public function transformResponse(Response $response) : Response
    {

        $content = json_decode($response->getContent(), true);
        $content['name'] = implode(' ', [$content['first_name'], $content['last_name']]);

        return $response->setContent(json_encode($content));
    }
}
