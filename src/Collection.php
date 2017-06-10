<?php namespace Phonad;

use InvalidArgumentException;

/**
 * Collection permits the chaining of operations on a list of items.
 * Bound functions are applied to each item and the results concatenated.
 *
 * Example:
 * ```php
 * use Phonad\Collection;
 * use PHPUnit\Framework\Assert;
 *
 * $value = new Collection(1, 2, 3);
 *
 * $result = $value
 *   ->bind(function ($x) { return $x - 1; })
 *   ->bind(function ($x) { return $x * 2; })
 *   ->bind(function ($x) { return [$x, $x + 1]; })
 *   ->unpack();
 *
 * Assert::assertEquals([0, 1, 2, 3, 4, 5], $result);
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
     * @param $value mixed
     */
    public function __construct(...$items)
    {
        $this->value = $items;
    }

    /**
     * Apply a transformation to each item of the monad.
     *
     * @param callable $transform
     * @return Collection|Monad
     */
    public function bind(callable $transform)
    {
        $results = self::concat(array_map(static::$unit, array_map($transform, $this->value)));
        return static::unit(...$results);
    }

}
