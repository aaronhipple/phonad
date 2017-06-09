<?php
use PHPUnit\Framework\TestCase;
use aaronhipple\phonad\Option;
use aaronhipple\phonad\Nothing;
use aaronhipple\phonad\Utilities;

class UtilitiesTest extends TestCase
{
    /**
     * @group utilities
     */
    public function testComposeNoFunctionsThrowsError()
    {
        $this->expectException(InvalidArgumentException::class);
        Utilities::compose();
    }

    /**
     * @group utilities
     */
    public function testComposeSingleFunction()
    {
        $transform = Utilities::compose('str_rot13');
        $this->assertEquals(str_rot13('value'), $transform('value'));
    }

    /**
     * @group utilities
     */
    public function testComposeMultipleFunctions()
    {
        $transform = Utilities::compose('str_rot13', 'str_rot13');
        $this->assertEquals('value', $transform('value'));
    }

    /**
     * @group utilities
     */
    public function testMaybeUnpackWorksOnMonad()
    {
        $value = new Option(3);
        $result = Utilities::maybeUnpack($value);
        $this->assertEquals(3, $result);
    }

    /**
     * @group utilities
     */
    public function testMaybeUnpackWorksOnNonMonad()
    {
        $value = 3;
        $result = Utilities::maybeUnpack($value);
        $this->assertEquals(3, $result);
    }
}
