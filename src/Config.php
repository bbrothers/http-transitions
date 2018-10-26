<?php

namespace Transitions;

class Config
{

    /**
     * The header key for the applicable transition version.
     * @var string
     */
    protected $headerKey;

    /**
     * An array of transitions to apply, keyed by version date.
     * @var array
     */
    protected $transitions;

    public function __construct(string $headerKey = '', array $transitions = [])
    {

        $this->setHeaderKey($headerKey)
             ->setTransitions($transitions);
    }

    public static function fromArray(array $attributes) : Config
    {

        return new static($attributes['headerKey'] ?? '', $attributes['transitions'] ?? []);
    }

    public function headerKey() : string
    {

        return $this->headerKey;
    }

    /**
     * Get the applicable transitions for the given version.
     *
     * @param string|int $version
     * @return array|Transition[]
     */
    public function transitionsForVersion($version) : array
    {

        return array_reduce($this->filterStages($version), function (array $applicable, array $classes) {

            return array_merge($applicable, $classes);
        }, []);
    }

    /**
     * @param string|int $version
     * @return array
     */
    private function filterStages($version) : array
    {

        return array_filter($this->transitions, function ($key) use ($version) {

            return $version <= $key;
        }, ARRAY_FILTER_USE_KEY);
    }

    public function setHeaderKey(string $headerKey = '') : Config
    {

        if (empty($headerKey)) {
            throw HeaderKeyNotDefined::new();
        }
        $this->headerKey = $headerKey;

        return $this;
    }

    public function setTransitions(array $transitions = []) : Config
    {

        $this->transitions = $transitions;
        return $this;
    }
}
