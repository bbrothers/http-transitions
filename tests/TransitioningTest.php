<?php

declare(strict_types=1);

namespace Transitions;

use DateTime;
use PHPUnit\Framework\Attributes\Test;

final class TransitioningTest extends TestCase
{
    #[Test]
    public function it_transitions_a_request_transaction(): void
    {
        $this->getJson('users/123', ['Api-Version' => '20150101'])->assertJson([
            'id' => 123,
            'name' => 'John Doe',
            'birth_date' => (new DateTime('-40 years'))->format('Y-m-d'),
        ]);
    }

    #[Test]
    public function it_leaves_the_response_untouched_on_the_current_version(): void
    {
        $this->getJson('users/123', ['Api-Version' => '20170101'])->assertExactJson([
            'id' => 123,
            'name' => ['first_name' => 'John', 'last_name' => 'Doe'],
            'age' => 40,
        ]);
    }
}
