<?php
require __DIR__ . '/vendor/autoload.php';

use Phonad\ListMonad as L;
use Phonad\Nothing;
use Phonad\Option;

$books = [
  [
    'title' => 'foo',
    'author' => [
      'name' => [
        'first' => 'Bob',
        'last' => 'Johnson',
      ],
    ],
  ],
  [
    'title' => 'bar',
    'author' => [
      'name' => [
        'first' => 'John',
        'middle' => 'Jay',
        'last' => 'Branson',
      ],
    ],
  ],
  [
    'title' => 'baz'
  ],
  [
    'title' => 'biz',
    'author' => [
      'name' => [
        'first' => 'Ron'
      ],
    ],
  ],
];

$bookMonad = new L(...$books);

$names = $bookMonad
  ->at('author')
  ->at('name')
  ->at('middle')
  ->unpack();

var_dump($names);



$replicate = function ($times) {
    return function ($value) use ($times) {
        return new L(...array_fill(0, $times, $value));
    };
};

$list = new L(['bunny']);

$result = $list
  ->bind($replicate(3))
  ->bind($replicate(3))
  ->unpack();

var_dump($result);
