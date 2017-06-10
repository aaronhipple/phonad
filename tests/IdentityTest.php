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
    public function testDoesntWrapSelf()
    {
        $value = Identity::unit(3);
        $newValue = Identity::unit($value);
        $this->assertEquals(3, $newValue->unpack());
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
     * @group monad
     */
    public function testConstructsNothingFromNull()
    {
        $monad = Identity::unit(null);
        $this->assertInstanceOf(Nothing::class, $monad);
    }
}
