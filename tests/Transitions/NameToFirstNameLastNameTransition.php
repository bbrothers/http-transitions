<?php

declare(strict_types=1);

namespace Transitions\Transitions;

class NameToFirstNameLastNameTransition extends JsonResponseTransition
{
    protected function transformJson(array $content): array
    {
        ['name' => $name] = $content;

        if (! is_array($name)) {
            return $content;
        }

        return array_diff_key([...$content, ...$name], ['name' => true]);
    }
}
