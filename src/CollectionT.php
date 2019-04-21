<?php namespace Phonad;

class CollectionT extends MonadT {
    public function __construct(...$monads) {
        $this->monads = $monads;
    }

    public static function unit(...$monads) {
        return new static(...$monads);
    }

    public function bind(callable $transform)
    {
        $results = array_map(function ($monad) use ($transform) {
            return $monad->bind($transform);
        }, $this->monads);
        return static::unit(...$results);
    }

    public static function lift(Monad $monad)
    {
        // Doesn't seem to mean much in PHP.
    }

    public function unpack()
    {
        return array_map(function ($monad) {
            return $monad->unpack();
        }, $this->monads);
    }
}
