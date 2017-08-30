<?php

namespace Transitions;

use Illuminate\Http\Request;

/**
 * Class FullNameToNameTransition
 * @package Transitions
 */
class FullNameToNameTransition extends Transition
{

    /**
     * @param Request $request
     * @return Request
     */
    public function transformRequest(Request $request) : Request
    {
        if (! $request->request->has('full_name')) {
            return $request;
        }
        $request->request->set('name', $request->request->get('full_name'));
        $request->request->remove('full_name');
        return $request;
    }
}
