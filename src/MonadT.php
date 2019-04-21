<?php namespace Phonad;

abstract class MonadT {
    abstract public static function unit(...$monads);
    abstract public function bind(callable $transform);
    abstract public static function lift(Monad $monad);
    abstract public function unpack();
}
