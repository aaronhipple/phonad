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

    /**
     * concat joins an array of values into a single array of unpacked values.
     *
     * Concat handles a unit of Nothing as a special case, ignoring it.
     *
     * @param $list array
     * @return array
     */
    public static function concat($list)
    {
        return array_reduce($list, function ($carry, $item) {
            return ($item instanceof Nothing || is_null($item))
                ? $carry
                : array_merge($carry, Utilities::maybeUnpack($item));
        }, []);
    }
}
