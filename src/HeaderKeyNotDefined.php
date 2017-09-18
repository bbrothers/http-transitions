<?php

namespace Transitions;

use LogicException;

class HeaderKeyNotDefined extends LogicException
{

    public static function new()
    {

        return new static(
            'A header key must be set in the transitions.php ' .
            'config file to identify a request version.'
        );
    }
}
