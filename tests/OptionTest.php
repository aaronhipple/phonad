<?php
use PHPUnit\Framework\TestCase;
use Phonad\Option;
use Phonad\Nothing;

class OptionTest extends TestCase
{
    /**
     * @group monad
     */
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

    /**
     * @group operation
     * @group at
     */
    public function testAtHandlesArrays()
    {
        $book = ['title' => 'War and Peace', 'author' => ['name' => 'Steve', 'email' => 'steve@example.test']];

        $bookOption = Option::unit($book);
        $email = $bookOption
          ->at('author')
          ->at('email')
          ->unpack();

        $this->assertEquals('steve@example.test', $email);
    }

    /**
     * @group operation
     * @group at
     */
    public function testAtFallsThroughArrays()
    {
        $book = ['title' => 'War and Peace', 'author' => ['name' => 'Steve', 'email' => 'steve@example.test']];

        $bookOption = Option::unit($book);
        $firstName = $bookOption
          ->at('author')
          ->at('name')
          ->at('first')
          ->unpack();

        $this->assertNull($firstName);
    }

    /**
     * @group operation
     * @group at
     */
    public function testAtHandlesObjects()
    {
        $book = (object)['title' => 'War and Peace', 'author' => ['name' => 'Steve', 'email' => 'steve@example.test']];

        $bookOption = Option::unit($book);
        $email = $bookOption
          ->at('author')
          ->at('email')
          ->unpack();

        $this->assertEquals('steve@example.test', $email);
    }

    /**
     * @group operation
     * @group at
     */
    public function testAtFallsThroughObjects()
    {
        $book = (object)['title' => 'War and Peace', 'author' => ['name' => 'Steve', 'email' => 'steve@example.test']];

        $bookOption = Option::unit($book);
        $firstName = $bookOption
          ->at('author')
          ->at('name')
          ->at('first')
          ->unpack();

        $this->assertNull($firstName);
    }
}
