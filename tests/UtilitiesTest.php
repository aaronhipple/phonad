<?php
use PHPUnit\Framework\TestCase;
use aaronhipple\phonad\Option;
use aaronhipple\phonad\Nothing;
use aaronhipple\phonad\Utilities;

class UtilitiesTest extends TestCase
{
    public function testComposeNoFunctionsThrowsError()
    {
        $this->expectException(InvalidArgumentException::class);
        Utilities::compose();
    }

    public function testComposeSingleFunction()
    {
        $transform = Utilities::compose('str_rot13');
        $this->assertEquals(str_rot13('value'), $transform('value'));
    }

    public function testComposeMultipleFunctions()
    {
        $transform = Utilities::compose('str_rot13', 'str_rot13');
        $this->assertEquals('value', $transform('value'));
    }

    public function testMaybeUnpackWorksOnMonad()
    {
        $value = new Option(3);
        $result = Utilities::maybeUnpack()($value);
        $this->assertEquals(3, $result);
    }

    public function testMaybeUnpackWorksOnNonMonad()
    {
        $value = 3;
        $result = Utilities::maybeUnpack()($value);
        $this->assertEquals(3, $result);
    }
}
