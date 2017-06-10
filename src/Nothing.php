<?php namespace Phonad;

/**
 * Nothing represents an empty value in the Option monad.
 */
class Nothing extends Option
{
    protected $value = null;

    /**
     * Represent Nothing::unit as a const containing a callable such
     * that it may be easily passed as a callback.
     */
    public static $unit = 'Phonad\Nothing::unit';


    /**
     * Nothing constructor.
     *
     * Does nothing.
     */
    public function __construct()
    {
    }

    /**
     * Apply a transformation to the monad.
     *
     * @param callable $transform
     * @return Nothing
     */
    public function bind(callable $transform)
    {
        return $this;
    }
}
