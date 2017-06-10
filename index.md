---

---
## PHP Sucks Sometimes

Don't get me wrong, PHP can be a super fun and easy language to work with.
But have you ever run into a situation like this (admittedly contrived)
one from everybody's favorite crusty old PHP CMS?

```php
<?php
# We need to get first names from our CMS data.

$firstNames = [];

foreach ($node->field_people[LANGUAGE_NONE] as $person) {
  $firstNames[] = $person['name']['first'];
}
```

What happens if `$node->field_people` or `$node->field_people[LANGUAGE_NONE]`
are undefined? We get a nasty error message and our script stops! Okay, maybe we can just do a
little check first.

```php
<?php
# Let's handle the field not being set.

$firstNames = [];

if (isset($node->field_people)) {
  if (isset($node->field_people[LANGUAGE_NONE])) {
    foreach ($node->field_people[LANGUAGE_NONE] as $person) {
      $firstNames[] = $person['name']['first'];
    }
  }
}
```

Well, that solves one problem, but all that nesting is already hard to read, and what if
our `$person` variables don't have a `name` key? Or a `first` key inside their
`name` array?  Well, let's test for `name`...

```php
<?php
# Now let's handle the field values maybe not being set.
$firstNames = [];

if (isset($node->field_people)) {
  if (isset($node->field_people[LANGUAGE_NONE])) {
    foreach ($node->field_people[LANGUAGE_NONE] as $person) {
      if (isset($person['name']) && isset($person['name']['first'])) {
        $firstNames[] = $person['name']['first'];
      }
    }
  }
}
```

This is getting out of hand! And what if we decide we only want to get
the first names of a certain class of people?

```php
<?php
# Let's only get user's first names
$userFirstNames = [];

if (isset($node->field_people)) {
  if (isset($node->field_people[LANGUAGE_NONE])) {
    foreach ($node->field_people[LANGUAGE_NONE] as $person) {
      if (isset($person['type'])) {
        if ($person['type'] !== 'user') {
          continue;
        }

        if (isset($person['name']) && isset($person['name']['first'])) {
          $userFirstNames[] = $person['name']['first'];
        }
      }
    }
  }
}
```

*Yikes!* That's an awful mess! We've got our first names, but look at all that code!

What if we could do this instead?

```php
<?php
$isUser = function ($person) {
  return isset($person['type']) && $person['type'] === 'user';
};

$users = new Phonad\Collection($node)
  ->at('field_people')
  ->at(LANGUAGE_NONE)
  ->where($isUser);

$userFirstNames = $users
  ->at('name')
  ->at('first')
  ->unpack();

// Let's get last names too, since it's so easy!
$userLastNames = $users
  ->at('name')
  ->at('last')
  ->unpack();
```

Welcome to Phonad. A monad library for PHP. Sort of.

## Phonads and Monads

Maybe they're *PH*P M*onads*. Maybe they're *Ph*ony M*onads*. Maybe it's pronounced
*Faux-nads*. Who cares? It's fun to say.

What are monads anyway? They're a construct from functional programming
that allows a handy, readable syntax for various chained operations in
those languages. For our purposes, they're just containers that let us chain
operations together. A monad has three core pieces.

- A monadic type (something like `new Option(3)` in our example)
- A `unit` or `return` operation (we use `unit` because `return` already has
  meaning in PHP) that encapsulates a value in the above monadic type.
- A `bind` operation that takes a function, applies it to the encapsulated
  value, and returns another encapsulated value (ready for the next `bind` operation).

The neat thing about these things is that you can define families of them that behave in
different ways. Take the `Option` monad (sometimes called the `Maybe` monad).

```php
<?php

$add = function ($x) {
  return function ($y) use ($x) {
    return $x + $y;
  };
}

$divideBy = function ($x) {
  return function ($y) use ($x) {
    if ($y === 0) { // Dividing by zero is bad news!
      return null;  // Let's return a null value if it happens.
    }
    return $x / $y;
  };
}

$value = Phonad\Option(5);
$newValue = $value
  ->bind($add(1))
  ->bind($divideBy(0)) // What happens here? The bind function
  ->bind($add(2))      // returns a Nothing instead of an Option
  ->bind($add(3))      // and Nothing always binds to itself,
  ->unpack();          // so subsequent operations don't execute.

var_dump($newValue);   // null
```

Well, that's kind of neat. And that's just a simple example of how monads can be used. You can
encapsulate just about anything into a Phonad, and you can `bind` just about any function.

## Go Forth and Phonad!

So take 'em, have fun with 'em, and tell me when they break! *Phonad* is still fairly young, so
maybe don't use them in production just yet unless you're willing to make some adjustments. If
you do make adjustments, send a pull request!
