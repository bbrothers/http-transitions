<?php

declare(strict_types=1);

namespace Transitions;

final readonly class Version
{
    private function __construct(public string $value) {}

    public static function from(string|int $value): self
    {
        return new self((string) $value);
    }

    public static function fromHeader(?string $header): ?self
    {
        $first = preg_split('/\s*,\s*/', trim($header ?? ''), 2)[0] ?? '';

        return $first === '' ? null : new self($first);
    }

    public function isAtOrBefore(self $other): bool
    {
        return strnatcmp($this->value, $other->value) <= 0;
    }
}
