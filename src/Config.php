<?php

declare(strict_types=1);

namespace Transitions;

final readonly class Config
{
    /** @param  list<Stage>  $stages */
    public function __construct(
        public string $headerKey,
        private array $stages = [],
    ) {
        if ($headerKey === '') {
            throw InvalidConfiguration::missingHeaderKey();
        }
    }

    /** @param  array<mixed>  $attributes */
    public static function fromArray(array $attributes): self
    {
        $headerKey = $attributes['headerKey'] ?? '';
        $transitions = $attributes['transitions'] ?? [];

        if (! is_string($headerKey)) {
            throw InvalidConfiguration::missingHeaderKey();
        }

        if (! is_array($transitions)) {
            throw InvalidConfiguration::transitionsMustBeKeyedByVersion();
        }

        return new self(
            $headerKey,
            array_map(Stage::fromConfig(...), array_keys($transitions), $transitions),
        );
    }

    /** @return list<class-string<Transition>> */
    public function transitionsFor(?Version $requested): array
    {
        $applicable = array_filter($this->stages, fn (Stage $stage): bool => $stage->appliesTo($requested));

        return array_merge(...array_map(fn (Stage $stage): array => $stage->transitions, array_values($applicable)));
    }
}
