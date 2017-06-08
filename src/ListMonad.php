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
    use Traits\Traversable;

    /**
     * Represent ListMonad::unit as a const containing a callable such
     * that it may be easily passed as a callback.
     */
    public static $unit = 'aaronhipple\phonad\ListMonad::unit';

    /**
     * ListMonad constructor.
     *
     * @param $value mixed The value to be contained. If not an array, an array will be constructed.
     */
    public function __construct(...$items)
    {
        $this->value = $items;
    }

    /**
     * Apply a transformation to each item of the monad.
     *
     * @param callable $transform
     * @return ListMonad A transformed instance of the monad.
     */
    public function bind(callable $transform)
    {
        $results = self::concat(array_map($transform, $this->value));
        return new static(...$results);
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

}
