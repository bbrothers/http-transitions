<?php

namespace Transitions\Transitions;

use DateTime;
use Symfony\Component\HttpFoundation\Response;
use Transitions\Transition;

class BirthDateTransition extends Transition
{

    public function transformResponse(Response $response) : Response
    {
        $content = json_decode($response->getContent(), true);
        $content['birth_date'] = (new DateTime("-{$content['age']} years"))->format('Y-m-d');
        unset($content['age']);

        return $response->setContent(json_encode($content));
    }
}
