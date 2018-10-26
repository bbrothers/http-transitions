<?php

namespace Transitions\Transitions;

use Illuminate\Http\Request;
use Transitions\Transition;

class FullNameToNameTransition extends Transition
{

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
