<?php
use PHPUnit\Framework\TestCase;
use Phonad\Collection;
use Phonad\Nothing;

class CollectionTest extends TestCase
{
    /**
     * @group monad
     */
    public function testConstructsAndUnpacks()
    {
        $monad = new Collection('one', 'two', 'three');
        $result = $monad->unpack();
        $this->assertEquals(['one', 'two', 'three'], $result);
    }

    /**
     * @group monad
     */
    public function testOperationsChain()
    {
        $value = new Collection(1, 2, 3);
     
        $result = $value
            ->bind(function ($x) {
                return $x - 1;
            })
            ->bind(function ($x) {
                return $x * 2;
            })
            ->unpack();
        
        $this->assertEquals([0, 2, 4], $result);
    }

    /**
     * @group operation
     * @group at
     */
    public function testAtHandlesArrays()
    {
        $books = [
          ['title' => 'War and Peace', 'author' => ['name' => 'Steve', 'email' => 'steve@example.test']],
          ['title' => 'Another Title', 'author' => ['name' => 'Frank']],
          ['title' => 'A Book Title.', 'author' => ['name' => 'Ellen', 'email' => 'ellen@example.test']],
        ];

        $booksList = Collection::unit(...$books);
        $emails = $booksList
          ->at('author')
          ->at('email')
          ->unpack();

        $this->assertEquals(['steve@example.test', null, 'ellen@example.test'], $emails);
    }

    /**
     * @group operation
     * @group at
     */
    public function testAtHandlesObjects()
    {
        $books = [
          (object)['title' => 'War and Peace', 'author' => ['name' => 'Steve', 'email' => 'steve@example.test']],
          (object)['title' => 'Another Title', 'author' => ['name' => 'Frank']],
          (object)['title' => 'A Book Title.', 'author' => ['name' => 'Ellen', 'email' => 'ellen@example.test']],
        ];

        $booksList = Collection::unit(...$books);
        $emails = $booksList
          ->at('author')
          ->at('email')
          ->unpack();

        $this->assertEquals(['steve@example.test', null, 'ellen@example.test'], $emails);
    }

    /**
     * @group operation
     * @group at
     */
    public function testAtFailsGracefully()
    {
        $books = [
          ['title' => 'War and Peace', 'author' => ['name' => 'Steve', 'email' => 'steve@example.test']],
          ['title' => 'Another Title', 'author' => ['name' => 'Frank']],
          ['title' => 'A Book Title.', 'author' => ['name' => 'Ellen', 'email' => 'ellen@example.test']],
        ];

        $booksList = Collection::unit(...$books);
        $emails = $booksList
          ->at('author')
          ->at('name')
          ->at('first')
          ->unpack();

        $this->assertEquals([null, null, null], $emails);
    }

    /**
     * @group operation
     * @group where
     */
    public function testWhereFiltersElements()
    {
        $books = [
          ['title' => 'War and Peace', 'author' => ['name' => 'Steve', 'email' => 'steve@example.test']],
          ['title' => 'Another Title', 'author' => ['name' => 'Frank']],
          ['title' => 'A Book Title.', 'author' => ['name' => 'Ellen', 'email' => 'ellen@example.test']],
        ];

        $booksList = Collection::unit(...$books);
        $theRightBook = $booksList
          ->where(function ($element) {
              return $element['title'] === 'Another Title';
          })
          ->unpack();

        $this->assertEquals(
            [
                null,
                $books[1],
                null,
            ],
            $theRightBook
        );
    }
}
