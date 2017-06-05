<?php
require __DIR__ . '/vendor/autoload.php';

use aaronhipple\phonad;

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

function valueAt($key)
{
    return function ($array) use ($key) {
        return isset($array[$key]) ? $array[$key] : null;
    };
}

$bookMonad = phonad\ListMonad::unit($books);

$names = $bookMonad
  ->bind(valueAt('author'))
  ->bind(valueAt('name'))
  ->bind(valueAt('middle'))
  ->unpack();

var_dump($names);
