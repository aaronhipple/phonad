<?php
use PHPUnit\Framework\TestCase;
use Phonad\Option;
use Phonad\Nothing;

class NothingTest extends TestCase
{
    /**
     * @group monad
     */
    public function testOperationsShortCircuitOnNothing()
    {
        $value = new Option(3);
     
        $result = $value
        ->bind(function ($x) {
            return $x + 3;
        })
        ->bind(function ($x) {
            return null;
        })
        ->bind(function ($x) {
            return $x + 3;
        })
            ->unpack();
        
        $this->assertNull($result);
    }
}
