<?php

namespace Transitions;

use Illuminate\Contracts\Container\Container;

class LaravelTransitionFactory implements TransitionFactory
{

    /**
     * @var array
     */
    private static $transitions = [];

    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $container)
    {

        $this->container = $container;
    }

    public function create(string $transition) : Transition
    {

        if (! isset(self::$transitions[$transition])) {
            self::$transitions[$transition] = $this->container->make($transition);
        }
        return self::$transitions[$transition];
    }
}
