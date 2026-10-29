<?php

declare(strict_types=1);

namespace Transitions;

use Illuminate\Container\Container;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Transitions\Transitions\BirthDateTransition;

final class LaravelTransitionFactoryTest extends TestCase
{
    #[Test]
    public function it_can_instantiate_a_transition(): void
    {
        $factory = new LaravelTransitionFactory(new Container);

        $this->assertInstanceOf(BirthDateTransition::class, $factory->create(BirthDateTransition::class));
    }

    #[Test]
    public function it_returns_the_same_transition_instance_on_subsequent_calls(): void
    {
        $factory = new LaravelTransitionFactory(new Container);

        $this->assertSame(
            $factory->create(BirthDateTransition::class),
            $factory->create(BirthDateTransition::class),
        );
    }
}
