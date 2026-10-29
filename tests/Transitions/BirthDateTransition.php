<?php

declare(strict_types=1);

namespace Transitions\Transitions;

use DateTime;

class BirthDateTransition extends JsonResponseTransition
{
    protected function transformJson(array $content): array
    {
        ['age' => $age] = $content;

        if (! is_int($age)) {
            return $content;
        }

        $content['birth_date'] = (new DateTime("-{$age} years"))->format('Y-m-d');
        unset($content['age']);

        return $content;
    }
}
