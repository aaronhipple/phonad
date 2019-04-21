<?php
use PHPUnit\Framework\TestCase;
use Phonad\Identity;
use Phonad\Nothing;
use Phonad\Exceptions\MethodNotFoundException;

class IdentityTest extends TestCase
{
    /**
     * @group monad
     */
    public function testOperationsChain()
    {
        $value = Identity::unit(3);
     
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
     * @group monad
     */
    public function testCallFailsOnUndefinedMethod()
    {
        $this->expectException(MethodNotFoundException::class);
        $value = Identity::unit(3);
        $value->foo(3);
    }

    /**
     * @group operations
     * @group arithmetic
     */
    public function testAdd()
    {
        $number = Identity::unit(3);
        $result = $number
            ->add(3)
            ->unpack();
        $this->assertEquals(6, $result);
    }

    /**
     * @group operations
     * @group arithmetic
     */
    public function testSubtractFrom()
    {
        $number = Identity::unit(3);
        $result = $number
            ->subtractFrom(9)
            ->unpack();
        $this->assertEquals(6, $result);
    }

    /**
     * @group operations
     * @group arithmetic
     */
    public function testSubtract()
    {
        $number = Identity::unit(3);
        $result = $number
            ->subtract(9)
            ->unpack();
        $this->assertEquals(-6, $result);
    }

    /**
     * @group operations
     * @group arithmetic
     */
    public function testMultiply()
    {
        $number = Identity::unit(3);
        $result = $number
            ->multiply(3)
            ->unpack();
        $this->assertEquals(9, $result);
    }

    /**
     * @group operations
     * @group arithmetic
     */
    public function testDivide()
    {
        $number = Identity::unit(3);
        $result = $number
            ->divide(6)
            ->unpack();
        $this->assertEquals(2, $result);
    }

    /**
     * @group operations
     * @group arithmetic
     */
    public function testDivideBy()
    {
        $number = Identity::unit(3);
        $result = $number
            ->divideBy(2)
            ->unpack();
        $this->assertEquals(1.5, $result);
    }

    /**
     * @group operations
     * @group arithmetic
     */
    public function testDivideByZero()
    {
        $number = Identity::unit(3);
        $result = $number
            ->divideBy(0)
            ->unpack();
        $this->assertNull($result);
    }

    /**
     * @group operations
     * @group arithmetic
     */
    public function testModulo()
    {
        $number = Identity::unit(5);
        $result = $number
            ->modulo(3)
            ->unpack();
        $this->assertEquals(2, $result);
    }

    /**
     * @group operations
     * @group arithmetic
     */
    public function testModuloByZero()
    {
        $number = Identity::unit(3);
        $result = $number
            ->modulo(0)
            ->unpack();
        $this->assertNull($result);
    }
}
