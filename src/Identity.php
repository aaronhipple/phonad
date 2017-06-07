<?php namespace aaronhipple\phonad;

/**
 * Identity permits the chaining of operations with no additional
 * special behavior. It carries no information other than its value.
 *
 * Example usage:
 *   use aaronhipple\phonad\Identity;
 *   use PHPUnit\Framework\Assert;
 *
 *   $value = new Identity(3);
 *
 *   $result = $value
 *     ->bind(function ($x) { return $x + 3; })
 *     ->bind(function ($x) { return $x + 3; })
 *     ->unpack();
 *
 *   Assert::assertEquals(9, $result);
 */
class Identity extends Monad
{
    /**
     * Represent Identity::unit as a const containing a callable such
     * that it may be easily passed as a callback.
     */
    const unit = 'aaronhipple\phonad\Identity::unit';

    /**
     * Apply a transformation to the monad.
     *
     * @param callable $transform
     * @return Option|null A transformed instance of the monad (or null).
     */
    public function bind(callable $transform)
    {
        return static::unit($transform($this->value));
    }
}
