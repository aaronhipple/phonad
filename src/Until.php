<?php namespace aaronhipple\phonad;

/**
 * Until permits the chaining of operations that may or may
 * not return a value. If a value is returned, subsequent operations fall
 * through without execution. This permits several attempts at obtaining a value.
 */
class Until extends Monad
{
    use Traits\Traversable;

    /**
     * Represent Until::unit as a const containing a callable such
     * that it may be easily passed as a callback.
     */
    public static $unit = 'aaronhipple\phonad\Until::unit';

    /**
     * Apply a transformation to the monad.
     *
     * @param callable $transform
     * @return Until|Something A transformed instance of the monad.
     */
    public function bind(callable $transform)
    {
        $result = $transform($this->value);

        if ($result instanceof Nothing || is_null($result)) {
            return new Until($this->value);
        }
        return Something::unit($result);
    }

    /**
     * Return nothing if the 'Until' monad hasn't been fulfilled.
     *
     * @return null
     */
    public function unpack()
    {
        return null;
    }
}
