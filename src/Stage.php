<?php

declare(strict_types=1);

namespace Transitions;

final readonly class Stage
{
    private const string EVERY_VERSION = '*';

    /** @param  list<class-string<Transition>>  $transitions */
    private function __construct(
        private ?Version $version,
        public array $transitions,
    ) {}

    /** @param  list<class-string<Transition>>  $transitions */
    public static function forVersion(Version $version, array $transitions): self
    {
        return new self($version, $transitions);
    }

    /** @param  list<class-string<Transition>>  $transitions */
    public static function forEveryVersion(array $transitions): self
    {
        return new self(null, $transitions);
    }

    public static function fromConfig(string|int $key, mixed $transitions): self
    {
        if (! is_array($transitions) || ! array_is_list($transitions)) {
            throw InvalidConfiguration::transitionsMustBeAList($key);
        }

        $classes = array_map(
            fn (mixed $class): string => is_string($class) && is_subclass_of($class, Transition::class)
                ? $class
                : throw InvalidConfiguration::notATransition($class, $key),
            $transitions,
        );

        return $key === self::EVERY_VERSION
            ? self::forEveryVersion($classes)
            : self::forVersion(Version::from($key), $classes);
    }

    public function appliesTo(?Version $requested): bool
    {
        return $this->version === null
            || $requested === null
            || $requested->isAtOrBefore($this->version);
    }
}
