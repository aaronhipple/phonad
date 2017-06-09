<?php namespace aaronhipple\phonad;

/**
 * Monad allows the enclosure of a value or values
 * such that operations may be safely and easily chained
 * upon them. Different monads implement different structures,
 * but they all implement 'unit' (sometimes called 'return'), 'bind',
 * and are themselves representations of a 'Monadic type'.
 */
abstract class Monad
{
    /**
     * Our monad's wrapped value.
     */
    protected $value;

    /**
     * Monad constructor
     *
     * @param $value mixed
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Calls to things that are private static methods pass through to retrieve a callback.
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this, $name)) {
            return $this->bind(static::$name(...$arguments));
        }
        throw new \Error(sprintf('Call to undefined method %s::%s()', __CLASS__, $name));
    }

    /**
     * A factory method for monads.
     *
     * @return Monad An instance of monad.
     */
    public static function unit(...$value)
    {
        if (count($value) === 1 && current($value) instanceof Monad) {
            return current($value);
        }
        return new static(...$value);
    }

    /**
     * Apply a transformation to the monad.
     *
     * @param callable $transform
     * @return Monad A transformed instance of the monad.
     */
    abstract public function bind(callable $transform);

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
