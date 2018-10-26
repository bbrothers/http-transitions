<?php

namespace Transitions;

use DateTime;

class TransitioningTest extends TestCase
{

    /** @test */
    public function itWillTransitionARequestTransaction() : void
    {

        $this->getJson('users/123', ['Api-Version' => '20150101'])->assertJson([
            'id'         => 123,
            'name'       => 'John Doe',
            'birth_date' => (new DateTime('-40 years'))->format('Y-m-d'),
        ]);
    }
}
