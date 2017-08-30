<?php

namespace Transitions;

use DateTime;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BirthDateTransition
 * @package Transitions
 */
class BirthDateTransition extends Transition
{

    /**
     * @param Response $response
     * @return Response
     */
    public function transformResponse(Response $response) : Response
    {
        $content = json_decode($response->getContent(), true);
        $content['birth_date'] = (new DateTime("-{$content['age']} years"))->format('Y-m-d');
        unset($content['age']);

        return $response->setContent(json_encode($content));
    }
}
