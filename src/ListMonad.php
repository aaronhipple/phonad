<?php namespace aaronhipple\phonad;

use InvalidArgumentException;

/**
 * ListMonad permits the chaining of operations on a list of items.
 *
 * Example usage:
 *   use aaronhipple\phonad\List;
 *   use PHPUnit\Framework\Assert;
 *
 *   $value = new List([1, 2, 3]);
 *
 *   $result = $value
 *     ->bind(function ($x) { return [$x - 1]; })
 *     ->bind(function ($x) { return [$x * 2]; })
 *     ->unpack();
 *
 *   Assert::assertEquals([0, 2, 4], $result);
 */
class ListMonad extends Monad
{
    /**
     * Represent ListMonad::unit as a const containing a callable such
     * that it may be easily passed as a callback.
     */
    public const unit = 'aaronhipple\phonad\ListMonad::unit';

    /**
     * ListMonad constructor.
     *
     * @param $value mixed The value to be contained. If not an array, an array will be constructed.
     */
    public function __construct($value)
    {
        if (!(is_array($value) || $value instanceof Traversable)) {
            throw new InvalidArgumentException('List must be constructed using an array or Traversable object');
        }
        $this->value = $value;
    }

    /**
     * Apply a transformation to each item of the monad.
     *
     * @param callable $transform
     * @return ListMonad A transformed instance of the monad.
     */
    public function bind(callable $transform)
    {
        return static::unit(self::concat(array_map($transform, $this->value)));
    }

    /**
     * concat joins an array of arrays into a single array.
     *
     * Concat handles a unit of [Nothing] as a special case, ignoring it.
     *
     * @param $list array An array of arrays.
     * @return array A flattened array.
     */
    public static function concat($list)
    {
        return array_reduce($list, function ($carry, Monad $item) {
            return ($item instanceof Nothing)
              ? $carry
              : array_merge($carry, $item->unpack());
        }, []);
    }

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
                ? new static([$element[$key]])
                : new Nothing;
            }
            if (is_object($element)) {
                return property_exists($element, $key)
                ? new static([$element->{$key}])
                : new Nothing;
            }
            return new Nothing;
        };
    }
}
