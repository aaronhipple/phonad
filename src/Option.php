<?php namespace aaronhipple\phonad;

/**
 * Option permits the chaining of operations that may or may
 * not return a value. If no value (represented here as PHP's `null`)
 * is returned, subsequent operations fall through without execution.
 *
 * Example usage:
 *   use aaronhipple\phonad\Option;
 *   use PHPUnit\Framework\Assert;
 *
 *   $value = new Option(3);
 *
 *   $result = $value
 *     ->bind(function ($x) { return $x + 3; })
 *     ->bind(function ($x) { return NULL; })
 *     ->bind(function ($x) { return $x + 3; })
 *     ->unpack();
 *
 *   Assert::assertNull($result); // Value should be null.
 */
class Option extends Monad
{
    /**
     * Represent Option::unit as a const containing a callable such
     * that it may be easily passed as a callback.
     */
    public const unit = 'aaronhipple\phonad\Option::unit';

    /**
     * Apply a transformation to the monad.
     *
     * @param callable $transform
     * @return Option|null A transformed instance of the monad (or null).
     */
    public function bind(callable $transform)
    {
        $isNothing = 'is_null';
        if ($isNothing($this->value)) {
            return static::unit(null);
        }
        return parent::bind($transform);
    }
}
