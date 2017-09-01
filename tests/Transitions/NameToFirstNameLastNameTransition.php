<?php

namespace Transitions\Transitions;

use Symfony\Component\HttpFoundation\Response;
use Transitions\Transition;

/**
 * Class NameToFirstNameLastNameTransition
 * @package Transitions
 */
class NameToFirstNameLastNameTransition extends Transition
{

    /**
     * @param Response $response
     * @return Response
     */
    public function transformResponse(Response $response) : Response
    {
        $content = json_decode($response->getContent(), true);
        $content['first_name'] = $content['name']['first_name'];
        $content['last_name'] = $content['name']['last_name'];
        unset($content['name']);

        return $response->setContent(json_encode($content));
    }
}
