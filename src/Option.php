<?php namespace Phonad;

/**
 * Option permits the chaining of operations that may or may
 * not return a value. If no value is returned, subsequent operations
 * fall through without execution.
 *
 * Example:
 * ```php
 * use Phonad\Option;
 * use PHPUnit\Framework\Assert;
 *
 * $value = new Option(3);
 *
 * $result = $value
 *   ->bind(function ($x) { return $x + 3; })
 *   ->bind(function ($x) { return null; })
 *   ->bind(function ($x) { return $x + 3; })
 *   ->unpack();
 *
 * Assert::assertNull($result); // Value should be null.
 * ```
 */
class Option extends Monad
{
    use Traits\Traversable;

    /**
     * Represent Option::unit as a const containing a callable such
     * that it may be easily passed as a callback.
     */
    public static $unit = 'Phonad\Option::unit';

    /**
     * Apply a transformation to the monad.
     *
     * @param callable $transform
     * @return Option|Nothing|Monad
     */
    public function bind(callable $transform)
    {
        $result = $transform($this->value);
        return ($result instanceof Option)
          ? $result
          : static::unit($result);
    }
}
