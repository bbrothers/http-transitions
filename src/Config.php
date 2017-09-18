<?php

namespace Transitions;

use Illuminate\Support\Collection;
use LogicException;

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
     * @param string|int $version
     * @return Collection
     */
    public function transitionsForRequest($version)
    {

        return Collection::make($this->transitions)
                         ->filter(function ($_, $key) use ($version) {

                             return $version <= $key;
                         })->flatten();
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
