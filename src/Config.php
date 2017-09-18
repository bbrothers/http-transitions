<?php

namespace Transitions;

use Generator;

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

    /**
     * Config constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {

        $this->setHeaderKey($attributes)
             ->setTransitions($attributes);
    }

    /**
     * @return string
     */
    public function headerKey()
    {

        return $this->headerKey;
    }

    /**
     * Get the applicable transitions for the given version.
     *
     * @param string|int $version
     * @return array
     */
    public function transitionsForVersion($version) : array
    {

        $applicable = [];
        foreach ($this->filterStages($version) as $key => $classes) {
            $applicable = array_merge($applicable, $classes);
        }

        return $applicable;
    }

    /**
     * @param string|int $version
     * @return Generator
     */
    private function filterStages($version) : Generator
    {

        foreach ($this->transitions as $key => $class) {
            if ($version <= $key) {
                yield $key => $class;
            }
        }
    }

    /**
     * @param array $attributes
     * @return Config
     */
    public function setHeaderKey(array $attributes) : Config
    {

        if (! isset($attributes['headerKey'])) {
            throw HeaderKeyNotDefined::new();
        }
        $this->headerKey = $attributes['headerKey'];

        return $this;
    }

    /**
     * @param array $attributes
     * @return Config
     */
    public function setTransitions(array $attributes) : Config
    {

        $this->transitions = (array) $attributes['transitions'] ?? [];
        return $this;
    }
}
