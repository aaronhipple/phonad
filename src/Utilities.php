<?php namespace aaronhipple\phonad;

use InvalidArgumentException;

class Utilities
{
    /**
     * compose assembles a new function from several others.
     *
     * @param callable $transforms... Functions from which to compose our resulting function.
     * @return callable
     */
    public static function compose()
    {
        $transforms = func_get_args();
        if (count($transforms) < 1) {
            throw new InvalidArgumentException('compose requires at least one argument.');
        }

        $reduce = function ($carry, callable $item) {
            return call_user_func_array($item, (array)$carry);
        };

        return function () use ($transforms, $reduce) {
            $arguments = func_get_args();
            return array_reduce($transforms, $reduce, $arguments);
        };
    }

    /**
     * maybeBind returns a wrapped callback that may either
     * run directly on a value or bind to a Monadic value.
     *
     * @param callable $transform
     * @return callable
     */
    public static function maybeBind(callable $transform)
    {
        return function ($value) use ($transform) {
            if ($value instanceof Monad) {
                return $value->bind($transform);
            }
            return $transform($value);
        };
    }

    /**
     * maybeUnpack returns a function that either unpacks
     * a Monadic value or returns the raw value.
     *
     * @return callable
     */
    public static function maybeUnpack()
    {
        return function ($value) {
            if ($value instanceof Monad) {
                return $value->unpack();
            }
            return $value;
        };
    }
}
