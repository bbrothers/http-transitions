<?php

declare(strict_types=1);

namespace Transitions;

use Illuminate\Contracts\Container\Container;

final class LaravelTransitionFactory implements TransitionFactory
{
    /** @var array<class-string<Transition>, Transition> */
    private array $transitions = [];

    public function __construct(private readonly Container $container) {}

    public function create(string $transition): Transition
    {
        return $this->transitions[$transition] ??= $this->container->make($transition);
    }
}
