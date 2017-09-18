<?php

namespace Transitions;

use Illuminate\Contracts\Container\Container;

/**
 * Class TransitionFactory
 */
class TransitionFactory
{

    /**
     * @var array
     */
    private static $transitions = [];

    /**
     * @var Container
     */
    private $container;

    /**
     * TransitionFactory constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {

        $this->container = $container;
    }

    /**
     * @param string $transition
     * @return Transition
     */
    public function create(string $transition) : Transition
    {

        if (! isset(self::$transitions[$transition])) {
            self::$transitions[$transition] = $this->container->make($transition);
        }
        return self::$transitions[$transition];
    }
}
