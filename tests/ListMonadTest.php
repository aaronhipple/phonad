<?php
use PHPUnit\Framework\TestCase;
use aaronhipple\phonad\ListMonad;

class ListMonadTest extends TestCase
{
    public function testOperationsChain()
    {
        $value = new ListMonad([1, 2, 3]);
     
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
}
