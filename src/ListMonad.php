<?php namespace aaronhipple\phonad;

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
 *     ->bind(function ($x) { return $x - 1; })
 *     ->bind(function ($x) { return $x * 2; })
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
        $this->value = is_array($value) ? $value : [$value];
    }

    /**
     * Apply a transformation to each item of the monad.
     *
     * @param callable $transform
     * @return ListMonad A transformed instance of the monad.
     */
    public function bind(callable $transform)
    {
        return static::unit(array_map(Utilities::maybeBind($transform), $this->value));
    }

    /**
     * Retrieve the encapsulated values from the monad.
     *
     * @return mixed
     */
    public function unpack()
    {
        return array_map(Utilities::maybeUnpack(), $this->value);
    }
}
