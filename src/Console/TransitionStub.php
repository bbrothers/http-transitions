<?php

declare(strict_types=1);

namespace Transitions\Console;

enum TransitionStub: string
{
    case Full = 'transition';
    case RequestOnly = 'request_only_transition';
    case ResponseOnly = 'response_only_transition';

    public static function forOptions(bool $requestOnly, bool $responseOnly): self
    {
        return match (true) {
            $requestOnly => self::RequestOnly,
            $responseOnly => self::ResponseOnly,
            default => self::Full,
        };
    }

    public function path(): string
    {
        return dirname(__DIR__, 2)."/stubs/{$this->value}.stub";
    }
}
