<?php namespace aaronhipple\phonad\Traits;

use aaronhipple\phonad\Nothing;

trait Traversable {
    /**
     * at returns a callback for retrieving a value of a keyed type at the given key.
     *
     * @param $key string
     */
    public static function at($key)
    {
        return function ($element) use ($key) {
            if (is_array($element)) {
                return isset($element[$key])
                ? new static($element[$key])
                : new Nothing;
            }
            if (is_object($element)) {
                return property_exists($element, $key)
                ? new static($element->{$key})
                : new Nothing;
            }
            return new Nothing;
        };
    }
}
