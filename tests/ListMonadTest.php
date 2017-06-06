<?php
use PHPUnit\Framework\TestCase;
use aaronhipple\phonad\ListMonad as L;
use aaronhipple\phonad\Nothing;

class ListMonadTest extends TestCase
{
    public function testConstructsCorrectly() {
        $result = new L(['valid value']);
        $this->assertInstanceOf('aaronhipple\phonad\ListMonad', $result);
    }

    public function testThrowsExceptionOnInvalidValue() {
        $this->expectException(InvalidArgumentException::class);
        new L('invalid value');
    }

    public function testOperationsChain()
    {
        $value = new L([1, 2, 3]);
     
        $result = $value
            ->bind(function ($x) {
                return new L([$x - 1]);
            })
            ->bind(function ($x) {
                return new L([$x * 2]);
            })
            ->unpack();
        
        $this->assertEquals([0, 2, 4], $result);
    }

    public function testConcatOnArrays() {
        $arrays = [
          new L(['one']),
          new L(['two']),
          new L(['three']),
        ];
        $result = L::concat($arrays);
        $this->assertEquals(['one', 'two', 'three'], $result);
    }

    public function testConcatHandlesNone() {
        $arrays = [
          new L(['one']),
          new L(['two']),
          new Nothing,
          new L(['three']),
        ];
        $result = L::concat($arrays);
        $this->assertEquals(['one', 'two', 'three'], $result);
    }

    public function testAtHandlesArrays() {
        $books = [
          ['title' => 'War and Peace', 'author' => ['name' => 'Steve', 'email' => 'steve@example.test']],
          ['title' => 'Another Title', 'author' => ['name' => 'Frank']],
          ['title' => 'A Book Title.', 'author' => ['name' => 'Ellen', 'email' => 'ellen@example.test']],
        ];

        $booksList = L::unit($books);
        $emails = $booksList
          ->bind(L::at('author'))
          ->bind(L::at('email'))
          ->unpack();

        $this->assertEquals(['steve@example.test', 'ellen@example.test'], $emails);
    }

    public function testAtHandlesObjects() {
        $books = [
          (object)['title' => 'War and Peace', 'author' => ['name' => 'Steve', 'email' => 'steve@example.test']],
          (object)['title' => 'Another Title', 'author' => ['name' => 'Frank']],
          (object)['title' => 'A Book Title.', 'author' => ['name' => 'Ellen', 'email' => 'ellen@example.test']],
        ];

        $booksList = L::unit($books);
        $emails = $booksList
          ->bind(L::at('author'))
          ->bind(L::at('email'))
          ->unpack();

        $this->assertEquals(['steve@example.test', 'ellen@example.test'], $emails);
    }

    public function testAtFailsGracefully() {
        $books = [
          ['title' => 'War and Peace', 'author' => ['name' => 'Steve', 'email' => 'steve@example.test']],
          ['title' => 'Another Title', 'author' => ['name' => 'Frank']],
          ['title' => 'A Book Title.', 'author' => ['name' => 'Ellen', 'email' => 'ellen@example.test']],
        ];

        $booksList = L::unit($books);
        $emails = $booksList
          ->bind(L::at('author'))
          ->bind(L::at('name'))
          ->bind(L::at('first'))
          ->unpack();

        $this->assertEquals([], $emails);
    }
}
