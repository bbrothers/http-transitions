<?php

declare(strict_types=1);

namespace Transitions;

use LogicException;

final class InvalidConfiguration extends LogicException
{
    public static function missingHeaderKey(): self
    {
        return new self(
            'A header key must be set in the transitions.php config file array '.
            'under the `headerKey` key, to identify a request version.'.PHP_EOL.
            'see https://github.com/bbrothers/http-transitions#usage for details.'.PHP_EOL.
            "Example: `['headerKey' => 'Api-Version']`"
        );
    }

    public static function transitionsMustBeKeyedByVersion(): self
    {
        return new self('The `transitions` config must be an array of Transition class lists, keyed by version.');
    }

    public static function transitionsMustBeAList(string|int $stage): self
    {
        return new self("The `transitions` entry for stage `{$stage}` must be a list of Transition class names.");
    }

    public static function notATransition(mixed $class, string|int $stage): self
    {
        $name = is_string($class) ? $class : get_debug_type($class);

        return new self("`{$name}` in stage `{$stage}` must be a class extending ".Transition::class.'.');
    }
}
