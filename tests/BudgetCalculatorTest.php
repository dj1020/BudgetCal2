<?php

use PHPUnit\Framework\TestCase;

require __DIR__ . '/../src/BudgetCalculator.php';

class BudgetCalculatorTest extends TestCase
{
    /**
      * @test
      */
    public function it_should_initialize_an_instance_of_calculator()
    {
        // Act
        $calculator = new BudgetCalculator();

        // Assert
        $this->assertInstanceOf(BudgetCalculator::class, $calculator);
    }
}
