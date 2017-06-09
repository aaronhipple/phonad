<?php
use PHPUnit\Framework\TestCase;
use aaronhipple\phonad\Until;
use aaronhipple\phonad\Nothing;

class UntilTest extends TestCase
{
    /**
     * @group monad
     */
    public function testRetrievesValue()
    {
        $person = (object)[
            'name' => 'Haskell Curry',
            'age' => null,
            'birthday' => '1900-09-12',
        ];

        $ageMonad = new Until($person);

        $age = $ageMonad
            ->bind(function ($person) {
                if (!$person->age) {
                    return new Nothing;
                }
                return $person->age;
            })
            ->bind(function ($person) {
                if (!$person->birthday) {
                    return new Nothing;
                }
                $birthday = new DateTime($person->birthday);
                $now = new DateTime;
                $age = $birthday
                    ->diff($now)
                    ->format('%y');
                return intval($age);
            })
            ->unpack();

        $this->assertInternalType('int', $age);
    }

    /**
     * @group monad
     */
    public function testSomethingFallsThrough()
    {
        $person = (object)[
            'name' => 'Douglas Adams',
            'age' => 42,
            'birthday' => null,
        ];

        $ageMonad = new Until($person);

        $age = $ageMonad
            ->bind(function ($person) {
                if (!$person->age) {
                    return new Nothing;
                }
                return $person->age;
            })
            ->bind(function ($person) {
                throw new Exception('Unable to calculate age!');
            })
            ->unpack();

        $this->assertEquals(42, $age);
    }

    /**
     * @group operation
     * @group at
     */
    public function testTraversesWithAt()
    {
        $person = (object)[
            'name' => 'Verbal Kint',
            'realName' => 'Keyser Söze',
        ];

        $nameMonad = new Until($person);

        $name = $nameMonad
            ->at('name')
            ->at('realName')
            ->unpack();

        $this->assertEquals('Verbal Kint', $name);
    }

    /**
     * @group monad
     */
    public function testUnpacksToNullIfUnfulfilled()
    {
        $person = (object)[
            'name' => 'Verbal Kint',
            'realName' => 'Keyser Söze',
        ];

        $nameMonad = new Until($person);

        $name = $nameMonad
            ->at('fakeName')
            ->unpack();

        $this->assertNull($name);
    }

    /**
     * @group monad
     */
    public function testBindsToSelfIfUnfulfilled()
    {
        $until = new Until('value');

        $newUntil = $until
            ->bind(function () {
                return new Nothing;
            });

        $this->assertInstanceOf(Until::class, $newUntil);
    }
}
