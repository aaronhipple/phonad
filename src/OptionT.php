<?php namespace Phonad;

class OptionT extends MonadT {
    public function __construct(Monad $monad) {
        $this->monad = $monad;
    }

    public static function unit(...$monads) {
        return new static(...$monads);
    }

    public function bind(callable $transform) {
        $result = $this->monad->bind($transform);
        if (is_null($result->unpack())) {
            return new Nothing;
        }
        return static::unit($result);
    }

    public static function lift(Monad $monad) {
        // Doesn't seem to mean much in PHP.
    }

    public function unpack()
    {
        return $this->monad->unpack();
    }
}
