<?php namespace Phonad\Traits;

use Phonad\Nothing;
use Phonad\Utilities;

/**
 * Collection provides operations that can be used on Collection monads.
 */
trait Collection
{
    /**
     * at returns a callback for retrieving a value of a keyed type at the given key.
     *
     * @param $key string
     */
    protected static function where(callable $test)
    {
        return function ($element) use ($test) {
            return $test($element)
            ? $element
            : null;
        };
    }
}
