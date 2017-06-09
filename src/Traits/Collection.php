<?php namespace aaronhipple\phonad\Traits;

use aaronhipple\phonad\Nothing;

trait Collection {
    /**
     * at returns a callback for retrieving a value of a keyed type at the given key.
     *
     * @param $key string
     */
    protected static function where(callable $test)
    {
        return function ($element) use ($test) {
            return $test($element)
            ? new static($element)
            : new Nothing;
        };
    }
}
