<?php

namespace Transitions;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class FirstNameLastNameToFullNameTransition
 * @package Transitions
 */
class FirstNameLastNameToFullNameTransition extends Transition
{

    /**
     * @param Response $response
     * @return Response
     */
    public function transformResponse(Response $response) : Response
    {
        $content = json_decode($response->getContent(), true);
        $content['name'] = implode(' ', [$content['first_name'], $content['last_name']]);

        return $response->setContent(json_encode($content));
    }
}
