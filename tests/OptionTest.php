<?php
use PHPUnit\Framework\TestCase;
use aaronhipple\phonad\Option;

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
            return null;
        })
        ->bind(function ($x) {
            return $x + 3;
        })
            ->unpack();
        
        $this->assertNull($result);
    }
}
