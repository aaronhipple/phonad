<?php namespace Phonad;

use InvalidArgumentException;

/**
 * Collection permits the chaining of operations on a list of items.
 *
 * Example usage:
 *
 * ```php
 *   use Phonad\Collection;
 *   use PHPUnit\Framework\Assert;
 *
 *   $value = new Collection(1, 2, 3);
 *
 *   $result = $value
 *     ->bind(function ($x) { return $x - 1; })
 *     ->bind(function ($x) { return $x * 2; })
 *     ->unpack();
 *
 *   Assert::assertEquals([0, 2, 4], $result);
 * ```
 */
class Collection extends Monad
{
    use Traits\Traversable;
    use Traits\Collection;

    /**
     * Represent Collection::unit as a const containing a callable such
     * that it may be easily passed as a callback.
     */
    public static $unit = 'Phonad\Collection::unit';

    /**
     * Collection constructor.
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
     * @return Monad A transformed monad of the monad.
     */
    public function bind(callable $transform)
    {
        $results = self::concat(array_map(static::$unit, array_map($transform, $this->value)));
        return static::unit(...$results);
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
