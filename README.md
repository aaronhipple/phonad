# phonad

[![Build Status](https://travis-ci.org/aaronhipple/phonad.svg?branch=master)](https://travis-ci.org/aaronhipple/phonad)
[![Test Coverage](https://codeclimate.com/github/aaronhipple/phonad/badges/coverage.svg)](https://codeclimate.com/github/aaronhipple/phonad/coverage)
[![Code Climate](https://codeclimate.com/github/aaronhipple/phonad/badges/gpa.svg)](https://codeclimate.com/github/aaronhipple/phonad)

A Monad library for PHP. Sort of.

Maybe they're *PH*P M*onads*. Maybe they're *Ph*ony M*onads*. Maybe it's pronounced
*Faux-nads*. Who cares? It's fun to say. Learn more at [the site](https://aaronhipple.github.io/phonad/).

## example

Let's get some emails from an array of book and author information.

```php
<?php

$books = [
  ['title' => 'Some Book Title', 'author' => ['name' => 'Steve', 'email' => 'steve@example.test']],
  ['title' => 'Another Title', 'author' => ['name' => 'Frank']],
  ['title' => 'A Book Title', 'author' => ['name' => 'Ellen', 'email' => 'ellen@example.test']],
];

$booksList = Collection::unit(...$books);
$emails = $booksList
  ->at('author')
  ->at('email')
  ->unpack();

print_r($emails); // ['steve@example.test', 'ellen@example.test']
```

## caveats

This represents a learning project that may or may not be ready for prime time.
Inspiration is taken from [ircmaxell/monad-php](https://github.com/ircmaxell/monad-php).

## contributing

Pull requests are welcome. New functionality and bug fixes are great, new tests are even better.

## license

MIT.
