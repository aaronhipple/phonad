<?php namespace aaronhipple\phonad;

/**
 * Option permits the chaining of operations that may or may
 * not return a value. If no value is returned, subsequent operations
 * fall through without execution. This has the effect of squashing errors.
 *
 * Example usage:
 *   use aaronhipple\phonad\Option;
 *   use aaronhipple\phonad\Nothing;
 *   use PHPUnit\Framework\Assert;
 *
 *   $value = new Option(3);
 *
 *   $result = $value
 *     ->bind(function ($x) { return $x + 3; })
 *     ->bind(function ($x) { return new Nothing; })
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
    const unit = 'aaronhipple\phonad\Option::unit';

    /**
     * Apply a transformation to the monad.
     *
     * @param callable $transform
     * @return Option|Nothing A transformed instance of the monad.
     */
    public function bind(callable $transform)
    {
        $result = $transform($this->value);
        return ($result instanceof Option)
          ? $result
          : static::unit($result);
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
                ? new static($element[$key])
                : new Nothing;
            }
            if (is_object($element)) {
                return property_exists($element, $key)
                ? new static($element->{$key})
                : new Nothing;
            }
            return new Nothing;
        };
    }
}
