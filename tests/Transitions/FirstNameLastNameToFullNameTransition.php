<?php

declare(strict_types=1);

namespace Transitions\Transitions;

class FirstNameLastNameToFullNameTransition extends JsonResponseTransition
{
    protected function transformJson(array $content): array
    {
        ['first_name' => $first, 'last_name' => $last] = $content;

        if (! is_string($first) || ! is_string($last)) {
            return $content;
        }

        $content['name'] = "{$first} {$last}";

        return $content;
    }
}
