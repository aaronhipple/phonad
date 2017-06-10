<?php

/**
 * @OutputTimeUnit("seconds")
 * @OutputMode("throughput")
 */
class ArithmeticBench
{
    /**
     * @Warmup(1)
     * @Iterations(100)
     */
    public function benchAddWithMonad()
    {
        $number = new Phonad\Identity(1);
        $result = $number
            ->add(3)
            ->add(2)
            ->add(1)
            ->unpack();
    }

    /**
     * @Iterations(100)
     */
    public function benchAddWithPHP()
    {
        $number = 1;
        $result = $number
            + 3
            + 2
            + 1;
    }


    public function initializeMapNumbers()
    {
        $this->mapNumbers = [];
        $i = 0;
        while ($i < 100) {
            $this->mapNumbers[] = $i++;
        }
    }

    /**
     * @BeforeMethods({"initializeMapNumbers"})
     * @Warmup(1)
     * @Iterations(50)
     */
    public function benchMapAddWithMonad()
    {
        $numbers = new Phonad\Collection(...$this->mapNumbers);
        $result = $numbers
            ->add(3)
            ->add(2)
            ->add(1)
            ->unpack();
    }

    /**
     * @BeforeMethods({"initializeMapNumbers"})
     * @Iterations(50)
     */
    public function benchMapAddWithPHPMap()
    {
        $numbers = $this->mapNumbers;
        $result = array_map(function ($number) {
            return $number
                + 3
                + 2
                + 1;
        });
    }

    /**
     * @BeforeMethods({"initializeMapNumbers"})
     * @Iterations(50)
     */
    public function benchMapAddWithPHPForEach()
    {
        $numbers = $this->mapNumbers;
        $result = [];
        foreach ($numbers as $number) {
            $result[] = $number
                + 3
                + 2
                + 1;
        }
    }
}
