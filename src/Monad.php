<?php namespace Phonad;

/**
 * Monad allows the enclosure of a value or values
 * such that operations may be safely and easily chained
 * upon them. Different monads implement different structures,
 * but they all implement 'unit' (sometimes called 'return'), 'bind',
 * and are themselves representations of a 'Monadic type'.
 */
abstract class Monad
{
    use Traits\Arithmetic;

    /**
     * Our monad's wrapped value.
     *
     * @var mixed
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
     *
     * @param $name string The called method name.
     * @param $arguments array An array of function arguments.
     * @return Monad
     * @throws Phonad\Exceptions\MethodNotFoundException
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this, $name)) {
            return $this->bind(static::$name(...$arguments));
        }
        throw new Exceptions\MethodNotFoundException(sprintf('Call to undefined method %s::%s()', __CLASS__, $name));
    }

    /**
     * A factory method for monads.
     *
     * @param $value mixed The value(s) being encapsulated.
     * @return Monad
     */
    public static function unit(...$value)
    {
        return new static(...$value);
    }

    /**
     * Apply a transformation to the monad.
     *
     * @param callable $transform
     * @return Monad
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
