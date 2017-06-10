<?php namespace Phonad\Traits;

/**
 * Arithmetic provides operations that can be used .
 */
trait Arithmetic
{
    /**
     * add returns a callback for addition.
     *
     * @param $x number
     */
    protected static function add($x)
    {
        /**
         * @param $y number
         */
        return function ($y) use ($x) {
            return $x + $y;
        };
    }

    /**
     * subtractFrom returns a callback for subtraction.
     *
     * @param $x number
     */
    protected static function subtractFrom($x)
    {
        /**
         * @param $y number
         */
        return function ($y) use ($x) {
            return $x - $y;
        };
    }

    /**
     * subtract returns a callback for subtraction.
     *
     * @param $y number
     */
    protected static function subtract($y)
    {
        /**
         * @param $x number
         */
        return function ($x) use ($y) {
            return static::subtractFrom($x)($y);
        };
    }

    /**
     * multiply returns a callback for multiplication.
     *
     * @param $x number
     */
    protected static function multiply($x)
    {
        /**
         * @param $y number
         */
        return function ($y) use ($x) {
            return $x * $y;
        };
    }

    /**
     * divide returns a callback for division.
     *
     * @param $x number
     */
    protected static function divide($x)
    {
        /**
         * @param $y number
         */
        return function ($y) use ($x) {
            return $y !== 0
                ? $x / $y
                : null;
        };
    }

    /**
     * divideBy returns a callback for division.
     *
     * @param $y number
     */
    protected static function divideBy($y)
    {
        /**
         * @param $x number
         */
        return function ($x) use ($y) {
            return static::divide($x)($y);
        };
    }
}
