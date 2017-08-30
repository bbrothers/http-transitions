<?php

namespace Transitions;

use DateTime;

/**
 * Class TransitioningTest
 * @package Transitions
 */
class TransitioningTest extends TestCase
{

    /** @test */
    public function itWillNotRunTransitionsWhenApiVersionIsGreaterThanTheTransitionsVersion()
    {
        $this->getJson('users/123', ['Api-Version' => '20170101'])
             ->assertJson([
                 'id'   => 123,
                 'name' => [
                     'first_name' => 'John',
                     'last_name'  => 'Doe',
                 ],
                 'age'  => 40,
             ]);
    }

    /** @test */
    public function itWillRunAllTransitionsGreaterThanTheSuppliedVersion()
    {

        $this->getJson('users/123', ['Api-Version' => '20150101'])->assertJson([
            'id'         => 123,
            'name'       => 'John Doe',
            'birth_date' => (new DateTime('-40 years'))->format('Y-m-d'),
        ]);
    }

    /** @test */
    public function itWillTransitionARequestSubmission()
    {
        $data = [
            'id' => 123,
            'full_name' => [
                'first_name' => 'Jane',
                'last_name' => 'Doe',
            ],
            'age' => 35,
        ];
        $this->postJson('users', $data, ['Api-Version' => '20150101'])->assertJson([
            'id'   => 123,
            'name' => 'Jane Doe',
            'birth_date' => (new DateTime('-35 years'))->format('Y-m-d'),
        ]);
    }

    /** @test */
    public function itThrowsAnExceptionIfNoHeaderKeyIsConfigured()
    {

        $this->app['config']['transitions'] = [];
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessageRegExp('/^A header key must be set/');
        $this->getJson('users/123');
    }
}
