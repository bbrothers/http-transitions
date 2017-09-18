<?php

namespace Transitions;

use Transitions\Transitions\BirthDateTransition;

class TransitionFactoryTest extends TestCase
{

    /** @test */
    public function itCanInstantiateATransition()
    {

        $factory = new TransitionFactory($this->app);
        $transition = $factory->create(BirthDateTransition::class);
        $this->assertInstanceOf(Transition::class, $transition);
        $this->assertSame($transition, $factory->create(BirthDateTransition::class));
    }
}
