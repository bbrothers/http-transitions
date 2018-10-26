<?php

namespace Transitions;

use LogicException;

class HeaderKeyNotDefined extends LogicException
{

    public static function new()
    {

        return new static(
            'A header key must be set in the transitions.php config file array ' .
            'under the `headerKey` key, to identify a request version.' . PHP_EOL .
            'see https://github.com/bbrothers/http-transitions#usage for details.' . PHP_EOL .
            "Example: `['headerKey' => 'Api-Version']"
        );
    }
}
