<?php

declare(strict_types=1);

namespace Transitions;

interface TransitionFactory
{
    /** @param  class-string<Transition>  $transition */
    public function create(string $transition): Transition;
}
