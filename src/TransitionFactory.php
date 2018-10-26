<?php

namespace Transitions;

interface TransitionFactory
{

    public function create(string $transition) : Transition;
}
