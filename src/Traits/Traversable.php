<?php namespace Phonad\Traits;

/**
 * Traversable provides operations that may be used in Traversable monads.
 */
trait Traversable
{
    /**
     * at returns a callback for retrieving a value of a keyed type at the given key.
     *
     * @param $key string
     */
    protected static function at($key)
    {
        return function ($element) use ($key) {
            if (is_array($element)) {
                return isset($element[$key])
                ? $element[$key]
                : null;
            }
            if (is_object($element)) {
                return property_exists($element, $key)
                ? $element->{$key}
                : null;
            }
            return null;
        };
    }
}
