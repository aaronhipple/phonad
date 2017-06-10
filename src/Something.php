<?php namespace aaronhipple\phonad;

use Closure;

/**
 * Something represents an fulfilled value in the Until monad.
 */
class Something extends Until
{
    protected $value;

    /**
     * Represent Something::unit as a const containing a callable such
     * that it may be easily passed as a callback.
     */
    public static $unit = 'aaronhipple\phonad\Something::unit';

    /**
     * Apply a transformation to the monad.
     *
     * @param callable $transform
     * @return Something
     */
    public function bind(callable $transform)
    {
        return $this;
    }

    /**
     * Retrieve the encapsulated value from the monad.
     *
     * @return mixed
     */
    public function unpack()
    {
        return $this->value;
    }
}