<?php
use PHPUnit\Framework\TestCase;
use aaronhipple\phonad\Option;
use aaronhipple\phonad\Nothing;

class OptionTest extends TestCase
{
    public function testOperationsChain()
    {
        $value = new Option(3);
     
        $result = $value
        ->bind(function ($x) {
            return $x + 3;
        })
        ->bind(function ($x) {
            return $x + 3;
        })
            ->unpack();
        
        $this->assertEquals(9, $result);
    }
    
    public function testAtHandlesArrays() {
        $book = ['title' => 'War and Peace', 'author' => ['name' => 'Steve', 'email' => 'steve@example.test']];

        $bookOption = Option::unit($book);
        $email = $bookOption
          ->bind(Option::at('author'))
          ->bind(Option::at('email'))
          ->unpack();

        $this->assertEquals('steve@example.test', $email);
    }

    public function testAtFallsThroughArrays() {
        $book = ['title' => 'War and Peace', 'author' => ['name' => 'Steve', 'email' => 'steve@example.test']];

        $bookOption = Option::unit($book);
        $firstName = $bookOption
          ->bind(Option::at('author'))
          ->bind(Option::at('name'))
          ->bind(Option::at('first'))
          ->unpack();

        $this->assertNull($firstName);
    }

    public function testAtHandlesObjects() {
        $book = (object)['title' => 'War and Peace', 'author' => ['name' => 'Steve', 'email' => 'steve@example.test']];

        $bookOption = Option::unit($book);
        $email = $bookOption
          ->bind(Option::at('author'))
          ->bind(Option::at('email'))
          ->unpack();

        $this->assertEquals('steve@example.test', $email);
    }

    public function testAtFallsThroughObjects() {
        $book = (object)['title' => 'War and Peace', 'author' => ['name' => 'Steve', 'email' => 'steve@example.test']];

        $bookOption = Option::unit($book);
        $firstName = $bookOption
          ->bind(Option::at('author'))
          ->bind(Option::at('name'))
          ->bind(Option::at('first'))
          ->unpack();

        $this->assertNull($firstName);
    }
}
