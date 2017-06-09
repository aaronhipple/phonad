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
    public static function compose(...$transforms)
    {
        if (count($transforms) < 1) {
            throw new InvalidArgumentException('compose requires at least one argument.');
        }

        $reduce = function ($carry, callable $item) {
            return call_user_func_array($item, (array)$carry);
        };

        return function (...$arguments) use ($transforms, $reduce) {
            return array_reduce($transforms, $reduce, $arguments);
        };
    }

    /**
     * maybeUnpack either unpacks a Monadic value or returns the raw value.
     *
     * @param $value mixed
     * @return mixed
     */
    public static function maybeUnpack($value)
    {
        if ($value instanceof Monad) {
            return $value->unpack();
        }
        return $value;
    }
}
