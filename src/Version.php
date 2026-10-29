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
        return $header === null || $header === '' ? null : new self($header);
    }

    public function isAtOrBefore(self $other): bool
    {
        return strnatcmp($this->value, $other->value) <= 0;
    }
}
