<?php
use PHPUnit\Framework\TestCase;
use aaronhipple\phonad\ListMonad as L;
use aaronhipple\phonad\Nothing;

class ListMonadTest extends TestCase
{
    /**
     * @group monad
     */
    public function testConstructsAndUnpacks() {
        $monad = new L('one', 'two', 'three');
        $result = $monad->unpack();
        $this->assertEquals(['one', 'two', 'three'], $result);
    }

    /**
     * @group monad
     */
    public function testOperationsChain()
    {
        $value = new L(1, 2, 3);
     
        $result = $value
            ->bind(function ($x) {
                return new L($x - 1);
            })
            ->bind(function ($x) {
                return new L($x * 2);
            })
            ->unpack();
        
        $this->assertEquals([0, 2, 4], $result);
    }

    /**
     * @group operation
     * @group concat
     */
    public function testConcatOnArrays() {
        $arrays = [
          new L('one'),
          new L('two'),
          new L('three'),
        ];
        $result = L::concat($arrays);
        $this->assertEquals(['one', 'two', 'three'], $result);
    }

    /**
     * @group operation
     * @group concat
     */
    public function testConcatHandlesNone() {
        $arrays = [
          new L('one'),
          new L('two'),
          new Nothing,
          new L('three'),
        ];
        $result = L::concat($arrays);
        $this->assertEquals(['one', 'two', 'three'], $result);
    }

    /**
     * @group operation
     * @group at
     */
    public function testAtHandlesArrays() {
        $books = [
          ['title' => 'War and Peace', 'author' => ['name' => 'Steve', 'email' => 'steve@example.test']],
          ['title' => 'Another Title', 'author' => ['name' => 'Frank']],
          ['title' => 'A Book Title.', 'author' => ['name' => 'Ellen', 'email' => 'ellen@example.test']],
        ];

        $booksList = L::unit(...$books);
        $emails = $booksList
          ->bind(L::at('author'))
          ->bind(L::at('email'))
          ->unpack();

        $this->assertEquals(['steve@example.test', 'ellen@example.test'], $emails);
    }

    /**
     * @group operation
     * @group at
     */
    public function testAtHandlesObjects() {
        $books = [
          (object)['title' => 'War and Peace', 'author' => ['name' => 'Steve', 'email' => 'steve@example.test']],
          (object)['title' => 'Another Title', 'author' => ['name' => 'Frank']],
          (object)['title' => 'A Book Title.', 'author' => ['name' => 'Ellen', 'email' => 'ellen@example.test']],
        ];

        $booksList = L::unit(...$books);
        $emails = $booksList
          ->bind(L::at('author'))
          ->bind(L::at('email'))
          ->unpack();

        $this->assertEquals(['steve@example.test', 'ellen@example.test'], $emails);
    }

    /**
     * @group operation
     * @group at
     */
    public function testAtFailsGracefully() {
        $books = [
          ['title' => 'War and Peace', 'author' => ['name' => 'Steve', 'email' => 'steve@example.test']],
          ['title' => 'Another Title', 'author' => ['name' => 'Frank']],
          ['title' => 'A Book Title.', 'author' => ['name' => 'Ellen', 'email' => 'ellen@example.test']],
        ];

        $booksList = L::unit(...$books);
        $emails = $booksList
          ->bind(L::at('author'))
          ->bind(L::at('name'))
          ->bind(L::at('first'))
          ->unpack();

        $this->assertEquals([], $emails);
    }
}
