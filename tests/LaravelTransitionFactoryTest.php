<?php

namespace Transitions;

use Closure;
use Illuminate\Contracts\Container\Container;
use Transitions\Transitions\BirthDateTransition;

class LaravelTransitionFactoryTest extends \PHPUnit\Framework\TestCase
{

    /** @test */
    public function itCanInstantiateATransition() : void
    {

        $factory = new LaravelTransitionFactory($this->fakedContainer());
        $transition = $factory->create(BirthDateTransition::class);
        $this->assertInstanceOf(Transition::class, $transition);
    }

    /** @test */
    public function itWillReturnTheSameTransitionInstanceOnSubsequentCalls() : void
    {

        $factory = new LaravelTransitionFactory($this->fakedContainer());
        $this->assertSame(
            $factory->create(BirthDateTransition::class),
            $factory->create(BirthDateTransition::class)
        );
    }

    private function fakedContainer() : Container
    {

        return new class implements Container
        {

            public function make($abstract, array $parameters = [])
            {

                return new $abstract;
            }

            public function bound($abstract)
            {
                //
            }

            public function alias($abstract, $alias)
            {
                //
            }

            public function tag($abstracts, $tags)
            {
                //
            }

            public function tagged($tag)
            {
                //
            }

            public function bind($abstract, $concrete = null, $shared = false)
            {
                //
            }

            public function bindIf($abstract, $concrete = null, $shared = false)
            {
                //
            }

            public function singleton($abstract, $concrete = null)
            {
                //
            }

            public function extend($abstract, Closure $closure)
            {
                //
            }

            public function instance($abstract, $instance)
            {
                //
            }

            public function when($concrete)
            {
                //
            }

            public function factory($abstract)
            {
                //
            }

            public function call($callback, array $parameters = [], $defaultMethod = null)
            {
                //
            }

            public function resolved($abstract)
            {
                //
            }

            public function resolving($abstract, Closure $callback = null)
            {
                //
            }

            public function afterResolving($abstract, Closure $callback = null)
            {
                //
            }

            public function get($id)
            {
                //
            }

            public function has($id)
            {
                //
            }
        };
    }
}
